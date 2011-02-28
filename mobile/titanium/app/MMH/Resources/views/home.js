var winHome, viewHome, viewHomeContainer, winHomeOpened = false;
viewHomeContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewHome = mmh.ui.view.create(mmh.constant('viewContent'));

viewHomeContainer.add(mmh.ui.view.create({height:50}));
viewHomeContainer.add(viewHome);
winHome = mmh.ui.window.create('Your Children', viewHomeContainer);

winHome.addEventListener('open', function() {
  if(winHomeOpened) {
    winHome.show();
    return;
  }
  var params, postbody;
  postbody = mmh.user.getRequestCredentials();
  if(postbody.userId === null || postbody.userToken === null) {
    Ti.UI.createAlertDialog({
        title: 'Not signed in',
        message: 'Sorry, you don\'t appear to be signed in.'
    }).show();
    winSignIn.open();
    return;
  }
  params = {
    url: mmh.constant('siteUrl') + '/api/children',
    method:'POST',
    postbody: postbody,
    success: function() {
      var json;
      json = JSON.parse(this.responseText);
      if(!mmh.ajax.isSuccess(json)) {
        Ti.UI.createAlertDialog({
            title: 'Problem logging in',
            message: 'Sorry, we could not log you in.'
        }).show();
        winSignIn.open();
      } else {
        var children = json.params.children, child, i;
        if(children.length > 0) {
          var image, button, childViewHeight = 125, spacer = 15, currentPosition = 0, 
              childView = Ti.UI.createView({height:childViewHeight,width:'90%',borderColor:'#fff',borderWidth:1,borderRadius:10,backgroundColor:'#ddd'}),
              cameraCallback = function() { camera.start(mmh.camera.callback); };
          for(i in children) {
            if(children.hasOwnProperty(i)) {
              child = children[i];
              thisView = childView;
              thisView.top = currentPosition+spacer;
              image = Ti.UI.createImageView({width:100,height:100,image:child.thumb,borderRadius:5,left:12});
              button = mmh.ui.button.create('Take a photo');
              button.left = 120;
              button.addEventListener('click', cameraCallback);
              thisView.add(image);
              thisView.add(button);
              viewHome.add(thisView);
              currentPosition += (childViewHeight+spacer);
            }
          }
        } else {
          // TODO no children view
          Titanium.API.info('this user has no children');
        }
      }
    },
    failure: function() {
      Ti.UI.createAlertDialog({
          title: 'Problem retrieving account',
          message: 'Sorry, we could not get your information.'
      }).show();
      winSignIn.open();
    }
  };
  Ti.API.info('sending children request');
  Ti.API.info(JSON.stringify(postbody));
  httpClient.initAndSend(params);
  winHomeOpened = true;
});
