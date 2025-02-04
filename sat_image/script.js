var gyazo = {
  app: 'sat_image_punbb_extension',
  client_id: '2rv69WyYYyRe7TGKkWXgu6hDaX3Sa99R3MWSJre8_40',
  access_token: 'faDfI4tKX59trYP8z7gDOZmQ4RG36DCMMD76Z-3d51M'
}

PUNBB.env.user_id = PUNBB.env.user_is_guest != 1 ? $('#navprofile a').attr('href').match(/id=(\d+)$/)[1] : '1';
PUNBB.env.user_is_moder = $('#navadmin').length;

function imgBut() {
  let fld = $('textarea[name="req_message"]')[0];
  if (fld.selectionStart != fld.selectionEnd)
    PUNBB.pun_bbcode.insert_text('[img]', '[/img]');
  else $('#bbcode_img').toggle();
}

function insertImage() {
  let i = {};
  i.link = $('#img-link').val().trim();
  i.sign = $('#img-title').val().trim();
  i.float = $('input[name="img-float"]:checked').val();
  i.w = parseInt($('#img-width').val());
  i.h = parseInt($('#img-height').val());

  if (!i.link.match(/^(http|ftp)(s?):\/\//)) alert('Error! Link not found.');
  else if (location.protocol == 'https:' && i.link.match(/^(http|ftp)(s?):\/\//)[2] != 's') alert('Error! Insecure protocol. Expected https:// or ftps://');
  else {
    let code = '[img' +
               (i.float != 'none' || i.sign || i.w || i.h ? '=' : '') +
               (i.float != 'none' ? 'float:' + i.float : '') +
               (i.w ? i.w + $('#pxpr').val().replace('px', '') : '') +
               (i.h ? 'x' + i.h : '') +
               (i.sign && ((i.float != 'none' || i.w || i.h) || (i.sign.match(/\d/) && i.sign.match(/\d/).index == 0)) ? ' ' : '') +
               i.sign + ']' + i.link + '[/img]';
    PUNBB.pun_bbcode.insert_text(code, '');
    $('#img-link').val('');
    $('#img-title').val('');
    $('#bbcode_img').toggle();
  }
}

function previewImage(i) {
    $('#img-preview').css('background-image', 'url(' + $(i).val() + ')');
}

async function uploadImage(i) {
  let imgdata = new FormData();
  imgdata.append('imagedata', $(i)[0].files[0]);
  imgdata.append('access_token', gyazo.access_token);
  imgdata.append('referer_url', location.origin);
  imgdata.append('app', gyazo.app);
  imgdata.append('title', $('#brd-title a').text());
  imgdata.append('desc', $('h1.main-title a').text());
  const response = await window.fetch('https://upload.gyazo.com/api/upload', {
    method: 'POST',
    body: imgdata
  });
  if (response.status >= 400) {
    alert(response.status + ': ' + response.statusText);
  } else {
    const gdata = await response.json();
    let data = new FormData();
    $.each(gdata, function(i, v) {
      data.append(i, v);
    });
    data.append('csrf_token', $('input[name="img_csrf_token"]').val());
    $.ajax({
      url: '/misc.php?action=satimgrec',
      type: 'POST',
      processData: false,
      contentType: false,
      cache : false,
      data: data,
      complete: function(r) {
console.log(r);
      }
    });
    previewImage($('#img-link').val(gdata.url));
  }
}


function postimgGallery () {
  $('body').append('<div id="lightbox">' +
           '<div id="lightflipp" onclick="if(lightimgtimer !== undefined) {clearTimeout(lightimgtimer); lightimgtimer = undefined;} else {postimgScroll(); lightimgtimer = setInterval(postimgScroll, lightimgtime);}"></div>' +
           '<div id="lightexit" onclick="clearTimeout(lightimgtimer);lightimgtimer = undefined;this.parentNode.remove()"></div>' +
           '<div id="lightleft" onclick="postimgScroll(\'prev\')"></div>' +
           '<div id="lightimg"></div>' +
           '<div id="lightright" onclick="postimgScroll(\'next\')"></div>');
  $('#lightimg').append('<span class="postimg withsign"><img src="' + this.src + '"><em></em></span>');
  curlightimg = $(this).parent();
  lightimgs = curlightimg.parents('.entry-content').find('.postimg');
  if (curlightimg.find('em').length) $('#lightimg em').text(curlightimg.find('em').text());
  if(lightimgauto==1) lightimgtimer = setInterval(postimgScroll, lightimgtime);
}

function postimgScroll(way) {
  let i = lightimgs.index(curlightimg);
  if (way == 'prev') {
    curlightimg = i > 0 ? lightimgs.eq(i - 1) : lightimgs.last();
  } else {
    curlightimg = i < lightimgs.length - 1 ? lightimgs.eq(i + 1) : lightimgs.first();
  }
  $('#lightimg img').attr('src', curlightimg.find('img').attr('src'));
  $('#lightimg em').text(curlightimg.find('em').text());
}

var lightimgtime = 4000; // Время автоперелистывания в мс, 1с = 1000мс
var lightimgauto = 1; // 1 - автоперелистывание включается само
var curlightimg, lightimgs, lightimgtimer = undefined;
$('.postimg img').on('click', postimgGallery);
