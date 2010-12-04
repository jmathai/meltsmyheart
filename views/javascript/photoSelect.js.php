$("a.album").click(function(e) {
  e.preventDefault();
  $("#preview").html("Loading...");
  $.get(this.href, function(response) {
    var html = '<ul>';
    $(response).each(function(i, el) {
      if(true)
        html += '<?php echo str_replace("\n", "", getTemplate()->get('photosSelectItem.php', array('childId' => $childId, 'included' => false))); ?>';
      else
        html += '<?php echo str_replace("\n", "", getTemplate()->get('photosSelectItem.php', array('childId' => $childId, 'included' => true))); ?>';
      if(i == (response.length-1))
        $("#preview").html(html+'</ul>');
    });
  }, 'json');
});
