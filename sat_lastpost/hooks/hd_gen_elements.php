<?php if (!defined('FORUM')) die();

if (file_exists($ext_info['path'].'/lang/'.$forum_user['language'].'.php'))
	require $ext_info['path'].'/lang/'.$forum_user['language'].'.php';
else
	require $ext_info['path'].'/lang/English.php';

$query = array(
	'SELECT'	=> 'p.id AS pid, p.poster, p.posted, p.poster_id AS uid, p.message, p.hide_smilies, p.topic_id AS tid, t.subject, t.forum_id AS fid, f.forum_name, u.avatar, u.signature AS sig',
	'FROM'		=> 'posts AS p',
	'JOINS'		=> array(
		array(
			'INNER JOIN'	=> 'users AS u',
			'ON'		=> 'u.id=p.poster_id'
		),
		array(
			'INNER JOIN'	=> 'topics AS t',
			'ON'		=> 't.id=p.topic_id'
		),
		array(
			'INNER JOIN'	=> 'forums AS f',
			'ON'		=> 'f.id=t.forum_id'
		),
		array(
			'LEFT JOIN'	=> 'forum_perms AS fp',
			'ON'		=> '(fp.forum_id=f.id AND fp.group_id='.$forum_user['g_id'].')'
		)
	),
	'WHERE'		=> '(fp.read_forum IS NULL OR fp.read_forum=1) AND p.posted<='.time(),
	'ORDER BY'	=> 'p.posted DESC',
	'LIMIT'		=> '1'
);
($hook = get_hook('sat_lastpost_query_end')) ? eval($hook) : null;
$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
$lastpost = $forum_db->fetch_assoc($result);

if (!empty($lastpost)) {
	if (!defined('FORUM_PARSER_LOADED'))
		require FORUM_ROOT.'include/parser.php';

	switch ($lastpost['avatar'])
	{
		case FORUM_AVATAR_GIF:
			$lastpost['avatar'] = FORUM_ROOT.$forum_config['o_avatars_dir'].'/'.$lastpost['uid'].'.gif';
			break;

		case FORUM_AVATAR_JPG:
			$lastpost['avatar'] = FORUM_ROOT.$forum_config['o_avatars_dir'].'/'.$lastpost['uid'].'.jpg';
			break;

		case FORUM_AVATAR_PNG:
			$lastpost['avatar'] = FORUM_ROOT.$forum_config['o_avatars_dir'].'/'.$lastpost['uid'].'.png';
			break;

		case FORUM_AVATAR_NONE:
			if(isset($forum_config['o_defava']))
				$lastpost['avatar'] = $forum_config['o_defava'];
			break;

		default:
			break;
	}
	$lastpost['message'] = parse_message(forum_trim($lastpost['message']), $lastpost['hide_smilies']);
	$lastpost['sig'] = parse_signature(forum_trim($lastpost['sig']));

	$gen_elements['<!-- forum_announcement -->'] .= '<div id="brd-lastpost" class="gen-content">'."\n\t".'<h2 class="hn"><span>'.$lang_sat_lastpost['heading'].'</span></h2>'."\n\t".($lastpost['avatar'] ? '<em class="user-avatar"><a href="/profile.php?id='.$lastpost['uid'].'"><span class="avatar-image" title="'.$lastpost['poster'].'" style="background-image:url('.$lastpost['avatar'].')"></span></a></em>'."\n\t" : '').'<div class="content">'."\n\t\t".'<h3><strong><a href="/viewtopic.php?pid='.$lastpost['pid'].'#p'.$lastpost['pid'].'">'.$lastpost['subject'].'</a></strong> <span class="datetime">'.format_time($lastpost['posted']).'</span> <cite>'.($lastpost['avatar'] ? $lastpost['poster'] : '<a href="/profile.php?id='.$lastpost['uid'].'">'.$lastpost['poster'].'</a>').'</cite> <small>('.$lang_sat_lastpost['inforum'].' <a href="/viewforum.php?id='.$lastpost['fid'].'">'.$lastpost['forum_name'].'</a>)</small></h3>'."\n\t\t".'<div class="entry-content">'.$lastpost['message'].($lastpost['sig'] ? '<div class="sig-content"><span class="sig-line"><!-- --></span> '.$lastpost['sig'].'</div>' : '').'</div>'."\n\t".'</div>'."\n".'</div>';
}