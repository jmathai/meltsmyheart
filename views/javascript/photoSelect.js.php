$("a.album").click(function(e) {
  e.preventDefault();
  $("#preview").html("Loading...");
  $.get(this.href, function(response) {
    var photos, html = '';
    photos = response.data;
    $(photos).each(function(i, el) {
      html += '<img src="'+el.picture+'" vspace="5" hspace="5">';
      if(i == (photos.length-1))
        $("#preview").html(html);
    });
  }, 'json');
});
