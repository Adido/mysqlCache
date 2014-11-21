<?php
/**
 * @package mysqlcaching
 */
$xpdo_meta_map['Cache']= array (
  'package' => 'mysqlcaching',
  'version' => '1.1',
  'table' => 'cache',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'key' => NULL,
    'added' => NULL,
    'ttl' => NULL,
    'data' => NULL,
  ),
  'fieldMeta' => 
  array (
    'key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'null' => false,
    ),
    'added' => 
    array (
      'dbtype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'ttl' => 
    array (
      'dbtype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'data' => 
    array (
      'dbtype' => 'longblob',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'key' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
