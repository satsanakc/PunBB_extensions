function diceRoll() {
  let str = '[roll=';
  str += +$('#bbcode_dice [name="num"]').val() + ',';
  str += +$('#bbcode_dice [name="faces"]').val() + ',';
  str += +$('#bbcode_dice [name="afaces"]').val() + ',';
  str += +$('#bbcode_dice [name="mod"]').val() + ',';
  str += +$('#bbcode_dice [name="diff"]').val() + ',';
  str += +$('#bbcode_dice [name="min"]')[0].checked + ',';
  str += +$('#bbcode_dice [name="max"]')[0].checked + ',';
  str += +$('#bbcode_dice [name="sum"]')[0].checked + ',';
  str += $('#bbcode_dice [name="desc"]').val().replace('[', '&#91;').replace(']', '&#93;') + ']';
  PUNBB.pun_bbcode.insert_text(str, '');
  $('#bbcode_dice').hide();
}

$('#sat_dice_afaces_row').hide();
$('#bbcode_dice .extra').hide();
$('#bbcode_dice select[name="faces"]').on('change', function() {
  if(this.value)
    $('#sat_dice_afaces_row').hide();
  else
    $('#sat_dice_afaces_row').show();
});
