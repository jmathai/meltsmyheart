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
        textField: {left:10,right:10,borderStyle:Titanium.UI.INPUT_BORDERSTYLE_ROUNDED,height:60},
        textAreaFont: {fontSize:18},
        viewContainer: {top:48},
        viewContent: {width:'100%',backgroundColor:'#000'/*backgroundImage:'images/stripes_diagonal.png'*/}
      },
      currentChildId = null,
      currentWindow = null,
      previousWindow = null,
      hasContacts = null,
      hasChildren = null,
      userId = null,
      userToken = null,
      userInitSuccess,
      userInitFailure,
      activityIndicator,
      resize;
  resize = function(image) {
    return image;
    /*var imageView, imageToResize, imageOut;
    imageView = Ti.UI.createImageView({image:image});
    imageToResize = imageView.toImage();
    imageToResize.width = 300;//parseInt(imageToResize.width * .5);
    imageToResize.height = 400;//parseInt(imageToResize.height * .5);
    imageOut = Ti.UI.createImageView({image:imageToResize});
    return imageOut.toBlob();*/
  };

  userInitSuccess = function(e) {
    var json = JSON.parse(this.responseText), hasContacts;
    recipientCount = json.params.recipientCount;
    childrenCount = json.params.childrenCount;
    mmh.util.log('setting recipientCount ' + recipientCount);
    db.insertKey('hasContacts', recipientCount);
    db.insertKey('hasChildren', childrenCount);
    mmh.ui.window.openAndShow(winHome);
    mmh.ui.loader.hide();
  };
  userInitFailure = function(e) {
    var dialog = Ti.UI.createAlertDialog({
        title: 'Could not log in',
        message: 'Sorry, we could not get your information.',
        buttons: ['Ok']
    });
    dialog.addEventListener('click', function(e) {
      mmh.user.clearCredentials();
      winSignIn.open();
    });
    mmh.ui.loader.hide();
    dialog.show();
  }

  return  {
    ajax: {
      isSuccess: function(response) {
        return response.status >= 200 && response.status <= 299;
      }
    },
    app: {
      setCurrentWindow: function(win) {
        previousWindow = currentWindow;
        currentWindow = win;
      },
      getPreviousWindow: function() {
        return previousWindow;
      }
    },
    camera: {
      start: function(childId, callback) {
        mmh.user.setCurrentChildId(childId);
        camera.start(callback);
      },
      callback: {
        success: function(ev) {
          /*var image = ev.media;
          Titanium.API.info('image is ' + image);
          // TODO loader
          mmh.upload.post(image);*/
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
        create: function(title/*, params*/) {
          //var params = {left:10,right:10, height:40, style:Titanium.UI.iPhone.SystemButtonStyle.PLAIN, borderRadius:10, font:{fontSize:16,fontWeight:'bold'}, backgroundGradient:{type:'linear', colors:['#000001','#666666'], startPoint:{x:0,y:0}, endPoint:{x:2,y:50}, backFillStart:false}, borderWidth:1, borderColor:'#666', buttonBackgroundColor: '#f2db33', buttonBorderColor: '#000', buttonWidth: '80%', buttonHeight: 40, buttonBorderRadius: 10};
          var def = {title:title,image:'images/button-yellow.png',width:160,height:40},
            params = arguments.length > 1 ? arguments[1] : {};
          return Ti.UI.createButton(mmh.util.merge(def, params));
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
      },
      textArea: {
        create: function() {
          var params = arguments[0] ? arguments[0] : {};
          return Ti.UI.createTextArea(params);
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
      scrollView: {
        create: function() {
          var view, params;
          params = arguments[0] ? arguments[0] : {};
          return Ti.UI.createScrollView(params);
        }
      },
      window: {
        create: function(title, view) {
          var win, hdr, lbl;
          win = Ti.UI.createWindow({  
              backgroundColor: '#000',
              width:'100%',
              size:{width:'100%'}
          });
          hdr= Ti.UI.createView({height:50,width:'110%',borderWidth:2,borderColor:mmh.constant('headerBorderColor'),backgroundImage:mmh.constant('headerBackgroundImage'),top:-2});
          lbl = Ti.UI.createLabel({color: mmh.constant('headerFontColor'), font: mmh.constant('headerFont') ,top:12, textAlign:'center', height:'auto', text:title});
          hdr.add(lbl);
          win.add(hdr);
          win.add(view);
          return win;
        },
        animateTo: function(from, to) {
          /*var t = Ti.UI.iPhone.AnimationStyle.FLIP_FROM_LEFT;
          from.animate({view:to,transition:t});*/
          from.hide();
          mmh.ui.window.openAndShow(to);
        },
        openAndShow: function(win) {
          win.open();
          win.show();
        }
      }
    },
    upload: {
      post: function() {
        mmh.ui.loader.show('Uploading photo...');
        var postbody = mmh.user.getRequestCredentials(), image;
        postbody.photo = resize(jsShare.getImage());
        postbody.message = jsShare.getTextArea().value;
        httpClient.initAndSend({
          url: mmh.constant('siteUrl') + '/photos/add/'+mmh.user.getCurrentChildId(),
          method: 'POST',
          postbody: postbody,
          success: mmh.upload.callback.success,
          failure: mmh.upload.callback.failure
        });
      },
      callback: {
        success: function(ev) {
          Ti.UI.createAlertDialog({
              title: 'Photo posted',
              message: 'Your photo was posted successfully.'
          }).show();
          Titanium.API.info('Upload posted successfully.');
          mmh.ui.loader.hide();
          mmh.ui.window.animateTo(winShare, winHome);
        },
        failure: function(ev) {
          mmh.ui.loader.hide();
          Ti.UI.createAlertDialog({
              title: 'Photo upload failed',
              message: 'Sorry, could not upload your photo.'
          }).show();
          Ti.API.info('Upload post encountered an error.');
          /*if (xhr.status == 200) {
              xhr.onload(e);
              return;
          }*/
        }
      }
    },
    user: {
      clearCredentials: function() {
        db.execute('DELETE FROM prefs');
      },
      getCurrentChildId: function() {
        return currentChildId;
      },
      getId: function() {
        if(userId !== null) {
          return userId;
        }
        userId = db.queryForKey('userId');
        return userId;
      },
      getRequestCredentials: function (){
        return {userId: mmh.user.getId(), userToken: mmh.user.getToken()};
      },
      getToken: function() {
        if(userToken !== null) {
          return userToken;
        }
        userToken = db.queryForKey('userToken');
        return userToken;
      },
      hasChildren: function() {
        // TODO: replace with ajax call or set on login
        if(hasChildren !== null) {
          return hasChildren;
        }
        hasContacts = db.queryForKey('hasChildren');
        return hasChildren;
      },
      hasContacts: function() {
        // TODO: replace with ajax call or set on login
        if(hasContacts !== null) {
          return hasContacts;
        }
        hasContacts = db.queryForKey('hasContacts');
        return hasContacts;
      },
      init: function() {
        userId = mmh.user.getId();
        if(userId === null) {
          mmh.util.log('No user Id found');
          mmh.user.clearCredentials();
          mmh.ui.window.openAndShow(winSignIn);
        } else {
          mmh.util.log('Found user Id ' + userId);
          mmh.ui.loader.show('Loading Melts My Heart');
          httpClient.initAndSend({
            url: mmh.constant('siteUrl') + '/mobile/init',
            method: 'POST',
            postbody: mmh.user.getRequestCredentials(),
            success: userInitSuccess,
            failure: userInitFailure
          });
        }
      },
      setCurrentChildId: function(childId) {
        Ti.API.info('setting childId to ' + childId);
        currentChildId = childId;
      },
      setRequestCredentials: function(uId, uToken) {
        rs = db.execute("INSERT INTO prefs(name, value) VALUES('userId', '"+uId+"');");
        rs = db.execute("INSERT INTO prefs(name, value) VALUES('userToken', '"+uToken+"');");
        userId = uId;
      },
    },
    util: {
      device: {
        display: {
          width: Ti.Platform.displayCaps.platformWidth,
          height: Ti.Platform.displayCaps.platformHeight
        }
      },
      log: function(msg) {
        if(typeof msg !== 'string') {
          msg = JSON.stringify(msg);
        }
        Ti.API.info('[MMH] ' + msg);
      },
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

Function.prototype.bind = function(scope) {
  var _function = this;
  
  return function() {
    return _function.apply(scope, arguments);
  };
};
