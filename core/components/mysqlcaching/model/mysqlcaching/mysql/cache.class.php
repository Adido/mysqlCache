<?php
/**
 * @package mysqlcache
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/cache.class.php');
class Cache_mysql extends Cache {}