<?php
if (!defined('FORUM')) die();

if(isset($_GET['satquote'])) {
	$query = array(
		'SELECT'	=> 'p.poster_id, p.posted, p.message, u.username AS poster, u.avatar',
		'FROM'		=> 'posts AS p',
		'WHERE'		=> 'p.id='.$qid.' AND topic_id='.$tid,
		'JOINS'		=> array(
			array(
				'INNER JOIN'	=> 'users AS u',
				'ON'		=> 'u.id=p.poster_id'
			)
		)
	);

	$result = $forum_db->query_build($query) or error(__FILE__, __LINE__);
	$quote_info = $forum_db->fetch_assoc($result);

	if (!$quote_info)
	{
		echo '{"error":{"code":"400","message":"'.$lang_common['Bad request'].'"}}';
		exit;
	}

	($hook = get_hook('po_modify_quote_info')) ? eval($hook) : null;

	if ($forum_config['p_message_bbcode'] == '1')
	{
		// If username contains a square bracket, we add "" or '' around it (so we know when it starts and ends)
		if (strpos($quote_info['poster'], '[') !== false || strpos($quote_info['poster'], ']') !== false)
		{
			if (strpos($quote_info['poster'], '\'') !== false)
				$quote_info['poster'] = '"'.$quote_info['poster'].'"';
			else
				$quote_info['poster'] = '\''.$quote_info['poster'].'\'';
		}
		else
		{
			// Get the characters at the start and end of $q_poster
			$ends = utf8_substr($quote_info['poster'], 0, 1).utf8_substr($quote_info['poster'], -1, 1);

			// Deal with quoting "Username" or 'Username' (becomes '"Username"' or "'Username'")
			if ($ends == '\'\'')
				$quote_info['poster'] = '"'.$quote_info['poster'].'"';
			else if ($ends == '""')
				$quote_info['poster'] = '\''.$quote_info['poster'].'\'';
		}
		$forum_page['quote'] = '[quote='.$quote_info['poster'].' post_id='.$qid.' time='.$quote_info['posted'].' user_id='.$quote_info['poster_id'].']'.$quote_info['message'].'[/quote]'."\n";
	}
	else
		$forum_page['quote'] = '> '.$quote_info['poster'].' '.$lang_common['wrote'].':'."\n\n".'> '.$quote_info['message']."\n";

$forum_page['quote'] = preg_replace('/"/', '\"', $forum_page['quote']);
$forum_page['quote'] = preg_replace('/\n/', '\\n', $forum_page['quote']);
	echo '{"response":{"qid":"'.$qid.'","uid":"'.$quote_info['poster_id'].'","poster":"'.preg_replace('/"/', '\"', $quote_info['poster']).'","posted":"'.$quote_info['posted'].'","avatar":"'.$quote_info['avatar'].'","quote":"'.$forum_page['quote'].'"}}';
	exit;
}