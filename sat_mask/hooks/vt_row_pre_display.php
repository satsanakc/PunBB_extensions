<?php
unset($signature_cache[$cur_post['poster_id']]);

if ($forum_page['is_admmod'] == 1 || $cur_post['poster_id'] == $forum_user['id']) {
	$cur_post['form_action'] = forum_link('misc.php?action=setmask&amp;pid=$1', $cur_post['id']);
	$cur_post['token'] = '<input type="hidden" name="csrf_token" value="'.generate_form_token($cur_post['form_action']).'" />';

	$maskForm = '<form id="mask-form" method="post" action="'.$cur_post['form_action'].'" style="display: none;">'."\n".
		"\t\t\t\t\t\t\t".'<div class="hidden">'."\n".
		"\t\t\t\t\t\t\t\t".'<input type="hidden" name="form_sent" value="1">'."\n".
		"\t\t\t\t\t\t\t\t".$cur_post['token']."\n".
		"\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t".'<fieldset class="frm-group">'."\n".
		"\t\t\t\t\t\t\t\t".'<legend class="group-legend"><strong>'.$lang_sat_mask['legend'].'</strong></legend>'."\n".
		"\t\t\t\t\t\t\t\t".'<div class="sf-set">'."\n".
		"\t\t\t\t\t\t\t\t\t".'<div class="sf-box text">'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<label for="maskname"><span>'.$lang_sat_mask['name'].'</span> <small>'.$lang_sat_mask['namesmall'].'</small></label><br>'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<span class="fld-input"><input type="text" id="maskname" name="maskname" value="'.$cur_post['mask_name'].'" size="35" maxlength="25"></span>'."\n".
		"\t\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'<div class="sf-set">'."\n".
		"\t\t\t\t\t\t\t\t\t".'<div class="sf-box text">'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<label for="masktitle"><span>'.$lang_sat_mask['title'].'</span> <small>'.$lang_sat_mask['titlesmall'].'</small></label><br>'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<span class="fld-input"><input type="text" id="masktitle" name="masktitle" value="'.$cur_post['mask_title'].'" size="35" maxlength="255"></span>'."\n".
		"\t\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'<div class="sf-set">'."\n".
		"\t\t\t\t\t\t\t\t\t".'<div class="sf-box text">'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<label for="maskava"><span>'.$lang_sat_mask['ava'].'</span> <small>'.$lang_sat_mask['avasmall'].'</small></label><br>'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<span class="fld-input"><input type="text" id="maskava" name="maskava" value="'.$cur_post['mask_ava'].'" size="35" maxlength="255"></span>'."\n".
		"\t\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'<div class="txt-set">'."\n".
		"\t\t\t\t\t\t\t\t\t".'<div class="txt-box textarea">'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<label for="masksig"><span>'.$lang_sat_mask['sig'].'</span> <small>'.$lang_sat_mask['sigsmall'].'</small></label>'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'<div class="txt-input">'."\n".
		"\t\t\t\t\t\t\t\t\t\t\t".'<span class="fld-input"><textarea id="masksig" name="masksig" rows="4" cols="65">'.$cur_post['mask_sig'].'</textarea></span>'."\n".
		"\t\t\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t\t".'</fieldset>'."\n".
		"\t\t\t\t\t\t\t".'<div class="frm-buttons">'."\n".
		"\t\t\t\t\t\t\t\t".'<span class="submit primary"><input type="submit" name="submit" value="'.$lang_sat_mask['submit'].'"></span>'."\n".
		"\t\t\t\t\t\t\t\t".'<span class="submit"><input type="submit" name="remove" value="'.$lang_sat_mask['remove'].'"></span>'."\n".
		"\t\t\t\t\t\t\t\t".'<span class="submit"><input type="submit" name="cancel" value="'.$lang_sat_mask['cancel'].'"></span>'."\n".
		"\t\t\t\t\t\t\t\t".'<p class="posting" style="display: none;"><a class="try" href="javascript://"><span>'.$lang_sat_mask['try'].'</span></a><a class="gallery" href="javascript://" style="display: none;"><span>'.$lang_sat_mask['gallery'].'</span></a></p>'."\n".
		"\t\t\t\t\t\t\t".'</div>'."\n".
		"\t\t\t\t\t\t".'</form>';

	$forum_page['message'] = array('mask' => $maskForm) + $forum_page['message'];
}