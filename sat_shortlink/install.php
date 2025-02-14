<?php
if(!defined('SAT_SHORTLINK_INSTALL')) die();

if (!$forum_db->table_exists('sat_shortlinks')) {
    $schema = array(
        'FIELDS'      => array(
            'id'		=> array(
		'datatype'	=> 'SERIAL',
		'allow_null'	=> false
            ),
            'generated'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'user_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'url'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => false
            ),
            'link'       => array(
                'datatype'        => 'CHAR(6)',
                'allow_null'      => false
            )
        ),
	'PRIMARY KEY'	=> array('id'),
	'INDEXES'	=> array(
			'link_idx'		=> array('link')
	)
    );
    $forum_db->create_table('sat_shortlinks', $schema);
}
