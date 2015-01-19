<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

require_once "../json_begin.php";

$q = "	select	mime
		,		image
		from	images
		where	id = ?
	";

try {
	$bindv = array ($_GET["id"]);

	$ps = $jaring->_db->_dbo->prepare ($q);

	$ps->execute ($bindv);

	$ps->bindColumn (1, $mime, PDO::PARAM_STR);
	$ps->bindColumn (2, $lob, PDO::PARAM_LOB);
	$ps->fetch (PDO::FETCH_BOUND);

	header ("Content-Type: $mime");
	echo $lob;
}
catch (Exception $e) {
	echo $e.getMessage ();
}
