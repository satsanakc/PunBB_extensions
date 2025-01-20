<?php
if(!defined('SAT_ENI_PROFILE_INSTALL')) die();

$fractions = array(
	array(
		'id'	=> 1,
		'name'	=> 'Альтанар',
		'desc'	=> ''
	),
	array(
		'id'	=> 2,
		'name'	=> 'Архейн',
		'desc'	=> ''
	),
	array(
		'id'	=> 3,
		'name'	=> 'Аскардиана',
		'desc'	=> ''
	),
	array(
		'id'	=> 4,
		'name'	=> 'Айсгардика',
		'desc'	=> ''
	),
	array(
		'id'	=> 5,
		'name'	=> 'Вермилон',
		'desc'	=> ''
	),
	array(
		'id'	=> 6,
		'name'	=> 'Гурубаши',
		'desc'	=> ''
	),
	array(
		'id'	=> 7,
		'name'	=> 'Джер',
		'desc'	=> ''
	),
	array(
		'id'	=> 8,
		'name'	=> 'Орда',
		'desc'	=> ''
	),
	array(
		'id'	=> 9,
		'name'	=> 'Румиват',
		'desc'	=> ''
	),
	array(
		'id'	=> 10,
		'name'	=> 'Северный архипелаг',
		'desc'	=> ''
	),
	array(
		'id'	=> 11,
		'name'	=> 'Сельмирион',
		'desc'	=> ''
	),
	array(
		'id'	=> 12,
		'name'	=> 'Тарр-Друин и Тилгорин',
		'desc'	=> ''
	),
	array(
		'id'	=> 13,
		'name'	=> 'Харпанварг',
		'desc'	=> ''
	),
	array(
		'id'	=> 14,
		'name'	=> 'Хаст',
		'desc'	=> ''
	),
	array(
		'id'	=> 15,
		'name'	=> 'Эллендиар',
		'desc'	=> ''
	),
	array(
		'id'	=> 99,
		'name'	=> 'Другое',
		'desc'	=> ''
	)
);

if ($forum_db->table_exists('fractions')) {
	$schema = array(
		'DELETE'	=> 'fractions'
	);
	$forum_db->query_build($schema);
}

$i = 0;
foreach ($fractions as $val) {
	$i++;
	$str = strval($i).", '".$val['name']."', '".$val['desc']."'";
	$schema = array(
		'INSERT'	=> 'id, name, description',
		'INTO'		=> 'fractions',
		'VALUES'	=> $str		
	);
	$forum_db->query_build($schema);
}