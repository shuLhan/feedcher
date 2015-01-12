<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

namespace jaringphp;

class JaringOut
{
	public $success	= FALSE;
	public $total		= 0;
	public $data		= [];

	function __construct ($success = FALSE, $data = "", $total = 0)
	{
		$this->success = $success;
		$this->total = $total;
		$this->data = $data;
	}

	function set ($success = FALSE, $data = "", $total = 0)
	{
		$this->success = $success;
		$this->total = $total;
		$this->data = $data;
	}

	function get_json ()
	{
		return json_encode ($this);
	}
}
