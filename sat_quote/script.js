function safeJsonParse(str) {
  try {
    return [null, JSON.parse(str)];
  } catch (err) {
    return [err];
  }
}

function satQuote(e) {
  $.get($(this).attr('href') + '&satquote=1', function(res) {
    res = safeJsonParse(res);
    if(res[0]) {
      console.log('Failed to parse JSON: ' + res[0].message);
    } else if(res[1].error) {
      console.log('The request failed(' + error.code + '): ' + error.message);
    } else if(res[1].response) {
      res = res[1].response;
      PUNBB.pun_bbcode.insert_text(res.quote, '');
    } else {
      console.log('Unknown error.');
    }
  });
  e.preventDefault();
}

$('.quote-post a').on('click', satQuote);