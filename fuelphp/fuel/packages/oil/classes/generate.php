<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Oil;

/**
 * Oil\Generate Class
 *
 * @package		Fuel
 * @subpackage	Oil
 * @category	Core
 * @author		Phil Sturgeon
 */
class Generate
{
	public static $create_folders = array();
	public static $create_files = array();

	public static $scaffolding = false;

	private static $_default_constraints = array(
		'varchar' => 255,
		'char' => 255,
		'int' => 11
	);

	public static function config($args)
	{
		$file = strtolower(array_shift($args));

		if (empty($file))
		{
			throw new Exception('No config filename has been provided.');
		}

		$config = array();

		// load the config
		if ($paths = \Finder::search('config', $file, '.php', true))
		{
			// Reverse the file list so that we load the core configs first and
			// the app can override anything.
			$paths = array_reverse($paths);
			foreach ($paths as $path)
			{
				$config = \Fuel::load($path) + $config;
			}
		}
		unset($path);

		// We always pass in fields to a config, so lets sort them out here.
		foreach ($args as $conf)
		{
			// Each paramater for a config is seperated by the : character
			$parts = explode(":", $conf);

			// We must have the 'name:value' if nothing else!
			if (count($parts) >= 2)
			{
				$config[$parts[0]] = $parts[1];
			}
		}

		$overwrite = (\Cli::option('o') or \Cli::option('overwrite'));

		// strip whitespace and add tab
		$export = str_replace(array('  ', 'array ('), array("\t", 'array('), var_export($config, true));

		$content = '<?php'.PHP_EOL.PHP_EOL.'return '.$export.';';
		$content .= <<<CONF


/* End of file $file.php */
CONF;

		$module = \Cli::option('module', \Cli::option('m'));

		// add support for `php oil g config module::file arg1:value1`
		if (strpos($file, '::') !== false)
		{
			list($module, $file) = explode('::', $file);
		}

		// get the namespace path (if available)
		if ( ! empty($module) and $path = \Autoloader::namespace_path('\\'.ucfirst($module)))
		{
			// strip the classes directory as we need the module root
			// and construct the filename
			$path = substr($path,0, -8).'config'.DS.$file.'.php';
			$path_name = "\\".ucfirst($module).'::';
		}
		elseif ( ! empty($module))
		{
			throw new Exception("{$module} need to be loaded first, please use config always_load.modules.");
		}
		else
		{
			$path = APPPATH.'config'.DS.$file.'.php';
			$path_name = 'APPPATH/';
		}

		if ( ! $overwrite and is_file($path))
		{
			throw new Exception("{$path_name}/config/{$file}.php already exist, please use --overwrite option to force update");
		}

		$path = pathinfo($path);

		try
		{
			\File::update($path['dirname'], $path['basename'], $content);
			\Cli::write("Created config: {$path_name}config/{$file}.php", 'green');
		}
		catch (\InvalidPathException $e)
		{
			throw new Exception("Invalid basepath, cannot update at ".$path_name."config".DS."{$file}.php");
		}
		catch (\FileAccessException $e)
		{
			throw new Exception($path_name."config".DS.$file.".php could not be written.");
		}
	}

