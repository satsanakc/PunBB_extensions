<?php 
if(!defined('SAT_SHOP_INSTALL')) die();

forum_config_add('o_sat_shop_show_announce', '1');
forum_config_add('o_sat_shop_testmode', '1');
forum_config_add('o_sat_shop_commission', '3.9');
forum_config_add('o_sat_shop_mrh_login', '');
forum_config_add('o_sat_shop_mrh_pass1', '');
forum_config_add('o_sat_shop_mrh_pass2', '');
forum_config_add('o_sat_shop_test_pass1', '');
forum_config_add('o_sat_shop_test_pass2', '');



if (!$forum_db->table_exists('sat_shop_goods')) {
	$schema = array(
		'FIELDS'	=> array(
			'id'		=> array(
				'datatype'	=> 'SERIAL',
				'allow_null'	=> false
			),
			'cat'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> false
			),
			'name'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> ''
			),
			'txt'		=> array(
				'datatype'	=> 'TEXT',
				'allow_null'	=> true
			),
			'desc'		=> array(
				'datatype'	=> 'TEXT',
				'allow_null'	=> true
			),
			'price'		=> array(
				'datatype'	=> 'FLOAT(10,2) UNSIGNED',
				'allow_null'	=> false,
				'default'	=> 0
			),
			'discuss'		=> array(
				'datatype'	=> 'VARCHAR(500)',
				'allow_null'	=> false,
				'default'	=> '/smart/post.php?fid=2'
			),
			'exist'	=> array(
				'datatype'	=> 'BOOLEAN',
				'allow_null'	=> false,
				'default'	=> true
			)
		),
		'PRIMARY KEY'	=> array('id')
	);
	$forum_db->create_table('sat_shop_goods', $schema);
}

if (!$forum_db->table_exists('sat_shop_orders')) {
	$schema = array(
		'FIELDS'	=> array(
			'id'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> false
			),
			'time'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> true
			),
			'gid'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> false
			),
			'gname'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> ''
			),
			'gprice'		=> array(
				'datatype'	=> 'FLOAT(10,2) UNSIGNED',
				'allow_null'	=> false,
				'default'	=> 0
			),
			'uid'		=> array(
				'datatype'	=> 'INT(10) UNSIGNED',
				'allow_null'	=> false
			),
			'uname'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> ''
			),
			'utel'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> ''
			),
			'uaddress'		=> array(
				'datatype'	=> 'VARCHAR(500)',
				'allow_null'	=> false,
				'default'	=> ''
			),
			'status'		=> array(
				'datatype'	=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'	=> ''
			)
		),
		'PRIMARY KEY'	=> array('id'),
		'INDEXES'	=> array(
			'uid_idx'		=> array('uid')
		)
	);
	$forum_db->create_table('sat_shop_orders', $schema);
}