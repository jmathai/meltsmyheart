var mmh = (function() {
  var constants = {
        siteUrl: 'http://meltsmyheart.com',
        siteName: 'Melts My Heart',
        databaseName: 'meltsmyheart',
        headerBackgroundImage:'images/header.png',
        headerBackgroundColor: '#000',
        headerBorderColor: '#ccc',
        headerFont: {fontSize:20,fontFamily:'Helvetica Neue',fontWeight:'bold'},
        headerFontColor: '#fff',
        hr: {width:'90%',height:2,backgroundColor:'#ddd',bottom:5},
        labelForm: {fontSize:18,height:25,left:10,right:10},
        textField: {left:10,right:10,borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,height:30},
        viewContainer: {top:48},
        viewContent: {width:'100%',backgroundImage:'images/stripes_diagonal.png'}
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
          var params = {left:10,right:10, height:40, style:Titanium.UI.iPhone.SystemButtonStyle.PLAIN, borderRadius:10, font:{fontSize:16,fontWeight:'bold'}, backgroundGradient:{type:'linear', colors:['#000001','#666666'], startPoint:{x:0,y:0}, endPoint:{x:2,y:50}, backFillStart:false}, borderWidth:1, borderColor:'#666', buttonBackgroundColor: '#f2db33', buttonBorderColor: '#000', buttonWidth: '80%', buttonHeight: 40, buttonBorderRadius: 10};
          params.title = title;
          return Ti.UI.createButton(params);
        }
      },
      hr: {
        create: function() {
          params = arguments[0] ? arguments[0] : mmh.constant('hr');
          return mmh.ui.view.create(params);
        }
      },
      label: {
        create: function(text) {
          var params = arguments[1] ? arguments[1] : {};
          params.text = text;
          return Ti.UI.createLabel(params);
        }
      },
      textField: {
        create: function() {
          var params = arguments[0] ? arguments[0] : {};
          return Ti.UI.createTextField(params);
        }
      },
      view: {
        create: function() {
          var view, params;
          params = arguments[0] ? arguments[0] : {};
          return Ti.UI.createView(params);
        }
      },
      window: {
        create: function(title, view) {
          var win, hdr, lbl;
          win = Ti.UI.createWindow({  
              backgroundColor: '#fff',
              width:'100%',
              size:{width:'100%'}
          });
          hdr= Ti.UI.createView({height:50,width:'110%',borderWidth:2,borderColor:mmh.constant('headerBorderColor'),backgroundImage:mmh.constant('headerBackgroundImage'),top:-2});
          lbl = Ti.UI.createLabel({color: mmh.constant('headerFontColor'), font: mmh.constant('headerFont') ,top:12, textAlign:'center', height:'auto', text:title});
          hdr.add(lbl);
          win.add(hdr);
          win.add(view);
          return win;
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
    },
    util: {
      merge: function(obj1, obj2) {
        var i;
        for(i in obj2) {
          if(obj2.hasOwnProperty(i)) {
            obj1[i] = obj2[i];
          }
        }
        return obj1;
      }
    }
  };
})();
