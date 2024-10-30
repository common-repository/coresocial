<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Plugins\InstallDB as BaseInstallDB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class InstallDB extends BaseInstallDB {
	protected $version = 20230616;
	protected $prefix = 'coresocial';
	protected $tables = array(
		'items'  => array(
			'name'    => 'items',
			'columns' => 5,
			'scope'   => 'blog',
			'data'    => "id bigint(20) unsigned NOT NULL AUTO_INCREMENT, \n" .
			             "item varchar(16) NOT NULL DEFAULT 'post', \n" .
			             "item_id bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "item_hash varchar(32) NOT NULL DEFAULT '', \n" .
			             "url varchar(1024) NULL DEFAULT NULL, \n" .
			             "PRIMARY KEY  (id), \n" .
			             "KEY item (item), \n" .
			             "KEY item_id (item_id), \n" .
			             "KEY item_hash (item_hash)",
		),
		'log'    => array(
			'name'    => 'log',
			'columns' => 8,
			'scope'   => 'blog',
			'data'    => "id bigint(20) unsigned NOT NULL AUTO_INCREMENT, \n" .
			             "item_id bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "user_id bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "user_hash varchar(32) NOT NULL DEFAULT '', \n" .
			             "network varchar(64) NOT NULL DEFAULT '', \n" .
			             "action varchar(64) NOT NULL DEFAULT 'share', \n" .
			             "module varchar(64) NOT NULL DEFAULT 'inline', \n" .
			             "logged datetime NULL DEFAULT NULL, \n" .
			             "PRIMARY KEY  (id), \n" .
			             "KEY item_id (item_id), \n" .
			             "KEY user_id (user_id), \n" .
			             "KEY user_hash (user_hash), \n" .
			             "KEY network (network), \n" .
			             "KEY action (action), \n" .
			             "KEY module (module), \n" .
			             "KEY logged (logged)",
		),
		'counts' => array(
			'name'    => 'counts',
			'columns' => 6,
			'scope'   => 'blog',
			'data'    => "id bigint(20) unsigned NOT NULL AUTO_INCREMENT, \n" .
			             "item_id bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "network varchar(64) NOT NULL DEFAULT '', \n" .
			             "internal bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "online bigint(20) unsigned NOT NULL DEFAULT 0, \n" .
			             "updated datetime NULL DEFAULT NULL, \n" .
			             "PRIMARY KEY  (id), \n" .
			             "UNIQUE KEY item_id_network (item_id, network), \n" .
			             "KEY item_id (item_id), \n" .
			             "KEY network (network), \n" .
			             "KEY updated (updated)",
		),
	);

	public static function instance() : InstallDB {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new InstallDB();
		}

		return $instance;
	}
}
