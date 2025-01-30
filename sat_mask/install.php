<?php
if(!defined('SAT_MASK_INSTALL')) die();

if (!$forum_db->field_exists('users', 'masks'))
    $forum_db->add_field('users', 'masks', 'INT(10)', false, 3);

if (!$forum_db->field_exists('posts', 'mask_name'))
    $forum_db->add_field('posts', 'mask_name', 'VARCHAR(255)', true);
if (!$forum_db->field_exists('posts', 'mask_title'))
    $forum_db->add_field('posts', 'mask_title', 'VARCHAR(255)', true);
if (!$forum_db->field_exists('posts', 'mask_ava'))
    $forum_db->add_field('posts', 'mask_ava', 'VARCHAR(255)', true);
if (!$forum_db->field_exists('posts', 'mask_sig'))
    $forum_db->add_field('posts', 'mask_sig', 'TEXT', true);

if (!$forum_db->table_exists('sat_masks')) {
    $schema = array(
        'FIELDS'      => array(
            'id'            	=> array(
                'datatype'        => 'SERIAL',
                'allow_null'      => false
            ),
            'save_name'          => array(
                'datatype'        => 'VARCHAR(100)',
                'allow_null'      => false
            ),
            'user_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'name'          => array(
                'datatype'        => 'VARCHAR(255)',
                'allow_null'      => true
            ),
            'title'          => array(
                'datatype'        => 'VARCHAR(255)',
                'allow_null'      => true
            ),
            'avatar'          => array(
                'datatype'        => 'VARCHAR(255)',
                'allow_null'      => true
            ),
            'signature'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => true
            )
        ),
        'PRIMARY KEY' => array('id'),
	'INDEXES'	=> array(
		'uid_idx'		=> array('user_id')
	)
    );
    $forum_db->create_table('sat_masks', $schema);
}
