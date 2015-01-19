<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 *
 * TODO: add start and limit.
 */

require_once "../../json_begin.php";

$q	= " select	FI.id
		,		FI.date
		,		FI.title
		,		FI.url
		,		FI.author
		,		FI.description
		,		FI.content
		,		FI.cover_image
		from	feed_category	FC
		,		feed			FE
		,		feed_item		FI
		where	FC.id = ?
		and		FE.feed_category_id = FC.id
		and		FI.feed_id = FE.id
		order by FI.date DESC
	";

try {
	if (! isset ($_GET["id"])) {
		throw new Exception ("Category is empty");
	}

	$bindv = array ($_GET["id"]);

	$rs = $jaring->_db->execute ($q, $bindv);

	$jaring->_out->set (TRUE, $rs, count ($rs));
} catch (Exception $e) {
	$jaring->_out->data = $e->getMessage ();
}

require_once "../../json_end.php";
