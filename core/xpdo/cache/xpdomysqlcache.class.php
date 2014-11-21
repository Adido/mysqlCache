<?php
/*
 * Copyright 2014 Adido Ltd.
 *
 * This file is part of mysqlcache.
 *
 * mysqlcache is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * mysqlcache is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Caching; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * Provides a mysql-powered xPDOCache implementation.
 *
 * This requires the mysql extension for PHP.
 *
 * @package mysqlcache
 */
class xPDOMysqlCache extends xPDOCache {

    public function __construct(& $xpdo, $options = array()) {
        parent :: __construct($xpdo, $options);
        $this->initialized = true;
    }

	private function getTableName() {
		return "{$this->xpdo->getOption('table_prefix')}cache";
	}


    private function addUpdate($key, $ttl, $var) {
        $var = serialize($var);
	    $table = $this->getTableName($key);

        $sql = "INSERT INTO `$table` SET `key` = :key, `ttl` = :ttl, `data` = :var, added = :added ON DUPLICATE KEY UPDATE `data` = :var, `ttl` = :ttl, added=:added";
	    $query = new xPDOCriteria(
		    $this->xpdo,
		    $sql,
		    array(
		        ":key" => $this->getCacheKey($key),
		        ":var" => $var,
		        ":ttl" => $ttl,
		        ":added" => time(),
	    ));
	    if (!$query->prepare() || !$query->stmt->execute()) {
		    return false;
	    }
        return true;;
    }

    public function add($key, $var, $expire= 0, $options= array()) {
        return $this->addUpdate($key, $expire, $var);
    }

    public function set($key, $var, $expire= 0, $options= array()) {
        return $this->addUpdate($key, $expire, $var);
    }

    public function replace($key, $var, $expire= 0, $options= array()) {
        return $this->addUpdate($key, $expire, $var);
    }

    public function delete($key, $options= array()) {
	    $table = $this->getTableName();
        $sql = "DELETE FROM `$table` WHERE `key` = :key";
	    $query = new xPDOCriteria(
		    $this->xpdo,
		    $sql,
		    array(
			    ":key" => $this->getCacheKey($key),
		    ));
	    if (!$query->prepare() || !$query->stmt->execute()) {
		    return false;
	    }
	    return true;;
    }

    public function get($key, $options= array()) {
	    $table = $this->getTableName();
        $sql = "SELECT * FROM `$table` WHERE `key` = :key LIMIT 1";
	    $query = new xPDOCriteria(
		    $this->xpdo,
		    $sql,
		    array(
			    ":key" => $this->getCacheKey($key),
		    ));

	    if (!$query->prepare()) return false;
	    $r = $query->stmt->execute();
	    if(!$r) return false;

        $row = $r->fetch(PDO::FETCH_ASSOC);

        // check TTL on data
        if($row["ttl"]) {
            if(($row["added"] + $row["ttl"]) <= time()) {
                // expired - so delete and get again
                $this->delete($key);
                return false;
            }
        }
        $d =  unserialize($row["data"]);
        return $d;
    }

    public function flush($options= array()) {
	    $table = $this->getTableName();
	    $sql = "DELETE FROM `$table` WHERE `key` LIKE :key";
	    $query = new xPDOCriteria(
		    $this->xpdo,
		    $sql,
		    array(
			    ":key" => $this->key,
		    ));
	    if (!$query->prepare() || !$query->stmt->execute()) {
		    return false;
	    }
        return true;
    }
}
