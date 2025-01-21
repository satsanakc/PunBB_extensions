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

function showSection(s) {
  $('.data-list li:not(#display-profile), .profile > div:not(.set1)').addClass('hidden');
  $('.'+s).removeClass('hidden');
  $('#display-profile strong').removeClass('warn');
  $('#display-profile strong[data-section="'+s+'"]').addClass('warn');
  setCookie('profsect', s, 365);
}

$('.data-list li:not(#display-profile):not([class]), .profile > div:not(.set1)[class^="ct-set"]').addClass('user');
showSection(!getCookie('profsect') ? $('#display-profile strong.warn')[0].dataset.section : getCookie('profsect'));

$('#display-profile strong').on('click', function(e) {
  showSection(e.target.dataset.section);
});
