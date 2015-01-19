<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

require_once "../../json_begin.php";

$q	= " select	A.id
		,		A.name
		,		A.description
		,		A.logo_id
		from	feed_category	A
";

try {
	$rs = $jaring->_db->execute ($q);

	foreach ($rs as &$category) {
		$category["link"] = "/feed/category/?id=". $category["id"];
		$category["image"] = "/images/?id=". $category["logo_id"];
	}

	$jaring->_out->set (TRUE, $rs, count ($rs));
} catch (Exception $e) {
	$jaring->_out->data = $e->getMessage ();
}

require_once "../../json_end.php";
