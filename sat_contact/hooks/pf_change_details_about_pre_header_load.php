<?php
	if ($user['url'] != '') {
			$forum_page['url'] = '<a href="'.$url_source.'" target="_blank" class="external url" rel="me">'.$user['url'].'</a>';
			$forum_page['user_contact']['website'] = '<li><span>'.$lang_profile['Website'].': '.$forum_page['url'].'</span></li>';
	}
	if ($user['skype'] !='')
			$forum_page['user_contact']['skype'] = '<li><span>'.$lang_profile['Skype'].': <a href="skype:'.$user['skype'].'" class="skype">'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words($user['skype']) : $user['skype']).'</a></span></li>';
	if ($user['discord'] !='')
			$forum_page['user_contact']['discord'] = '<li><span>'.$lang_sat_contact['discord'].': <strong>'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words($user['discord']) : $user['discord']).'</strong></span></li>';
	if ($user['telegram'] !='') {
		if (strpos(strtolower($user['telegram']), 't.me') !== false)
					$user['telegram'] = preg_replace('#[\s\S]*?t\.me/([\s\S]+)#', '$1', $user['telegram']);
				if (strpos(strtolower($user['telegram']), '@') === 0)
					$user['telegram'] = preg_replace('#@([\s\S]+)#', '$1', $user['telegram']);
		$forum_page['user_contact']['telegram'] = '<li><span>'.$lang_sat_contact['telegram'].': <a href="https://t.me/'.$user['telegram'].'" target="_blank" class="telegram">@'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words($user['telegram']) : $user['telegram']).'</a></span></li>';
	}
	if ($user['vk'] !='') {
			if (strpos(strtolower($user['vk']), 'vk.com') !== false)
					$user['vk'] = preg_replace('#[\s\S]*?vk\.com/([\s\S]+)#', '$1', $user['vk']);
			$forum_page['user_contact']['vk'] = '<li><span>'.$lang_sat_contact['vk'].': <a href="https://vk.com/'.$user['vk'].'" target="_blank" class="external url">'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words($user['vk']) : $user['vk']).'</a></span></li>';
	}
	if ($user['ok'] !='') {
			if (strpos(strtolower($user['ok']), 'ok.ru') === false)
				$user['ok'] = 'https://ok.ru/profile/'.$user['ok'];
			$forum_page['user_contact']['ok'] = '<li><span>'.$lang_sat_contact['ok'].': <a href="'.$user['ok'].'" target="_blank" class="external url">'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words(preg_replace('#[\s\S]+/([\s\S]+)#', '$1', $user['ok'])) : preg_replace('#[\s\S]+/([\s\S]+)#', '$1', $user['ok'])).'</a></span></li>';
	}