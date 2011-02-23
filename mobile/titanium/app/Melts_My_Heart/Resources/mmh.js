var mmh = (function() {
  var constants = {
        siteUrl: 'http://meltsmyheart.com',
        siteName: 'Melts My Heart',
        databaseName: 'meltsmyheart',
        headerBackgroundColor: '#0a77aa',
        headerFont: {fontSize:20,fontFamily:'Helvetica Neue',fontWeight:'bold'},
        headerFontColor: '#fff',
        buttonBackgroundColor: '#f2db33',
        buttonBorderColor: '#000',
        buttonWidth: 150,
        buttonHeight: 40,
        buttonBorderRadius: 10,
        buttonFont: {fontSize:20,fontFamily:'Helvetica Neue'},
        buttonFontColor: '#999'
      },
      activityIndicator;

  return  {
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
    constant: function(name) {
      return constants[name];
    },
    ui: {
      button: {
        create: function(title) {
          var params = arguments[1] ? arguments[1] : {},
              width = params.width ? params.width : mmh.constant('buttonWidth'),
              height = params.height ? params.height : mmh.constant('buttonHeight');

          return Titanium.UI.createButton({
                title: title, 
                backgroundColor: mmh.constant('buttonBackgroundColor'), 
                borderColor: mmh.constant('buttonBorderColor'),
                borderRadius: mmh.constant('buttonBorderRadius'), 
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
    },
    switchView: function(title, view) {
      // TODO animate
      var hdr, lbl;
      hdr= Ti.UI.createView({height:50,borderWidth:0,backgroundColor:mmh.constant('headerBackgroundColor'),top:0});
      lbl = Ti.UI.createLabel({color: mmh.constant('headerFontColor'), font: mmh.constant('headerFont') ,top:12, textAlign:'center', height:'auto', text:title});
      hdr.add(lbl);
      winMain.add(hdr);
      winMain.add(view);
    },
    upload: {
      post: function(image) {
        mmh.ui.loader.show();
        httpClient.initAndSend({
          url: mmh.constant('siteUrl') + '/upload.php',
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
    user: {
      getId: function() {
        return db.queryForKey('userId');
      },
      getToken: function() {
        return db.queryForKey('token');
      }
    }
  };
})();
