<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

require_once "../../json_begin.php";

$q	= " select id, name
		from	feed_category";

try {
	$rs = $jaring->_db->execute ($q);

	foreach ($rs as &$category) {
		$category["link"] = "/category/?id=". $category["id"];
	}

	$jaring->_out->set (TRUE, $rs, count ($rs));
} catch (Exception $e) {
	$jaring->_out->data = $e->getMessage ();
}

require_once "../../json_end.php";
