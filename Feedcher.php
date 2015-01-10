<?php
/**
 * Copyright Mhd Sulhan (ms@kilabit.info) 2014
 */

include "autoload.php";

use PicoFeed\Reader\Reader;
use PicoFeed\Client\Grabber;
use jaringphp\Jaring;

class Feedcher extends Jaring
{
	public $_reader = null;
	public $_grabber = null;
	public $_max_try = 3;

	public function __construct ($app_conf)
	{
		parent::__construct ($app_conf);

		$this->_reader = new Reader ();
		$this->_grabber = new Grabber ('');
		$this->_max_try = 3;
	}

	public function save ($feed_md, $resource, $feed)
	{
		$time_fetch = time ();
		$last_fetch = $feed_md["last_fetch"];
		$last_mod = $resource->getLastModified ();

		$q = " update feed"
			." set last_fetch = ?"
			." , etag = ?"
			." , last_mod = ?"
			." , description = ?"
			." where id = ?";

		$s = $this->_db->execute ($q, array (
				$time_fetch
			,	$resource->getEtag ()
			,	$last_mod == "" ? $time_fetch : $last_mod
			,	$feed->getDescription ()
			,	$feed_md["id"]
			));
	}

	public function fix_item (&$item)
	{
		// fix double slash in item's URL.
		$item->url = preg_replace ("/(?<!:)\/\//", "/", $item->url);

		// set description to content.
		$item->description = $item->content;
	}

	public function grab_content (&$item)
	{
		echo ">> grab content: $item->url".PHP_EOL;

		$this->_grabber->setUrl ($item->url);
		$this->_grabber->download ();
		$this->_grabber->parse ();

		$item->content = $this->_grabber->getContent ();
	}

	public function save_item ($feed_md, $item)
	{
		$q = " select count(id) as num_items "
			." from feed_item "
			." where id = ? and feed_id = ? ";

		$rs = $this->_db->execute ($q, array ($item->id, $feed_md["id"]));

		if ($rs == null) {
			return;
		}
		if (count ($rs) == 0) {
			return;
		}

		if ($rs[0]["num_items"] == 0) {
			$this->grab_content ($item);

			echo ">> save item   : $item->url".PHP_EOL;

			$q = " insert into feed_item
					( id
					, feed_id
					, date
					, title
					, url
					, description
					, author
					, content
					) values (?, ?, ?, ?, ?, ?, ?, ?)";

			$s = $this->_db->execute ($q, array (
						$item->id
					,	$feed_md["id"]
					,	$item->date
					,	$item->title
					,	$item->url
					,	$item->description
					,	$item->author
					,	$item->content
					)
					, false);
		}
	}

	public function fetch ($feed_md)
	{
		$n_try = 0;

		while ($n_try < $this->_max_try) {
			try {
				$resource = $this->_reader->download ($feed_md["url"]
								, $feed_md["last_mod"]
								, $feed_md["etag"]
							);

				if ($resource->getStatusCode() == 200) {
					break;
				}
			}
			catch (PicoFeed\Client\TimeoutException $e) {
				++$n_try;
			}
		}

		try {
			if ($resource->getStatusCode () == 200
			&& $resource->isModified ()) {
				echo ">> modified ".$feed_md["url"].PHP_EOL;

				$parser = $this->_reader->getParser (
								$resource->getUrl()
							,	$resource->getContent()
							,	$resource->getEncoding()
						);

				$feed = $parser->execute();

				foreach ($feed->items as $item) {
					$this->fix_item ($item);
					$this->save_item ($feed_md, $item);
				}

				$this->save ($feed_md, $resource, $feed);
			}
		}
		catch (Exception $e) {
			echo $e;
		}
	}

	public function run ()
	{
		try {
			$this->db_init ();

			$q = "select id, url, etag, last_mod, last_fetch from feed";
			$rs = $this->_db->execute ($q);

			foreach ($rs as $feed_md) {
				$this->fetch ($feed_md);
			}
		}
		catch (Exception $e) {
			echo $e;
		}
	}
}

$feedcher = new Feedcher ($argv[1]);

$feedcher->run ();
