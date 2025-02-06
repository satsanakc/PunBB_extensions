<?php
if(!defined('SAT_DRAFT_INSTALL')) die();

if (!$forum_db->field_exists('users', 'drafts'))
    $forum_db->add_field('users', 'drafts', 'INT(10)', false, 5);

if (!$forum_db->table_exists('sat_drafts')) {
    $schema = array(
        'FIELDS'      => array(
            'save_name'          => array(
                'datatype'        => 'VARCHAR(100)',
                'allow_null'      => true
            ),
            'user_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'topic_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => true
            ),
            'message'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => true
            )
        ),
	'INDEXES'	=> array(
		'uid_idx'		=> array('user_id')
	)
    );
    $forum_db->create_table('sat_drafts', $schema);
}
