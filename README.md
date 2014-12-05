## Mysql caching Extra.

Installation:

Install through either the MODX provider or extras.io provider, then set the "cache_handler" system setting to `cache.xPDOMysqlCache`

You can also set the cache setting within the config options inside config.inc.php to:
```
$config_options = array (
	'cache_handler' => 'cache.xPDOMysqlCache'
);
```

During installation we move the "xpdomysqlcache.class.php" file from `core/components/mysqlcaching/model/xpdomysqlcache.class.php` to `core/xpdo/cache/xpdomysqlcache.class.php` This may fail due to file permissions (an error is displayed during install). If this happens please move the file manually.

Caching will then be database backed.

Although has been tested on MySQL no testing has been done on MSSQL or any other database types. But as long as LONGBLOB is supported - it should work OK

Currently Implemented:

-- Mysql Caching - single Table

Next features / testing

-- Multiple tables created for each cache partition

-- Benchmark new column with TTL for better select / delete processing

-- garbage collection to remove old TTL cached entries