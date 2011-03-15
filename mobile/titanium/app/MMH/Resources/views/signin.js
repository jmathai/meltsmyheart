var winSignIn, viewSignIn, viewSignInContainer, jsSignIn, winSignInOpened = false;
viewSignInContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewSignIn = mmh.ui.view.create(mmh.constant('viewContent'));

viewSignInContainer.add(mmh.ui.view.create({height:50}));
viewSignInContainer.add(viewSignIn);
winSignIn = mmh.ui.window.create('Sign In', viewSignInContainer);

jsSignIn = function() {
  return {
    open: function(e) {
      if(winSignInOpened) {
        winSignIn.show();
        return;
      }
      
      // Email
      lblSignInEmail = mmh.ui.label.create('Email Address', mmh.util.merge(mmh.constant('labelForm'), {top:20}));
      txtSignInEmail = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:50}));
      viewSignIn.add(lblSignInEmail);
      viewSignIn.add(txtSignInEmail);

      // Password
      lblSignInPassword = mmh.ui.label.create('Password', mmh.util.merge(mmh.constant('labelForm'), {top:100}));
      txtSignInPassword = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:130,passwordMask:true}));
      viewSignIn.add(lblSignInPassword);
      viewSignIn.add(txtSignInPassword);

      // Submit
      btnSignIn = mmh.ui.button.create('Sign In');
      btnSignIn.addEventListener('click', function(ev) {
        var params;
        mmh.ui.loader.show('Logging in...');
        params = {
          url: mmh.constant('siteUrl') + '/api/login/token',
          method:'POST',
          postbody: {email:txtSignInEmail.value,password:txtSignInPassword.value,device:Ti.Platform.osname+' '+Ti.Platform.version+' ('+Ti.Platform.id+')'},
          success: function(e) {
            mmh.ui.loader.hide();
            var json = JSON.parse(this.responseText);
            if(!mmh.ajax.isSuccess(json) || json.params === false || json.params.userId.length === 0 || json.params.userToken.length === 0) {
              Ti.UI.createAlertDialog({
                  title: 'Problem logging in',
                  message: 'Sorry, we could not log you in.'
              }).show();
              winSignIn.open();
            } else {
              var userId, userToken, rs;
              userId = json.params.userId;
              userToken = json.params.userToken;
              mmh.user.setRequestCredentials(userId, userToken);
              mmh.user.init();
            }
          },
          failure: function(e) {
            mmh.ui.loader.hide();
            Ti.API.info(this.responseText);
            Ti.UI.createAlertDialog({
                title: 'Unexpected error',
                message: 'Sorry, an unexpected error occured.'
            }).show();
            winSignIn.open();
          }
        };
        httpClient.initAndSend(params);
      });
      viewSignIn.add(btnSignIn);
      winSignInOpened = true;
    }
  }
};
winSignIn.addEventListener('open', jsSignIn.open);
