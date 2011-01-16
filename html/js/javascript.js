var mmh = (function() {
    var displayMessage = function(type, msg, sticky/*, small*/) {
      var id = type == 'error' ? '#error' : '#message';
      var small = arguments.length > 3 && arguments[3] ? '<p>'+arguments[3]+'</p>' : '';
      var t = parseInt(document.body.scrollTop+5);
      $(id).css({"top":+t+"px"});
      msg += ' - [<a href="#" onclick="mmh.hideError(); mmh.hideConfirm(); return false;">Close</a>]'+small;
      if(!sticky)
        $(id).html(msg).show("fade", {}, 1000, function(){ setTimeout(function(){ $(id).hide("fade", {}, 1500); }, 5000) });
      else
        $(id).html(msg).show("fade", {}, 1000);
    };
    var hideMessage = function(type, args) {
      var id = type == 'error' ? '#error' : '#message';
      var delay = args.length > 0 ? args[0] : null;
      if(delay)
        $(id).html("").hide("blind", {}, 1500);
      else
        $(id).html("").hide();
    };
    var overlay = function(el, tgt) {
      var tgt = '#' + (arguments[1] || 'modal');
      $(el).overlay({
        target: tgt,
        mask: {
          color: '#96bfce',
          loadSpeed: 200,
          opacity: 0.9
        },
        top:'10%',
        close: tgt + ' button.close',
        onBeforeLoad: function() {
          mmh.loadDialog(this.getTrigger().attr("href"), {}, tgt);
        },
        onLoad: function() {
          $(tgt + ' .close').click(function(e){ e.preventDefault(); $(el).data("overlay").close();});
        }
      });
    };

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
      displayConfirm: function(msg/*, sticky, small*/) {
        var sticky = arguments.length > 1 ? arguments[1] : false;
        var small = arguments.length > 2 ? arguments[2] : null;
        msg = '<span class="ui-icon ui-icon-highlight" style="float:left; margin:5px 3px 0 0;"></span>'+msg;
        displayMessage('message', msg, sticky, small);
      },
      displayError: function(msg/*, sticky, small*/) {
        var sticky = arguments.length > 1 ? arguments[1] : false;
        var small = arguments.length > 2 ? arguments[2] : null;
        msg = '<span class="ui-icon ui-icon-alert" style="float:left; margin:5px 3px 0 0;"></span>'+msg;
        displayMessage('error', msg, sticky, small);
      },
      hideConfirm: function(/*delay=null*/) {
        hideMessage('message', arguments);
      },
      hideError: function(/*delay=null*/) {
        hideMessage('error', arguments);
      },
      loadDialog: function(url/*, params, tgt */) {
        var params = arguments[1] || {}, tgt = arguments[2] || '#modal';
        $.get(url, params, function(response) {
          if(mmh.ajax.isSuccess(response)) {
            _gaq.push(["_trackEvent", "dialog", "load", url]);
            $(tgt).html(response.message);
          } else {
            mmh.displayError("Sorry, there was a problem loading the page you requested.");
          }
        }, 'json');
      },
      overlay: function(i, el) {
        overlay(el, 'modal');
      },
      overlayWide: function(i, el) {
        overlay(el, 'modal-wide');
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

$.tools.validator.localize("en", {
  '[required]':'This form field is required.',
  '[min]':'Arvon on oltava suurempi, kuin $1',
});
$.tools.validator.fn("[date]", "Please enter a valid date.", function(input) {
  var value = input.attr('date');
  switch(value) {
    case 'mm/dd/yyyy':
      return input.val().match(/\d{1,2}\/\d{1,2}\/\d{4}/) != null;
      break;
  }
  return true;
});
$.tools.validator.fn("[check-name]", "This name is already in use.", function(input) {
  $.ajax({
            url: '/child/check',
            type: 'POST',
            cache: true,
            async: false,
            dataType: 'json',
            data: {value: input.val()},
            error: function() {
              ret = false;
            },
            success: function(response) {
              ret = response.message;
            }
          });
  return ret;
});
$.tools.validator.fn("[data-equals]", "Does not match $1.", function(input) {
	var name = input.attr("data-equals"),
		 field = this.getInputs().filter("[name=" + name + "]"); 
	return input.val() == field.val() ? true : [name]; 
});
