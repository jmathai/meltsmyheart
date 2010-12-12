var __ids = <?php echo json_encode($ids); ?>;
$("a.album").click(function(e) {
  e.preventDefault();
  $("#preview").html("Loading...");
  $.get(this.href, function(response) {
    var html = '<ul>';
    $(response).each(function(i, el) {
console.log(el.p_id);
      if(__ids[el.p_id])
        html += '<?php echo str_replace("\n", "", getTemplate()->get('partials/photoSelectItem.php', array('service' => $service, 'childId' => $childId, 'included' => true))); ?>';
      else
        html += '<?php echo str_replace("\n", "", getTemplate()->get('partials/photoSelectItem.php', array('service' => $service, 'childId' => $childId, 'included' => false))); ?>';

      if(i == (response.length-1))
      {
        $("#preview").html(html+'</ul>');
        $("a.photo-select-item").click(function(e) {
          e.preventDefault();
          $.get(this.href, function(response) {
            alert(response.toSource());
          }, 'json');
        });
      }
    });
  }, 'json');
});
