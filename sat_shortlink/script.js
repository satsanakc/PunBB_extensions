function insertShortUrl(ulmsg, unmsg, ured, err) {
  fld = $('textarea[name="req_message"]')[0];
  let url = prompt(ulmsg, 'https://');
  if (url === null) {
    fld.focus();
    return false;
  }
  url = url.trim();
  if(confirm(ured)) {
    $.ajax({
        url: '/misc.php',
        type: 'GET',
        cache : false,
        async: false,
        data: {
          action: 'gentoken',
          act: 'createlink'
        },
        success: function(r) {
          $.ajax({
            url: '/misc.php?action=createlink',
            type: 'POST',
            async: false,
            data: {csrf_token: r.token, url: url},
            success: function(r) {
              if (r.code == -3) {
                alert(err);
console.log(r);
              } else {
                url = location.origin + '/?link=' + r;
              }
            },
            error: function(r) {
              alert(err);
console.log(r);
            }
          });
        },
        error: function(r) {
console.log(r);
        }
    });
  }
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
