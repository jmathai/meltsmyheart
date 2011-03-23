var winSignIn, viewSignIn, viewSignInContainer, jsSignIn, winSignInOpened = false;
viewSignInContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewSignIn = mmh.ui.view.create(mmh.constant('viewContent'));

viewSignInContainer.add(mmh.ui.view.create({height:50}));
viewSignInContainer.add(viewSignIn);
winSignIn = mmh.ui.window.create('Sign In', viewSignInContainer);

jsSignIn = (function() {
  return {
    open: function() {
      if(winSignInOpened) {
        winSignIn.show();
        return;
      }
      var imgLogo = mmh.ui.logo.create({top:25}), txtSignInEmail, txtSignInPassword, btnSignIn, lnkCreateAccount;

      viewSignIn.add(imgLogo);
      // Email
      txtSignInEmail = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:120, hintText:'Email address', keyboardType: Ti.UI.KEYBOARD_EMAIL, autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE}));
      viewSignIn.add(txtSignInEmail);

      // Password
      txtSignInPassword = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:195, hintText:'Password', passwordMask:true, autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE}));
      viewSignIn.add(txtSignInPassword);

      // Submit
      btnSignIn = mmh.ui.button.create('Sign In', {top:270});
      btnSignIn.addEventListener('click', function(ev) {
        var params;
        if(txtSignInEmail.value === undefined || txtSignInPassword.value === undefined) {
          var title = 'Required form fields', message = 'Please fill in all form fields.';
          mmh.ui.alert.create(title, message);
          return;
        }
        mmh.ui.loader.show('Logging in...');
        params = {
          url: mmh.constant('siteUrl') + '/api/login/token',
          method:'POST',
          postbody: {email:txtSignInEmail.value,password:txtSignInPassword.value,device:Ti.Platform.osname+' '+Ti.Platform.version+' ('+Ti.Platform.id+')'},
          success: function(e) {
            mmh.ui.loader.hide();
            var json = JSON.parse(this.responseText);
            if(!mmh.ajax.isSuccess(json) || json.params === false || json.params.userId.length === 0 || json.params.userToken.length === 0) {
              mmh.ui.alert.create('Problem logging in', 'Sorry, we could not log you in.');
              winSignIn.open();
            } else {
              var userId, userToken, rs;
              userId = json.params.userId;
              userToken = json.params.userToken;
              user.setRequestCredentials(userId, userToken);
              mmh.init();
            }
          },
          failure: function(e) {
            mmh.ui.loader.hide();
            Ti.API.info(this.responseText);
            mmh.ui.alert.create('Unexpected error', 'Sorry, an unexpected error occured.');
            winSignIn.open();
          }
        };
        httpClient.initAndSend(params);
      });
      viewSignIn.add(btnSignIn);

      // Create
      lnkCreateAccount = mmh.ui.label.create('Need an account?', {top:320, font:{fontColor:'#ff0'}});
      lnkCreateAccount.addEventListener('click', function(){ mmh.ui.window.animateTo(winSignIn, winCreate); });
      viewSignIn.add(lnkCreateAccount);

      mmh.ui.window.openAndShow(winSignIn);
      winSignInOpened = true;
    }
  }
})();
winSignIn.addEventListener('open', jsSignIn.open);
