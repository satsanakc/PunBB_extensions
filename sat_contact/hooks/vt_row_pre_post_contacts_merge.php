<?php

					if (!empty($forum_page['post_contacts']['url']))
						$forum_page['post_contacts']['url'] = '<span class="user-url'.(empty($forum_page['post_contacts']) ? ' first-item' : '').'"><a target="_blank" class="external" href="'.forum_htmlencode(($forum_config['o_censoring'] == '1') ? censor_words($cur_post['url']) : $cur_post['url']).'">'.sprintf($lang_topic['Visit website'], '<span>'.sprintf($lang_topic['User possessive'], forum_htmlencode($cur_post['username'])).'</span>').'</a></span>';