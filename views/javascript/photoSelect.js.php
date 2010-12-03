$("a.album").click(function(e) {
  e.preventDefault();
  $("#preview").html("Loading...");
  $.get(this.href, function(response) {
    var html = '';
    $(response).each(function(i, el) {
      html += '<img src="'+el.thumbUrl+'" vspace="5" hspace="5">';
      if(i == (response.length-1))
        $("#preview").html(html);
    });
  }, 'json');
});
