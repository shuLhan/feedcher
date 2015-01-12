<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

require_once "autoload.php";

use jaringphp\Jaring;

$jaring = new Jaring (MAIN_PATH ."app.conf");

try {
	$jaring->db_init ();
}
catch (Exception $e) {
	$jaring->out->data = $e.getMessage ();
}
