var mmh = (function() {
    return {
      ajax: {
        isSuccess: function(response) {
          return response.status >= 200 && response.status <= 299;
        },
        error: function(response) {
          var message = response.message || "There was a problem processing your request.";
          alert(message);
        },
        success: function(response) {
          var message = response.message || "Your request was processed successfully.";
          alert(message);
        }
      },
      clickHandlers: {
        album: function(e) {
          e.preventDefault();
          $("#preview").html("Loading...");
          $.get(this.href, function(response) {
              if(mmh.ajax.isSuccess(response)) {
                $("#preview").html(response.message);
              } else {
                mmh.ajax.error(response);
              }
            }, 'json');
        },
        photo: function(e) {
          var el = this;
          e.preventDefault();
          $.post(this.href, function(response) {
            if(mmh.ajax.isSuccess(response)) {
              $(el).before(response.message).remove();
            } else {
              mmh.ajax.error(response);
            }
          }, 'json');
        }
      }
    };
  }
)();
