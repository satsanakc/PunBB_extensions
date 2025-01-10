function insertVideo() {
  let l = $('input[name="videolink"]').val();
  let m = l.match(/(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)([&?]t=)?(\d+)?/);
  if (m) {
    PUNBB.pun_bbcode.insert_text('[video]https://www.youtube.com/watch?v=' + m[4] + (m[6] ? '&t=' + m[6] + 's' : '') + '[/video]\n', '');
    $('input[name="videolink"]').val('');
    $('#bbcode_video').toggle();
  } else {
    m = l.match(/(https?:\/\/)?(rutube\.ru\/video\/|rutube\.ru\/play\/embed\/)([^\/]+)(?:[\s\S]*?t=(\d+))?/);
    if (m) {
      PUNBB.pun_bbcode.insert_text('[video]https://rutube.ru/video/' + m[3] + '/' + (m[4] ? '?t=' + m[4] : '') + '[/video]\n', '');
      $('input[name="videolink"]').val('');
      $('#bbcode_video').toggle();
    } else {
      alert('Error! Invalid link format.');
    }
  }
}