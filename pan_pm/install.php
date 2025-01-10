<?php 

if(!defined('PAN_PM_INSTALL')) die();

if (!$forum_db->table_exists('pan_pm'))
{
	$schema = array(
		'FIELDS'		=> array(
			'id'			=> array(
				'datatype'		=> 'SERIAL',
				'allow_null'	=> false
			),
			'topic_id'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'	=> true,
			),
			'sender'		=> array(
				'datatype'		=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'		=> '\'\''
			),
			'sender_id'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'	=> true,
			),
			'receiver'		=> array(
				'datatype'		=> 'VARCHAR(200)',
				'allow_null'	=> false,
				'default'		=> '\'\''
			),
			'receiver_id'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'	=> true
			),
			'subject'			=> array(
				'datatype'		=> 'VARCHAR(255)',
				'allow_null'	=> false,
				'default'		=> '\'\''
			),
			'message'		=> array(
				'datatype'		=> 'TEXT',
				'allow_null'	=> true
			),
			'posted'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'	=> false,
				'default'		=> '0'
			),
			'status'		=> array(
				'datatype'		=> 'VARCHAR(9)',
				'allow_null'	=> false,
				'default'		=> '\'sent\'',
			),
			'sticky_for_sender'	=> array(
				'datatype'		=> 'TINYINT(1)',
				'allow_null'	=> false,
				'default'		=> '0'
			),
			'sticky_for_receiver'=> array(
				'datatype'		=> 'TINYINT(1)',
				'allow_null'	=> false,
				'default'		=> '0'
			),
			'deleted_by_sender'	=> array(
				'datatype'		=> 'TINYINT(1)',
				'allow_null'	=> false,
				'default'		=> '0'
			),
			'deleted_by_receiver'=> array(
				'datatype'		=> 'TINYINT(1)',
				'allow_null'	=> false,
				'default'		=> '0'
			),
			'encrypt'	=> array(
				'datatype'		=> 'VARCHAR(40)',
				'allow_null'	=> false,
				'default'		=> '\'0\'',
			),
		),
		'PRIMARY KEY'	=> array('id'),
		'INDEXES'		=> array(
			'sender_id_idx'		=> array('sender_id'),
			'receiver_id_idx'	=> array('receiver_id'),
		)
	);

	$forum_db->create_table('pan_pm', $schema);
}

forum_config_add('o_pan_pm_global_link', '0');
forum_config_add('o_pan_pm_max_inbox', '100');
forum_config_add('o_pan_pm_max_outbox', '100');
forum_config_add('o_pan_pm_active_contacts', '180');

if (!$forum_db->field_exists('users', 'pan_pm_admin_disable'))
	$forum_db->add_field('users', 'pan_pm_admin_disable', 'TINYINT(1)', false, '0');

if(!$forum_db->field_exists('users', 'enable_pm_email'))
	$forum_db->add_field('users', 'enable_pm_email', 'TINYINT(1)', false, 1);