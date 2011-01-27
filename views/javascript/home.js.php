// initialize tooltip
$("ul.home-photos li img[title]").tooltip({
   // tweak the position
   offset: [10, 2],
   // use the "slide" effect
   effect: 'slide'
// add dynamic plugin with optional configuration for bottom edge
}).dynamic({ bottom: { direction: 'down', bounce: true } });
$("a.child-delete").click(function(e) {
  e.preventDefault();
  var el = this;
  $.post(el.href, {}, function(response) {
    if(mmh.ajax.isSuccess(response)) {
      $("#child-"+response.params.childId).animate({opacity:'toggle',height:'toggle'}, 1000); 
      mmh.displayConfirm(response.message);  
    } else {
      mmh.displayError(response.message);
    }
  }, 'json');
});
