var winHome, viewHome, jsHome, viewHomeContainer, winHomeOpened = false;
viewHomeContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewHome = mmh.ui.view.create(mmh.constant('viewContent'));

viewHomeContainer.add(mmh.ui.view.create({height:50}));
viewHomeContainer.add(viewHome);
winHome = mmh.ui.window.create('Your Children', viewHomeContainer);

jsHome = (function() {
  return {
    open: function () {
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
        success: jsHome.success,
        failure: jsHome.failure
      };
      Ti.API.info('sending children request');
      Ti.API.info(JSON.stringify(postbody));
      httpClient.initAndSend(params);
      winHomeOpened = true;
    },
    failure: function() {
      Ti.UI.createAlertDialog({
          title: 'Problem retrieving account',
          message: 'Sorry, we could not get your information.'
      }).show();
      winSignIn.open();
    },
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
          var image, button, childViewHeight = 125, spacer = 15, currentPosition = 0, thisView;
          for(i in children) {
            if(children.hasOwnProperty(i)) {
              child = children[i];
              thisView = Ti.UI.createView({height:childViewHeight,width:'90%',borderColor:'#fff',borderWidth:1,borderRadius:10,backgroundColor:'#ddd'});
              thisView.top = currentPosition+spacer;
              image = Ti.UI.createImageView({width:100,height:100,image:child.thumb,borderRadius:5,left:12});
              button = mmh.ui.button.create('Take a photo');
              button.left = 120;
              button.addEventListener('click', function(){ mmh.camera.start(this.c_id, jsShare.camera); }.bind(child));
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
    }
  };
})();

winHome.addEventListener('open', jsHome.open);
