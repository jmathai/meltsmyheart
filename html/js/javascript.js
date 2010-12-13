var mmh = (function() {
    return {
      clickHandlers: {
        album: function(e) {
          e.preventDefault();
          $("#preview").html("Loading...");
          $.get(this.href, function(response) {
              $("#preview").html(response);
            }, 'json');
        },
        photo: function(e) {
          var el = this;
          e.preventDefault();
          $.post(this.href, function(response) {
            $(el).before(response).remove();
          }, 'json');
        }
      }
    };
  }
)();
