<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */
header('Content-Type: application/json');

$json = $jaring->_out->get_json ();

if (isset ($_GET["callback"])) {
	$json = $_GET["callback"]."(". $json .")";
}

echo $json;
