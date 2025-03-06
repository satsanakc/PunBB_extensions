function setindent() {
  let arg1 = $('input[name="indentwidth"]').val();
  let arg2 = $('input[name="indentheight"]').val();
  arg1 = arg1 == '' ? '0' : arg1;
  arg2 = arg2 == '' ? '0' : arg2;
  let arg = (arg1 == '1.5' && arg2 == '1.2') ? '' : arg2 == '0' ? '=' + arg1 : '=' + arg1 + ',' + arg2;
  PUNBB.pun_bbcode.insert_text('[indent' + arg + ']', '');
  $('#bbcode_indent').toggle();
}

function showindent() {
  $('.testindent').css('width', +$('input[name="indentwidth"]')[0].value + 'em');
  $('.testindent').css('height', +$('input[name="indentheight"]')[0].value + 'em');
}

function valnum(a, param = false) {
  let res = a[0].value.replace(/[^\d,.-]/g, '')
    .replace(/,/g, '.')
    .replace(/(\d*\.\d*)\./g, '$1')
    .replace(/^0(\d+)/, '$1');
  res = (param == 'positive' || param == 'positiveint') ? res.replace(/-/g, '') : res.replace(/(.+)-/, '$1');
  res = (param == 'int' || param == 'positiveint') ? res.replace(/\./g, '') : res;
  a[0].value = res;
}

function changenum(a, add = 1, param = false) {
  a = 'input[name=' + a + ']';
  a = $(a);
  let res = parseFloat(a[0].value == '' ? '0' : a[0].value) + +add;
  if(param == 'positive' && res < 0) res = 0;
  a[0].value = res.toFixed(1).replace(/0$/, '').replace(/\.$/g, '');
}

var changenumTimer;

function changeradio(name, val) {
  let sel = 'input[name="' + name + '"][value="' + val + '"]';
  $(sel).trigger('click');
}

function setlist() {
  let arg1 = $('input[name="listview"]:checked').val();
  let arg2 = $('input[name="numitems"]').val();
  arg2 = arg2 == '' ? 0 : +arg2;
  let l = '[list=';
  let r = '';
  l += arg1 == 'a' ? 'a]\n' : (arg1 == '1' ? '1]\n' : '*]\n');
  l += '[*]';
  if (arg2 > 1) {
    for (i=1; i<arg2; i++) {
      r += '[/*]\n[*]';
    }
  }
  r += '[/*]\n[/list]';
  PUNBB.pun_bbcode.insert_text(l, r);
  $('#bbcode_list').toggle();
}

function insertUrl(ulmsg, unmsg) {
  fld = $('textarea[name="req_message"]')[0];
  let url = prompt (ulmsg, 'https://');
  if (url === null) {
    fld.focus();
    return false;
  }
  url = url.trim();
  if (fld.selectionStart == fld.selectionEnd) {
    let name = prompt (unmsg, '');
    if (name === null) {
      fld.focus();
      return false;
    }
    name = name.trim();
    if (name == '' && url == '') PUNBB.pun_bbcode.insert_text('[url]', '[/url]');
    else if (name == '') PUNBB.pun_bbcode.insert_text('[url]' + url + '[/url]', '');
    else if (url == '') PUNBB.pun_bbcode.insert_text('[url=]', name + '[/url]');
    else PUNBB.pun_bbcode.insert_text('[url=' + url + ']' + name + '[/url]', '');
  } else {
    if (url == '') PUNBB.pun_bbcode.insert_text('[url=]', '[/url]');
    else PUNBB.pun_bbcode.insert_text('[url=' + url + ']', '[/url]');
  }
}

function insertHide(msg) {
  let num = prompt (msg, '');
  if (num === null) {
    $('textarea[name="req_message"]')[0].focus();
    return false;
  }
  num = parseInt(num);
  num > 0 ? PUNBB.pun_bbcode.insert_text('[hide=' + num + ']', '[/hide]\n'): PUNBB.pun_bbcode.insert_text('[hide]', '[/hide]\n');
}

function insertSpoiler(msg) {
  let spttl = prompt (msg, '');
  if (spttl === null) {
    $('textarea[name="req_message"]')[0].focus();
    return false;
  }
  spttl = spttl.trim();
  spttl == '' ? PUNBB.pun_bbcode.insert_text('[spoiler]', '[/spoiler]\n') : PUNBB.pun_bbcode.insert_text('[spoiler=' + spttl + ']', '[/spoiler]\n');
}

function insertAbbr(msg) {
  let abbr = prompt (msg, '');
  if (abbr === null) {
    $('textarea[name="req_message"]')[0].focus();
    return false;
  }
  abbr = abbr.trim();
  PUNBB.pun_bbcode.insert_text('[abbr=' + abbr + ']', '[/abbr]');
}

function smiletabs(a) {
  if (!a.hasClass('active')) {
    $('#bbcode_smile .tabs li, #bbcode_smile .bbcont > div').removeClass('active');
    $('#bbcode_smile .' + a.attr('class')).addClass('active');
  }
}

function CCopping(o) {
  navigator.clipboard.writeText($(o).parent().find('pre').text());
}

$('html').on('click', function(e) {
  if ((!$(e.target).attr('id') || !~$(e.target).attr('id').indexOf('bbcode_')) && !$(e.target).parents('[id^="bbcode_"]').length) $('[id^="bbcode_"]').hide();
});

function showhideabbr(e) {
  if (!$(e.target).hasClass('showttl')) {
    $('.showttl').remove();
    if (e.target.localName == 'abbr' && $(e.target).attr('title')) {
      $(e.target).append('<div class="showttl">'+$(e.target).attr('title')+'</div>');
    }
  }
}
$('html').on('click', showhideabbr);

function testlink(e) {
  let link = e.target.href;
  if (~link.indexOf(location.origin)) {
    return true;
  } else {
    let conf = confirm(PUNBB.urlconf);
    if (conf) window.open(link);
  }
  return false;
}
$('a[rel="nofollow"]').on('click', testlink);
