<?php
if(!defined('SAT_DICE_INSTALL')) die();

if (!$forum_db->table_exists('sat_dice')) {
    $schema = array(
        'FIELDS'      => array(
            'id'		=> array(
		'datatype'	=> 'SERIAL',
		'allow_null'	=> false
	    ),
            'thrown'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'user_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'post_id'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => true
            ),
            'num'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'faces'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'dice_mod'         	=> array(
                'datatype'        => 'INT(2)',
                'allow_null'      => true
            ),
            'sum_mod'         	=> array(
                'datatype'        => 'INT(10)',
                'allow_null'      => true
            ),
            'diff'         	=> array(
                'datatype'        => 'INT(10)',
                'allow_null'      => true
            ),
            'min'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'max'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'sum'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'descr'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => true
            ),
            'res'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => false
            )
        ),
	'PRIMARY KEY'	=> array('id'),
	'INDEXES'	=> array(
			'time_idx'		=> array('thrown'),
			'uid_idx'		=> array('user_id'),
			'pid_idx'		=> array('post_id')
	)
    );
    $forum_db->create_table('sat_dice', $schema);
}

if (!$forum_db->table_exists('sat_dice_templ')) {
    $schema = array(
        'FIELDS'      => array(
            'id'		=> array(
		'datatype'	=> 'SERIAL',
		'allow_null'	=> false
            ),
            'name'         	=> array(
                'datatype'        => 'VARCHAR(100)',
                'allow_null'      => false
            ),
            'num'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => true
            ),
            'faces'         	=> array(
                'datatype'        => 'INT(10) UNSIGNED',
                'allow_null'      => false
            ),
            'dice_mod'         	=> array(
                'datatype'        => 'INT(2)',
                'allow_null'      => true
            ),
            'min'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'max'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'sum'         	=> array(
                'datatype'        => 'TINYINT(1)',
                'allow_null'      => true
            ),
            'descr'       => array(
                'datatype'        => 'TEXT',
                'allow_null'      => true
            )
        ),
	'PRIMARY KEY'	=> array('id')
    );
    $forum_db->create_table('sat_dice_templ', $schema);
}

$diceTempl = array(
	array(
		'name'	=> '0/1',
		'faces'	=> '2',
		'dice_mod' => '-1',
		'min' => '0',
		'max' => '0',
		'sum' => '0'
	),
	array(
		'name'	=> 'd2',
		'faces'	=> '2'
	),
	array(
		'name'	=> 'd4',
		'faces'	=> '4'
	),
	array(
		'name'	=> 'd6',
		'faces'	=> '6'
	),
	array(
		'name'	=> 'd8',
		'faces'	=> '8'
	),
	array(
		'name'	=> 'd10',
		'faces'	=> '10'
	),
	array(
		'name'	=> 'd12',
		'faces'	=> '12'
	),
	array(
		'name'	=> 'd20',
		'faces'	=> '20'
	),
	array(
		'name'	=> 'd100',
		'faces'	=> '100'
	),
	array(
		'name'	=> 'fate',
		'faces'	=> '3',
		'dice_mod' => '-2'
	)
);

$i = 0;
foreach ($diceTempl as $val) {
	$i++;
	$schema = array(
		'INSERT'	=> 'id, name, faces',
		'INTO'		=> 'sat_dice_templ',
		'VALUES'	=> strval($i).", '".$val['name']."', ".$val['faces']
	);
	if(!empty($val['dice_mod'])) {
		$schema['INSERT'] .= ', dice_mod';
		$schema['VALUES'] .= ', '.$val['dice_mod'];
	}
	if(isset($val['min'])) {
		$schema['INSERT'] .= ', min';
		$schema['VALUES'] .= ', '.$val['min'];
	}
	if(isset($val['max'])) {
		$schema['INSERT'] .= ', max';
		$schema['VALUES'] .= ', '.$val['max'];
	}
	if(isset($val['sum'])) {
		$schema['INSERT'] .= ', sum';
		$schema['VALUES'] .= ', '.$val['sum'];
	}
	$forum_db->query_build($schema);
}
