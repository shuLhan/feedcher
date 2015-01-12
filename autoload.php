<?php
/**
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

define ("MAIN_PATH", dirname (realpath (__FILE__)) ."/");

$classes_dir = array (
	MAIN_PATH . "lib/picoFeed/lib/"
,	MAIN_PATH . "lib/"
);

function class_autoload ($class_name)
{
	global $classes_dir;

	$class_path = str_replace ("\\", "/", $class_name);

	foreach ($classes_dir as $directory) {
		if (file_exists ($directory . $class_path . '.php')) {
			require_once ($directory . $class_path . '.php');
			return;
        }
    }
}

spl_autoload_register ("class_autoload");
