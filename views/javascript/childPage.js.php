$("a.child-photo").click(function(e){
  location.href='<?php echo $_SERVER['REQUEST_URI']; ?>#/photo/'+$(this).attr('data-photo-id');
  e.preventDefault();
}).lightBox();
$("div#child").click(function(e){
  console.log(e.target);
  console.log(e.currentTarget);
});
$("button#child-page-edit").each(mmh.overlayWide);
