<?php
$xpdo_meta_map['Routes']= array (
  'package' => 'genericrouter',
  'version' => '1.1',
  'table' => 'genericrouter_routes',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'expression' => 'null',
    'type' => 0,
    'target' => 0,
    'name' => NULL,
    'priority' => 999,
    'enabled' => 1,
    'representation' => '',
    'mode' => 0,
  ),
  'fieldMeta' => 
  array (
    'expression' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '170',
      'phptype' => 'string',
      'null' => true,
      'default' => 'null',
    ),
    'type' => 
    array (
      'dbtype' => 'int',
      'precision' => '2',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'target' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '80',
      'phptype' => 'string',
      'null' => false,
    ),
    'priority' => 
    array (
      'dbtype' => 'int',
      'precision' => '3',
      'phptype' => 'integer',
      'null' => false,
      'default' => 999,
    ),
    'enabled' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
    'representation' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'mode' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
  ),
);
