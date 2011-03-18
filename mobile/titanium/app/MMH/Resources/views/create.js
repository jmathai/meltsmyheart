var winCreate, viewCreate, viewCreateContainer, jsCreate, winCreateOpened = false;
viewCreateContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewCreate = mmh.ui.view.create(mmh.constant('viewContent'));

viewCreateContainer.add(mmh.ui.view.create({height:50}));
viewCreateContainer.add(viewCreate);
winCreate = mmh.ui.window.create('Sign In', viewCreateContainer);

jsCreate = (function() {
  return {
    open: function() {
      if(winCreateOpened) {
        winCreate.show();
        return;
      }
      var imgLogo = mmh.ui.logo.create({top:25}), txtCreateEmail, txtCreatePassword, txtCreatePasswordConfirm, btnCreate, lnkSignInAccount;

      viewCreate.add(imgLogo);
      // Email
      txtCreateEmail = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:120, hintText:'Email address', passwordMask:false, keyboardType: Ti.UI.KEYBOARD_EMAIL, autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE}));
      viewCreate.add(txtCreateEmail);

      // Password
      txtCreatePassword = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:195, hintText:'Password', passwordMask:true, autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE}));
      viewCreate.add(txtCreatePassword);

      // Password Confirm
      txtCreatePasswordConfirm = mmh.ui.textField.create(mmh.util.merge(mmh.constant('textField'), {top:270, hintText:'Confirm password', passwordMask:true, autocapitalization:Ti.UI.TEXT_AUTOCAPITALIZATION_NONE}));
      viewCreate.add(txtCreatePasswordConfirm);

      // Submit
      btnCreate = mmh.ui.button.create('Create Account', {top:345});
      btnCreate.addEventListener('click', function(ev) {
        var params;
        if(txtCreatePassword.value.length == 0 || txtCreatePassword.value != txtCreatePasswordConfirm.value) {
          Ti.UI.createAlertDialog({
              title: 'Passwords did not match',
              message: 'Please make sure that your passwords match.',
              buttons: ['Ok']
          }).show();
          return;
        }
        mmh.ui.loader.show('Logging in...');
        params = {
          url: mmh.constant('siteUrl') + '/api/user/create',
          method:'POST',
          postbody: {email:txtCreateEmail.value,password:txtCreatePassword.value,passwordConfirm:txtCreatePasswordConfirm.value,device:Ti.Platform.osname+' '+Ti.Platform.version+' ('+Ti.Platform.id+')'},
          success: function(e) {
            mmh.ui.loader.hide();
            var json = JSON.parse(this.responseText);
            if(!mmh.ajax.isSuccess(json) || json.params === false || json.params.userId.length === 0 || json.params.userToken.length === 0) {
              Ti.UI.createAlertDialog({
                  title: 'Problem creating account',
                  message: 'Sorry, we could not create an account for you. Please try again.',
                  buttons: ['Ok']
              }).show();
              winCreate.open();
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
            Ti.UI.createAlertDialog({
                title: 'Unexpected error',
                message: 'Sorry, an unexpected error occured.'
            }).show();
            winCreate.open();
          }
        };
        httpClient.initAndSend(params);
      });
      viewCreate.add(btnCreate);

      // Sign in
      lnkSignInAccount = mmh.ui.label.create('Already have an account?', {top:395, font:{fontColor:'#ff0'}});
      lnkSignInAccount.addEventListener('click', function(){ mmh.ui.window.animateTo(winCreate, winSignIn); });
      viewCreate.add(lnkSignInAccount);

      mmh.ui.window.openAndShow(winCreate);
      winCreateOpened = true;
    }
  }
})();
winCreate.addEventListener('open', jsCreate.open);
