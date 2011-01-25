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
          var anchor = this.getTrigger(), href = anchor.attr("href"), track = anchor.attr("track") || href;
          $("#modal").html("");
          mmh.loadDialog(href, {}, tgt);
          mpq.push(["track", track, {"display": "modal"}]); 
        },
        onLoad: function() {
          $(tgt + ' .close').live('click', function(e){ e.preventDefault(); $(el).data("overlay").close();});
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
            $("#button-view-page").show();
            if(mmh.ajax.isSuccess(response)) {
              if($(el).hasClass('add'))
                $(el).removeClass('add').addClass('remove');
              else
                $(el).removeClass('remove').addClass('add');
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
          //if(mmh.ajax.isSuccess(response)) {
          if(response.message) {
            _gaq.push(["_trackEvent", "dialog", "load", url]);
            $(tgt).html(response.message);
          /*} else {
            mmh.displayError("Sorry, there was a problem loading the page you requested.");*/
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
        complete: function(file) {
          var label = $("#photo-"+file.id+' label'),
              stats = this.getStats();
          this.uploadStart();
          label.html(label.html().replace(/^\d{1,3}/, '100'));
          $("#photo-"+file.id+">div").css('backgroundPosition', "1px 0")

          if(stats.files_queued == 0)
            $("#button-view-page").show();
        },
        debug: function() { },
        dialog: function(numSelected, numQueued, totalQueued) {
          this.startUpload();
          return true;
        },
        error: function(file, code, message) {
          $("#photo-"+file.id+">div").addClass("upload-error");
        },
        loaded: function() { },
        progress: function(file, complete, total) {
          var pct = Math.ceil(complete/total*100), 
              px = 119-parseInt(complete/total*119), 
              label = $("#photo-"+file.id+' label');
          label.html(label.html().replace(/^\d{1,3}/, pct));
          $("#photo-"+file.id+">div").css('backgroundPosition', "-"+px+"px 0")
        },
        queued: function(file) {
          var queueItem = '<div class="progressbar" id="photo-'+file.id+'"><div><div></div></div><label>0%<br>' + file.name + '</label></div>';
          $("#upload-queue").append(queueItem);
          return true;
        },
        start: function() { return true; },
        success: function(file) {
          $("#photo-"+file.id).addClass("complete");
        }
      },
    };
  }
)();
