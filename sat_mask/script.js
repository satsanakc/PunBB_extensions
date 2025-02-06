function maskShowForm(o) {
  $(o).parents('.post').find('.entry-content > :not(.sig-content)').toggle();
  $('html, body').stop().animate({
    scrollTop: $(o).parents('.post').offset().top
  }, 500);
}