	public static function controller($args, $build = true)
	{
		if ( ! ($name = \Str::lower(array_shift($args))))
		{
			throw new Exception('No controller name was provided.');
		}

		$actions = $args;

		$filename = trim(str_replace(array('_', '-'), DS, $name), DS);

		$filepath = APPPATH.'classes'.DS.'controller'.DS.$filename.'.php';

		// Uppercase each part of the class name and remove hyphens
		$class_name = \Inflector::classify($name);

		// Stick "blog" to the start of the array
		array_unshift($args, $filename);

		// Create views folder and each view file
		static::views($args, 'crud', false);

		$actions or $actions = array('index');

		$action_str = '';
		foreach ($actions as $action)
		{
			$action_str .= '
	public function action_'.$action.'()
	{
		$this->template->title = \'' . \Inflector::humanize($name) .' &raquo; ' . \Inflector::humanize($action) . '\';
		$this->template->content = View::forge(\''.$filename.'/' . $action .'\');
	}'.PHP_EOL;
		}

		$extends = \Cli::option('extends', 'Controller_Template');

		// Build Controller
		$controller = <<<CONTROLLER
<?php

class Controller_{$class_name} extends {$extends}
{
{$action_str}
}

CONTROLLER;

		// Write controller
		static::create($filepath, $controller, 'controller');

		$build and static::build();
	}


	public static function model($args, $build = true)
	{
		$singular = \Inflector::singularize(\Str::lower(array_shift($args)));

		if (empty($singular) or strpos($singular, ':'))
		{
			throw new Exception("Command is invalid.".PHP_EOL."\tphp oil g model <modelname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]");
		}

		if (empty($args))
		{
			throw new Exception('No fields have been provided, the model will not know how to build the table.');
		}

		$plural = \Inflector::pluralize($singular);

		$filename = trim(str_replace(array('_', '-'), DS, $singular), DS);

		$filepath = APPPATH . 'classes/model/'.$filename.'.php';

		// Uppercase each part of the class name and remove hyphens
		$class_name = \Inflector::classify($singular, false);

		$contents = '';

		$timestamp_properties = array();

		if ( ! \Cli::option('no-timestamp'))
		{
			$timestamp_properties = array('created_at:int', 'updated_at:int');
		}

		// Turn foo:string into "id", "foo",
		$properties = implode(",\n\t\t", array_map(function($field) {

			// Only take valid fields
			if (($field = strstr($field, ':', true)))
			{
				return "'".$field."'";
			}

		}, array_merge(array('id:int'), $args, $timestamp_properties)));

		if ( ! \Cli::option('no-properties'))
		{
			$contents .= <<<CONTENTS
	protected static \$_properties = array(
		{$properties}
	);

CONTENTS;
		}

		if (\Cli::option('crud'))
		{
			$contents .= <<<CONTENTS

	protected static \$_table_name = '{$plural}';

CONTENTS;
			$model = <<<MODEL
<?php

class Model_{$class_name} extends \Model_Crud
{
{$contents}
}

MODEL;
		}
		else
		{
			if ( ! \Cli::option('no-timestamp'))
			{
				$contents .= <<<CONTENTS

	protected static \$_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
CONTENTS;
			}

			$model = <<<MODEL
<?php

class Model_{$class_name} extends \Orm\Model
{
{$contents}
}

MODEL;
		}

		// Build the model
		static::create($filepath, $model, 'model');

		if ( ! empty($args))
		{
			array_unshift($args, 'create_'.$plural);
			static::migration($args, false);
		}

		else
		{
			throw new Exception('Not enough arguments to create this migration.');
		}

		$build and static::build();
	}


	public static function views($args, $subfolder, $build = true)
	{
		$controller = strtolower(array_shift($args));
		$controller_title = \Inflector::humanize($controller);

		$view_dir = APPPATH.'views/'.trim(str_replace(array('_', '-'), DS, $controller), DS).DS;

		$args or $args = array('index');

		// Make the directory for these views to be store in
		is_dir($view_dir) or static::$create_folders[] = $view_dir;

		// Add the default template if it doesnt exist
		if ( ! file_exists($app_template = APPPATH.'views/template.php'))
		{
			static::create($app_template, file_get_contents(PKGPATH.'oil/views/'.$subfolder.'/template.php'), 'view');
		}

		foreach ($args as $action)
		{
			$view_title = \Inflector::humanize($action);
			$view = <<<VIEW
<p>{$view_title}</p>
VIEW;

			// Create this view
			static::create($view_dir.$action.'.php', $view, 'view');
		}

		$build and static::build();
	}


	public static function migration($args, $build = true)
	{
		// Get the migration name
		$migration_name = \Str::lower(str_replace(array('-', '/'), '_', array_shift($args)));

		if (empty($migration_name) or strpos($migration_name, ':'))
		{
			throw new Exception("Command is invalid.".PHP_EOL."\tphp oil g migration <migrationname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]");
		}

		// Check if a migration with this name already exists
		if (count($duplicates = glob(APPPATH."migrations/*_{$migration_name}*")) > 0)
		{
			// Don't override a file
			if (\Cli::option('s', \Cli::option('skip')) === true)
			{
				return;
			}

			// Tear up the file path and name to get the last duplicate
			$file_name = pathinfo(end($duplicates), PATHINFO_FILENAME);

			// Override the (most recent) migration with the same name by using its number
			if (\Cli::option('f', \Cli::option('force')) === true)
			{
				list($number) = explode('_', $file_name);
			}

			// Name clashes but this is done by hand. Assume they know what they're doing and just increment the file
			elseif (static::$scaffolding === false)
			{
				// Increment the name of this
				$migration_name = \Str::increment(substr($file_name, 4), 2);
			}
		}

		// See if the action exists
		$methods = get_class_methods(__NAMESPACE__ . '\Generate_Migration_Actions');

		// For empty migrations that dont have actions
		$migration = array('', '');

		// Loop through the actions and act on a matching action appropriately
		foreach ($methods as $method_name)
		{
			// If the miration name starts with the name of the action method
			if (substr($migration_name, 0, strlen($method_name)) === $method_name)
			{
				/**
				 *	Create an array of the subject the migration is about
				 *
				 *	- In a migration named 'create_users' the subject is 'users' since thats what we want to create
				 *		So it would be the second object in the array
				 *			array(false, 'users')
				 *
				 *	- In a migration named 'add_name_to_users' the object is 'name' and the subject is 'users'.
				 *		So again 'users' would be the second object, but 'name' would be the first
				 *			array('name', 'users')
				 *
				 */
				$subjects = array(false, false);
				$matches = explode('_', str_replace($method_name . '_', '', $migration_name));

				// create_{table}
				if (count($matches) == 1)
				{
					$subjects = array(false, $matches[0]);
				}

				// add_{field}_to_{table}
				else if (count($matches) == 3 && $matches[1] == 'to')
				{
					$subjects = array($matches[0], $matches[2]);
				}

				// rename_field_{field}_to_{field}_in_{table} (with underscores in field names)
				else if (count($matches) >= 5 && in_array('to', $matches) && in_array('in', $matches))
				{
					$subjects = array(
					 implode('_', array_slice($matches, array_search('in', $matches)+1)),
					 implode('_', array_slice($matches, 0, array_search('to', $matches))),
					 implode('_', array_slice($matches, array_search('to', $matches)+1, array_search('in', $matches)-2))
				  );
				}

				// create_{table} or drop_{table} (with underscores in table name)
				else if (count($matches) !== 0)
				{
					$name = str_replace(array('create_', 'add_', '_to_'), array('create-', 'add-', '-to-'), $migration_name);

    				if (preg_match('/^(create|add)\-([a-z0-9\_]*)(\-to\-)?([a-z0-9\_]*)?$/i', $name, $deep_matches))
    				{
    					switch ($deep_matches[1])
    					{
    						case 'create' :
    							$subjects = array(false, $deep_matches[2]);
    						break;

    						case 'add' :
    							$subjects = array($deep_matches[2], $deep_matches[4]);
    						break;
    					}
    				}
				}

				// There is no subject here so just carry on with a normal empty migration
				else
				{
					break;
				}

				// We always pass in fields to a migration, so lets sort them out here.
				$fields = array();
				foreach ($args as $field)
				{
					$field_array = array();

					// Each paramater for a field is seperated by the : character
					$parts = explode(":", $field);

					// We must have the 'name:type' if nothing else!
					if (count($parts) >= 2)
					{
						$field_array['name'] = array_shift($parts);
						foreach ($parts as $part_i => $part)
						{
							preg_match('/([a-z0-9_-]+)(?:\[([0-9a-z\,\s]+)\])?/i', $part, $part_matches);
							array_shift($part_matches);

							if (count($part_matches) < 1)
							{
								// Move onto the next part, something is wrong here...
								continue;
							}

							$option_name = ''; // This is the name of the option to be passed to the action in a field
							$option = $part_matches;

							// The first option always has to be the field type
							if ($part_i == 0)
							{
								$option_name = 'type';
								$type = $option[0];
								if ($type === 'string')
								{
									$type = 'varchar';
								}
								else if ($type === 'integer')
								{
									$type = 'int';
								}

								if ( ! in_array($type, array('text', 'blob', 'datetime', 'date', 'timestamp', 'time')))
								{
									if ( ! isset($option[1]) || $option[1] == NULL)
									{
										if (isset(self::$_default_constraints[$type]))
										{
											$field_array['constraint'] = self::$_default_constraints[$type];
										}
									}
									else
									{
										// should support field_name:enum[value1,value2]
										if ($type === 'enum')
										{
											$values = explode(',', $option[1]);
											$option[1] = '"'.implode('","', $values).'"';

											$field_array['constraint'] = $option[1];
										}
										// should support field_name:decimal[10,2]
										elseif (in_array($type, array('decimal', 'float')))
										{
											$field_array['constraint'] = $option[1];
										}
										else
										{
											$field_array['constraint'] = (int) $option[1];
										}

									}
								}
								$option = $type;
							}
							else
							{
								// This allows you to put any number of :option or :option[val] into your field and these will...
								// ... always be passed through to the action making it really easy to add extra options for a field
								$option_name = array_shift($option);
								if (count($option) > 0)
								{
									$option = $option[0];
								}
								else
								{
									$option = true;
								}
							}

							$field_array[$option_name] = $option;

						}
						$fields[] = $field_array;
					}
					else
					{
						// Invalid field passed in
						continue;
					}
				}

				// Call the magic action which returns an array($up, $down) for the migration
				$migration = call_user_func(__NAMESPACE__ . "\Generate_Migration_Actions::{$method_name}", $subjects, $fields);
			}
		}

		// Build the migration
		list($up, $down)=$migration;

		$migration_name = ucfirst(strtolower($migration_name));

		$migration = <<<MIGRATION
<?php

namespace Fuel\Migrations;

class {$migration_name}
{
	public function up()
	{
{$up}
	}

	public function down()
	{
{$down}
	}
}
MIGRATION;

		$number = isset($number) ? $number : static::_find_migration_number();
		$filepath = APPPATH . 'migrations/'.$number.'_' . strtolower($migration_name) . '.php';

		static::create($filepath, $migration, 'migration');

		$build and static::build();
	}


	public static function help()
	{
		$output = <<<HELP
Usage:
  php oil [g|generate] [controller|model|migration|scaffold|views] [options]

Runtime options:
  -f, [--force]    # Overwrite files that already exist
  -s, [--skip]     # Skip files that already exist
  -q, [--quiet]    # Supress status output
  -t, [--speak]    # Speak errors in a robot voice

Description:
  The 'oil' command can be used to generate MVC components, database migrations
  and run specific tasks.

Examples:
  php oil generate controller <controllername> [<action1> |<action2> |..]
  php oil g model <modelname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]
  php oil g migration <migrationname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]
  php oil g scaffold <modelname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]
  php oil g scaffold/template_subfolder <modelname> [<fieldname1>:<type1> |<fieldname2>:<type2> |..]
  php oil g config <filename> [<key1>:<value1> |<key2>:<value2> |..]

