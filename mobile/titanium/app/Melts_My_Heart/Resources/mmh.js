var mmh = (function() {
  var uploadUrl = 'http://meltsmyheart.com/upload.php',
      buttonBackgroundColor = '#f2db33',
      buttonBorderColor = '#000',
      buttonWidth = 150,
      buttonHeight = 40,
      buttonBorderRadius = 10,
      activityIndicator;

      
  return  {
    upload: {
      post: function(image) {
        mmh.ui.loader.show();
        httpClient.initAndSend({
          url: uploadUrl,
          method: 'POST',
          postbody: { 'file':image, 'bar':"hello" },
          success: mmh.upload.callback.success,
          failure: mmh.upload.callback.failure
        });
      },
      callback: {
        success: function(ev) {
          Titanium.API.info('Upload posted successfully.');
          mmh.ui.loader.hide();
        },
        failure: function(ev) {
          mmh.ui.loader.hide();
          Titanium.API.info('Upload post encountered an error.');
          if (xhr.status == 200) {
              xhr.onload(e);
              return;
          }
        }
      }
    },
    camera: {
      callback: {
        success: function(ev) {
          var image = ev.media;
          Titanium.API.info('image is ' + image);
          // TODO loader
          mmh.upload.post(image);
        },
        failure: function(ev) {
          msg = Titanium.UI.createAlertDialog({message: 'Something went wrong.'});
          msg.show();
        }
      }
    },
    ui: {
      button: {
        create: function(title) {
          var params = arguments[1] ? arguments[1] : {},
              width = params.width ? params.width : buttonWidth,
              height = params.height ? params.height : buttonHeight;

          return Titanium.UI.createButton({
                title: title, 
                backgroundColor: buttonBackgroundColor, 
                borderColor: buttonBorderColor,
                borderRadius: buttonBorderRadius, 
                size: {width:width,height:height}});
        }
      },
      loader: {
        show: function(/*message*/) {
          var params = {height:50, width:10};
          if(arguments[0]) {
            params.message = arguments[0];
          }

          activityIndicator = Titanium.UI.createActivityIndicator(params);
          activityIndicator.show();
        },
        hide: function(loader) {
          activityIndicator.hide();
        }
      }
    }
  };
})();
