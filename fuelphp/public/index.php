<?php
if (isset($_GET['debug'])) {
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    // System Start Time
    define('START_TIME', microtime(true));
    // System Start Memory
    define('START_MEMORY_USAGE', memory_get_usage());
}

/**
 * Set error reporting and display errors settings.  You will want to change these when in production.
 */
error_reporting(-1);
ini_set('display_errors', 1);

/**
 * Website document root
 */
define('DOCROOT', __DIR__.DIRECTORY_SEPARATOR);

/**
 * Path to the application directory.
 */
define('APPPATH', realpath(__DIR__.'/../fuel/app/').DIRECTORY_SEPARATOR);

/**
 * Path to the default packages directory.
 */
define('PKGPATH', realpath(__DIR__.'/../fuel/packages/').DIRECTORY_SEPARATOR);

/**
 * The path to the framework core.
 */
define('COREPATH', realpath(__DIR__.'/../fuel/core/').DIRECTORY_SEPARATOR);

// Get the start time and memory for use later
defined('FUEL_START_TIME') or define('FUEL_START_TIME', microtime(true));
defined('FUEL_START_MEM') or define('FUEL_START_MEM', memory_get_usage());

// Boot the app
require APPPATH.'bootstrap.php';

// Generate the request, execute it and send the output.
try
{
	$response = Request::forge()->execute()->response();
}
catch (HttpNotFoundException $e)
{
	$route = array_key_exists('_404_', Router::$routes) ? Router::$routes['_404_']->translation : Config::get('routes._404_');
	if ($route)
	{
		$response = Request::forge($route)->execute()->response();
	}
	else
	{
		throw $e;
	}
}

// This will add the execution time and memory usage to the output.
// Comment this out if you don't use it.
/*$bm = Profiler::app_total();
$response->body(
	str_replace(
		array('{exec_time}', '{mem_usage}'),
		array(round($bm[0], 4), round($bm[1] / pow(1024, 2), 3)),
		$response->body()
	)
);*/

$response->send(true);

// Fire off the shutdown event
Event::shutdown();


if (!isset($_GET['debug'])) {
    die();
}

$xhprof_data = xhprof_disable();

echo "Page rendered in <b>"
    . round((microtime(true) - START_TIME), 5) * 1000 ." ms</b>, taking <b>"
    . round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 2) ." KB</b>";
$f = get_included_files();
echo ", include files: ".count($f);

$XHPROF_ROOT = realpath(dirname(__FILE__) .'/../..');
include_once $XHPROF_ROOT . "/xhprof/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof/xhprof_lib/utils/xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

echo ", xhprof <a href=\"http://xhprof.pfb.example.com/xhprof_html/index.php?run=$run_id&source=xhprof_foo\">url</a>";
