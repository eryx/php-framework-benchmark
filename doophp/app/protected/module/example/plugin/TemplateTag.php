<?php

/**
 * This class is automatically loaded when render() is called from modules
 * Tag class for Modules are named as <Modulename>Tag
 * Every public static method in this class can be used in the templates
 */
class ExampleTag {
    public static function test(){
	    return 'Module tag class';
	}
}

?>