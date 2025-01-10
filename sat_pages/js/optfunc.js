function hideform (b) {
  $(b).parent().find('.sat-content').contents().remove();
}

function showform (o) {
  $('#sat-pages-temp .sat-content').load(o.href + ' .main-subhead, .main-content');
}


$('.edit').add('.delete').click(function(event){
  showform(event.target);
  return false;
});


$('.close').click(function(event){
  hideform(event.target);
});