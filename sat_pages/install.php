<?php 
if(!defined('SAT_PAGES_INSTALL')) die();

if (!$forum_db->table_exists('sat_category'))
{
	$schema = array(
		'FIELDS'	=> array(
			'id'		=> array(
				'datatype'	=> 'SERIAL',
				'allow_null'	=> false
			),
			'name'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> '\'Category1\''
			),
			'announce'	=> array(
				'datatype'	=> 'BOOLEAN',
				'allow_null'	=> false,
				'default'	=> true
			),
			'showcont'	=> array(
				'datatype'	=> 'BOOLEAN',
				'allow_null'	=> false,
				'default'	=> true
			),
			'denyaccess'		=> array(
				'datatype'	=> 'TEXT',
				'allow_null'	=> true
			)
		),
		'PRIMARY KEY'	=> array('id'),
		'INDEXES'	=> array(
			'page_name_idx'		=> array('name')
		)
	);

	$forum_db->create_table('sat_category', $schema);
}

if (!$forum_db->table_exists('sat_pages'))
{
	$schema = array(
		'FIELDS'	=> array(
			'id'		=> array(
				'datatype'	=> 'SERIAL',
				'allow_null'	=> false
			),
			'name'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> '\'Page\''
			),
			'category_id'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> true
			),
			'announce'	=> array(
				'datatype'	=> 'BOOLEAN',
				'allow_null'	=> false,
				'default'	=> true
			),
			'denyaccess'		=> array(
				'datatype'	=> 'TEXT',
				'allow_null'	=> true
			),
			'txt'		=> array(
				'datatype'	=> 'MEDIUMTEXT',
				'allow_null'	=> true
			)
		),
		'PRIMARY KEY'	=> array('id'),
		'INDEXES'	=> array(
			'page_name_idx'		=> array('name')
		)
	);

	$forum_db->create_table('sat_pages', $schema);
}