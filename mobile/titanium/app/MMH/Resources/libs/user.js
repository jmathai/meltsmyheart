var user = (function() {
  var userId = null,
      userToken = null;

  return {
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
      return {userId: user.getId(), userToken: user.getToken()};
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
    callback: {
      initSuccess: function(e) {
        var json = JSON.parse(this.responseText), hasContacts;
        recipientCount = json.params.recipientCount;
        childrenCount = json.params.childrenCount;
        mmh.util.log('setting recipientCount ' + recipientCount);
        db.insertKey('hasContacts', recipientCount);
        db.insertKey('hasChildren', childrenCount);
        mmh.ui.window.openAndShow(winHome);
        mmh.ui.loader.hide();
      },
      initFailure: function(e) {
        var dialog = Ti.UI.createAlertDialog({
            title: 'Could not log in',
            message: 'Sorry, we could not get your information.',
            buttons: ['Ok']
        });
        dialog.addEventListener('click', function(e) {
          user.clearCredentials();
          winSignIn.open();
        });
        mmh.ui.loader.hide();
        dialog.show();
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
    }
  };
})();
