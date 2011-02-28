var winSignIn, viewSignIn, viewSignInContainer;
viewSignInContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewSignIn = mmh.ui.view.create(mmh.constant('viewContent'));


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
  Ti.API.info('Firing');
  var params;
  params = {
    url: mmh.constant('siteUrl') + '/login/token',
    method:'POST',
    postbody: {email:txtSignInEmail.value,password:txtSignInPassword.value,device:Ti.Platform.osname+' '+Ti.Platform.version+' ('+Ti.Platform.id+')'},
    success: function(e) {
      var json = JSON.parse(this.responseText);
      if(!mmh.ajax.isSuccess(json) || json.params === false || json.params.userId.length === 0 || json.params.userToken.length === 0) {
        Titanium.UI.createAlertDialog({
            title: 'Problem logging in',
            message: 'Sorry, we could not log you in.'
        }).show();
      } else {
        var userId, userToken;
        userId = parseInt(json.params.userId, 10);
        userToken = json.params.userToken.replace(/[^a-z0-9]/, '');
        db.execute("INSERT INTO prefs(name, value) VALUES('userId', '"+userId+"');");
        db.execute("INSERT INTO prefs(name, value) VALUES('userToken', '"+userToken+"');");
        Ti.API.info('Inserted ' + userId + ' and ' + userToken);
      }
    },
    failure: function(e) {
      Titanium.UI.createAlertDialog({
          title: 'Problem logging in',
          message: 'Sorry, we could not log you in.'
      }).show();
    }
  };
  httpClient.initAndSend(params);
});
viewSignIn.add(btnSignIn);

viewSignInContainer.add(mmh.ui.view.create({height:50}));
viewSignInContainer.add(viewSignIn);
winSignIn = mmh.ui.window.create('Sign In', viewSignInContainer);
