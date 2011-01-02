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
      },
      swfHandlers: {
        complete: function() {
                    console.log('complete');
        },
        debug: function() {
                 console.log('debug');
        },
        dialog: function(numSelected, numQueued, totalQueued) {
                  console.log('dialog');
                  console.log(arguments);
                  this.startUpload();
                  return true;
        },
        error: function(fileObj, code, message) {
                 console.log('error: ' + code + ' - ' + message);
        },
        loaded: function() {
                  console.log('loaded');
        },
        progress: function() {
                    console.log('progress');
        },
        queued: function(file) {
                  console.log('queued');
                  var queueItem = '<div id="photo-'+file.id+'" class="photo-queue-item"></div>';
                  $("#upload-queue").prepend(queueItem);
                  return true;
        },
        start: function() {
                 console.log('start');
                 return true;
        },
        success: function() {
                   console.log('success');
        }
      },
    };
  }
)();
