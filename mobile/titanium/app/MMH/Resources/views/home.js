var winHome, viewHome, jsHome, viewHomeContainer, winHomeOpened = false;
viewHomeContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewHome = mmh.ui.view.create(mmh.constant('viewContent'));

viewHomeContainer.add(mmh.ui.view.create({height:50}));
viewHomeContainer.add(viewHome);
winHome = mmh.ui.window.create('Your Children', viewHomeContainer);

jsHome = (function() {
  return {
    open: function () {
      var forceReload = arguments.length > 0 ? arguments[0] : false;
      if(!forceReload && winHomeOpened) {
        winHome.show();
        return;
      }
      var params, postbody;
      mmh.ui.loader.show('Loading children...');
      postbody = user.getRequestCredentials();
      if(postbody.userId === null || postbody.userToken === null) {
        mmh.ui.alert.create('Not signed in', 'Sorry, you don\'t appear to be signed in.');
        mmh.ui.window.openAndShow(jsWinSignIn.getWindow());
        return;
      }
      params = {
        url: mmh.constant('siteUrl') + '/api/children',
        method:'POST',
        postbody: postbody,
        success: jsHome.success,
        failure: jsHome.failure
      };
      httpClient.initAndSend(params);
      winHomeOpened = true;
    },
    failure: function() {
      mmh.ui.loader.hide();
      mmh.ui.alert.create('Problem retrieving account', 'Sorry we could not get your information.');
      mmh.ui.window.openAndShow(winSignIn);
    },
    success: function() {
      var json;
      mmh.ui.loader.hide();
      json = JSON.parse(this.responseText);
      if(!mmh.ajax.isSuccess(json)) {
        mmh.ui.alert.create('Problem logging in', 'Sorry we could not log you in.');
        mmh.ui.window.openAndShow(winSignIn);
      } else {
        var children = json.params.children, child, i;
        /*if(children.length == 1) {
          child = children[0];
          mmh.camera.start(child.c_id, jsShare.camera);
        } else */if(children.length > 0) {
          var table, row, rowHeight = 130, rows = [], view, button, label;
          row = Ti.UI.createTableViewRow({className:'addChildRow', height: 50});
          label = mmh.ui.label.create('Add another child', {right:10});
          row.add(label);
          row.addEventListener('click', function() { mmh.ui.window.openAndShow(winAddChild); });
          rows.push(row);
          for(i in children) {
            if(children.hasOwnProperty(i)) {
              child = children[i];
              label = mmh.ui.label.create(child.c_name, {left:70});
              label.font = {fontSize:20};
              view = mmh.ui.view.create();
              view.add(label);
              row = Ti.UI.createTableViewRow({className:'childRow', leftImage: (child.thumb === null ? 'images/profile-icon.png' : child.thumb), height: rowHeight, hasDetail: true});
              row.add(view);
              row.addEventListener('click', function(){ mmh.camera.start(this.c_id, jsShare.camera); }.bind(child));
              rows.push(row);
            }
          }
          table = Ti.UI.createTableView({data: rows});
          viewHome.add(table);
        } else {
          mmh.ui.window.openAndShow(winAddChild);
        }
      }
    }
  };
})();

winHome.addEventListener('open', jsHome.open);
