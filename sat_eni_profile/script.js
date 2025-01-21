function racechange(a) {
  if ($(a).attr('name') == 'ch_race_id') {
    $('select[name="form[ch_race_id]"] option:not(:first-of-type)').addClass('hidden');
    $('select[name="form[ch_race_id]"] option.gr' + a.value).removeClass('hidden');
    $('select[name="form[ch_race_id]"]')[0].value = '';
  }
  $('.racedesc').hide();
  if (!a.value)
    $('.racedesc.item' + $('[name="ch_race_id"]')[0].value).show();
  else
    $('.racedesc.item' + a.value).show();
}

function fractchange(a) {
  $('.fractdesc').hide();
  $('.fractdesc.item' + a.value).show();
}