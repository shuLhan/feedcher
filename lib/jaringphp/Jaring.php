<?php
/*
 * Copyright Mhd Sulhan (ms@kilabit.info) - 2014
 */

namespace jaringphp;

use jaringphp\JaringDB;
use jaringphp\JaringOut;

class Jaring
{
	public $_config = NULL;
	public $_path = NULL;
	public $_db = NULL;
	public $_out = NULL;

	/**
	 * Application path default to configuration file.
	 */
	public function __construct ($app_conf)
	{
		$this->load_config ($app_conf);
		$this->load_path ($app_conf);
		$this->_out = new JaringOut ();
	}

	public function load_config ($app_conf)
	{
		$str = file_get_contents ($app_conf);

		if (FALSE === $str) {
			throw new \Exception ("jaring: load config failed!");
		}

		$this->_config = json_decode ($str);

		if (! $this->_config) {
			throw new \Exception ("jaring: config decode failed!");
		}
	}

	public function load_path ($app_conf)
	{
		$path = realpath ($app_conf);

		if (! $path) {
			throw new \Exception ("jaring: can not get application path!");
		}

		$this->_path = dirname ($path) ."/";
	}

	public function db_init ()
	{
		// If databae class is sqlite, check if dbname absolute or relative.
		// If it is relative, make it absolute.
		if ($this->_config->database->class == "sqlite") {
			$dbname = $this->_config->database->dbname;

			if ($dbname[0] == '.') {
				$dbname = substr ($dbname, 2);

				if (FALSE === $dbname) {
					throw new \Exception ("jaring: substr error.");
				}

				$dbname = $this->_path . $dbname;
			} else if ($dbname[0] != '/') {
				$dbname = $this->_path . $dbname;
			}

			$this->_config->database->dbname = $dbname;
		}

		$this->_db = new JaringDB ($this->_config->database->class
						, $this->_config->database->host
						, $this->_config->database->port
						, $this->_config->database->dbname
						, $this->_config->database->username
						, $this->_config->database->password
						, $this->_config->database->pool_min
						, $this->_config->database->pool_max
					);

		$this->_db->init ();
	}
}
