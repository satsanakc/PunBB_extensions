<?php

if(!defined('SAT_ENI_PROFILE_INSTALL')) die();

if (!$forum_db->field_exists('users', 'ch_name'))
    $forum_db->add_field('users', 'ch_name', 'VARCHAR(255)', true);

if (!$forum_db->field_exists('users', 'ch_race_id'))
    $forum_db->add_field('users', 'ch_race_id', 'INT(10) UNSIGNED', true);

if (!$forum_db->field_exists('users', 'ch_gender'))
    $forum_db->add_field('users', 'ch_gender', 'TINYINT(1)', false, 0);

if (!$forum_db->field_exists('users', 'ch_birth'))
    $forum_db->add_field('users', 'ch_birth', 'BIGINT(12)', false, -62167219200);

if (!$forum_db->field_exists('users', 'ch_metier'))
    $forum_db->add_field('users', 'ch_metier', 'VARCHAR(255)', true);

if (!$forum_db->field_exists('users', 'ch_skills'))
    $forum_db->add_field('users', 'ch_skills', 'TEXT', true);

if (!$forum_db->field_exists('users', 'ch_person'))
    $forum_db->add_field('users', 'ch_person', 'TEXT', true);

if (!$forum_db->field_exists('users', 'ch_bio'))
    $forum_db->add_field('users', 'ch_bio', 'TEXT', true);

if (!$forum_db->field_exists('users', 'ch_extra'))
    $forum_db->add_field('users', 'ch_extra', 'TEXT', true);

if (!$forum_db->field_exists('users', 'showdesc'))
    $forum_db->add_field('users', 'showdesc', 'TINYINT(1)', false, 1);

if (!$forum_db->table_exists('races')) {
    $schema = array(
        'FIELDS'      => array(
            'id'            	=> array(
                'datatype'        => 'SERIAL',
                'allow_null'      => false
            ),
            'name'          	=> array(
                'datatype'        => 'VARCHAR(100)',
                'allow_null'      => false,
                'default'         => '\'\''
            ),
            'basis'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => true
            ),
            'available'        	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => false,
                'default'         => 1
            ),
            'description'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => true
            )
        ),
        'PRIMARY KEY' => array('id')
    );
    $forum_db->create_table('races', $schema);
}

if (!$forum_db->table_exists('gods')) {
    $schema = array(
        'FIELDS'      => array(
            'id'            => array(
                'datatype'          => 'SERIAL',
                'allow_null'        => false
            ),
            'name'      => array(
                'datatype'          => 'VARCHAR(100)',
                'allow_null'        => false,
                'default'           => '\'\''
            ),
            'primacy'   => array(
                'datatype'          => 'TINYINT(1)',
                'allow_null'        => true
            ),
            'description' => array(
                'datatype'          => 'TEXT',
                'allow_null'        => true
            )
        ),
        'PRIMARY KEY' => array('id')
    );
    $forum_db->create_table('gods', $schema);
}

if (!$forum_db->table_exists('religion')) {
    $schema = array(
        'FIELDS'        => array(
            'user_id'          => array(
                'datatype'	=> 'INT(10) UNSIGNED',
                'allow_null'	=> false
            ),
            'god_id'           => array(
                'datatype'	=> 'INT(10) UNSIGNED',
                'allow_null'	=> false
            )
        ),
        'PRIMARY KEY'	=> array('user_id', 'god_id')
    );
    $forum_db->create_table('religion', $schema);
}

if (!$forum_db->table_exists('fraction')) {
    $schema = array(
        'FIELDS'      => array(
            'id'            	=> array(
                'datatype'          => 'SERIAL',
                'allow_null'        => false
            ),
            'name'        	=> array(
                'datatype'          => 'VARCHAR(100)',
                'allow_null'        => false,
                'default'        => '\'\''
            ),
            'basis'        	=> array(
                'datatype'          => 'TINYINT(1)',
                'allow_null'        => true
            ),
            'description'	=> array(
                'datatype'          => 'TEXT',
                'allow_null'        => true
            )
        ),
        'PRIMARY KEY' => array('id')
    );
    $forum_db->create_table('fraction', $schema);
}

if (!$forum_db->table_exists('char_log'))
{
	$schema = array(
		'FIELDS'		=> array(
			'id'			=> array(
				'datatype'		=> 'SERIAL',
				'allow_null'		=> false
			),
			'char_id'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'		=> true
			),
			'poster_id'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'		=> true
			),
			'posted'		=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'		=> false,
				'default'		=> '0'
			),
            		'name'     		=> array(
                		'datatype'       	=> 'VARCHAR(255)',
                		'allow_null'        	=> true
            		),
            		'race'			=> array(
				'datatype'		=> 'INT(10) UNSIGNED',
				'allow_null'		=> true
			),
			'birth'			=> array(
				'datatype'		=> 'BIGINT(10)',
				'allow_null'		=> false,
				'default'		=> '-62167219200'
			),
			'metier'     		=> array(
                		'datatype'       	=> 'VARCHAR(255)',
                		'allow_null'        	=> true
            		),
            		'skills'     		=> array(
                		'datatype'       	=> 'TEXT',
                		'allow_null'        	=> true
            		),
            		'person'     		=> array(
                		'datatype'       	=> 'TEXT',
                		'allow_null'        	=> true
            		),
            		'bio'     		=> array(
                		'datatype'       	=> 'TEXT',
                		'allow_null'        	=> true
            		),
            		'extra'     		=> array(
                		'datatype'       	=> 'TEXT',
                		'allow_null'        	=> true
            		)
		),
		'PRIMARY KEY'		=> array('id'),
		'INDEXES'		=> array(
			'char_id_idx'		=> array('char_id')
		)
	);
	$forum_db->create_table('char_log', $schema);
}

require $ext_info['path'].'/extra/gods.php';
require $ext_info['path'].'/extra/races.php';