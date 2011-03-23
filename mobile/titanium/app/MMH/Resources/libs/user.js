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
        db.insertKey('hasContacts', recipientCount);
        db.insertKey('hasChildren', childrenCount);
        mmh.ui.loader.hide();
        mmh.ui.window.openAndShow(winHome);
      },
      initFailure: function(e) {
        mmh.ui.loader.hide();
        mmh.ui.alert.create('Could not log in', 'Sorry, we could not get your information.');
        user.clearCredentials();
        mmh.ui.window.openAndShow(winCreate);
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
