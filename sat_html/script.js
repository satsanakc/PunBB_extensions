function insertFrame() {
  let w = $('input#framewidth').val() ? parseInt($('input#framewidth').val()) : 640;
  let h = $('input#frameheight').val() ? parseInt($('input#frameheight').val()) : 360;
  let r = $('input#frameres')[0].checked ? 'r' : '';
  PUNBB.pun_bbcode.insert_text('[frame=' + w + 'x' + h + r + ']' + $('input#framelink').val() + '[/frame]\n', '');
  $('input#framelink').val('');
  $('#bbcode_frame').toggle();
}

let stsh = $('head link[rel="stylesheet"]');
let pstyle = [];
for (i=0; i<stsh.length; i++) pstyle.push(stsh[i].href);
var HTMLfData = {
  font: $('.entry-content').css('font'),
  style: pstyle
};

function sendHTMLdata(o) {
console.log($(o));
  html_frame_request = {
    font: HTMLfData.font,
    style: HTMLfData.style,
    content: o.innerHTML
  };
  let id = o.name;
  window.frames[id].postMessage({
    html_frame_response: html_frame_request
  }, location.origin);
}

window[window.addEventListener ? 'addEventListener' : 'attachEvent']('message', function(e) {
	if (e.origin !== location.origin) return;
	if (e.data && e.data.html_frame) {
		var framData = e.data.html_frame;
		var id = framData.id,
			event = framData.event;
		if (event == 'resize') {
			$('#' + id).css('height', framData.height + 'px').attr('height', framData.height)
		};
		if (event == 'load') {
			sendHTMLdata($('#' + id)[0]);
		}
	}
});

var htmlFrames = $('.html_frame');
for (i=0; i<htmlFrames.length; i++) {
  if (htmlFrames[i].innerHTML && htmlFrames[i].contentDocument.readyState == 'complete') {
    sendHTMLdata(htmlFrames[i]);
  }
}