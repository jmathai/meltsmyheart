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
      mmh.ui.loader.show('Loading...');
      postbody = user.getRequestCredentials();
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
      httpClient.initAndSend(params);
      winHomeOpened = true;
    },
    failure: function() {
      mmh.ui.loader.hide();
      Ti.UI.createAlertDialog({
          title: 'Problem retrieving account',
          message: 'Sorry, we could not get your information.'
      }).show();
      winSignIn.open();
    },
    success: function() {
      var json;
      mmh.ui.loader.hide();
      json = JSON.parse(this.responseText);
      if(!mmh.ajax.isSuccess(json)) {
        Ti.UI.createAlertDialog({
            title: 'Problem logging in',
            message: 'Sorry, we could not log you in.'
        }).show();
        winSignIn.open();
      } else {
        var children = json.params.children, child, i;
        if(false && children.length == 1) {
          child = children[0];
          mmh.camera.start(child.c_id, jsShare.camera);
        } else if(children.length > 1) {
          var table, row, rowHeight = 130, rows = [], view, button, label;
          for(i in children) {
            if(children.hasOwnProperty(i)) {
              child = children[i];
              //button = mmh.ui.button.create('Take a photo');
              //button.addEventListener('click', function(){ mmh.camera.start(this.c_id, jsShare.camera); }.bind(child));
              label = mmh.ui.label.create(child.c_name, {left:70});
              label.font = {fontSize:20};
              view = mmh.ui.view.create();
              view.add(label);
              //view.add(button);
              row = Ti.UI.createTableViewRow({ className:'childRow', leftImage: child.thumb, height: rowHeight, hasDetail: true});
              row.add(view);
              row.addEventListener('click', function(){ mmh.camera.start(this.c_id, jsShare.camera); }.bind(child));
              rows.push(row);
              /*button = mmh.ui.button.create('Take a photo');
              button.left = 120;
              button.addEventListener('click', function(){ mmh.camera.start(this.c_id, jsShare.camera); }.bind(child));
              thisView.add(image);

              thisView.add(button);
              viewHome.add(thisView);
              currentPosition += (childViewHeight+spacer);*/
            }
          }
          table = Ti.UI.createTableView({data: rows});
          viewHome.add(table);
        } else {
          // TODO no children view
          Titanium.API.info('this user has no children');
          Ti.UI.createAlertDialog({
              title: 'No Children',
              message: 'You have not added any children yet.'
          }).show();
        }
      }
    }
  };
})();

winHome.addEventListener('open', jsHome.open);