Note that the next two lines are equivalent:
  php oil g scaffold <modelname> ...
  php oil g scaffold/crud <modelname> ...

Documentation:
  http://docs.fuelphp.com/packages/oil/generate.html
HELP;

		\Cli::write($output);
	}


	public static function create($filepath, $contents, $type = 'file')
	{
		$directory = dirname($filepath);
		is_dir($directory) or static::$create_folders[] = $directory;

		// Check if a file exists then work out how to react
		if (file_exists($filepath))
		{
			// Don't override a file
			if (\Cli::option('s', \Cli::option('skip')) === true)
			{
				// Don't bother trying to make this, carry on camping
				return;
			}

			// If we aren't skipping it, tell em to use -f
			if (\Cli::option('f', \Cli::option('force')) === null)
			{
				throw new Exception($filepath .' already exists, use -f or --force to override.');
				exit;
			}
		}

		static::$create_files[] = array(
			'path' => $filepath,
			'contents' => $contents,
			'type' => $type
		);
	}


	public static function build()
	{
		foreach (static::$create_folders as $folder)
		{
			is_dir($folder) or mkdir($folder, 0755, TRUE);
		}

		foreach (static::$create_files as $file)
		{
			\Cli::write("\tCreating {$file['type']}: {$file['path']}", 'green');

			if ( ! $handle = @fopen($file['path'], 'w+'))
			{
				throw new Exception('Cannot open file: '. $file['path']);
			}

			$result = @fwrite($handle, $file['contents']);

			// Write $somecontent to our opened file.
			if ($result === FALSE)
			{
				throw new Exception('Cannot write to file: '. $file['path']);
			}

			@fclose($handle);

			@chmod($file['path'], 0666);
		}

		return $result;
	}

	public static function class_name($name)
	{
		return str_replace(array(' ', '-'), '_', ucwords(str_replace('_', ' ', $name)));
	}

	// Helper methods

	private static function _find_migration_number()
	{
		$glob = glob(APPPATH .'migrations/*_*.php');
		list($last) = explode('_', basename(end($glob)));

		return str_pad($last + 1, 3, '0', STR_PAD_LEFT);
	}

	private static function _update_current_version($version)
	{
		if (file_exists($app_path = APPPATH.'config'.DS.'migrations.php'))
		{
			$contents = file_get_contents($app_path);
		}
		elseif (file_exists($core_path = COREPATH.'config'.DS.'migrations.php'))
		{
			$contents = file_get_contents($core_path);
		}
		else
		{
			throw new Exception('Config file core/config/migrations.php');
			exit;
		}

		$contents = preg_replace("#('version'[ \t]+=>)[ \t]+([0-9]+),#i", "$1 $version,", $contents);

		static::create($app_path, $contents, 'config');
	}
}

/* End of file oil/classes/generate.php */
