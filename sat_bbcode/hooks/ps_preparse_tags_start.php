<?php if (!defined('FORUM')) die();

global $sat_bbcodes;

foreach ($sat_bbcodes as $key => $val) {
	if(isset($val['pattern']) && isset($val['replace'])) {
		if(!isset($val['endtag']) || ($val['endtag']) == true)
			$tags[] = $tags_opened[] = $tags_closed[] = $key;
		if(isset($val['nesting']) && is_int($val['nesting']))
			$tags_nested[$key] = $val['nesting'];
		if(!empty($val['ignore'])) $tags_ignore[] = $key;
		if(!empty($val['block'])) $tags_block[] = $key;
		if(!empty($val['inline'])) $tags_inline[] = $key;
		if(!empty($val['fix'])) $tags_fix[] = $key;
		if(!empty($val['trim'])) $tags_trim[] = $key;
		if(!empty($val['del_quotes'])) $tags_quotes[] = $key;
		if(!empty($val['use_in_link']))
			$tags_limit_bbcode['*'][] = $tags_limit_bbcode['url'][] = $tags_limit_bbcode['email'][] = $key;
	}
}
