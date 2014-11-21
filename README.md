## Mysql caching Extra.

Installation:

Install through either the MODX provider or extras.io provider, then set the cache system setting to "cache.xPDOMysqlCache"

Caching will then be database backed.

Although has been tested on MySQL no testing has been done on MSSQL or any other database types. But as long as LONGBLOB is supported - it should work OK

Currently Implemented:

-- Mysql Caching - single Table

Next features / testing

-- Multiple tables created for each cache partition

-- Benchmark new column with TTL for better select / delete processing

-- garbage collection to remove old TTL cached entries

License:

This extension is released under the GPLv3 (see core/components/mysqlcaching/docs/license.txt)
