Ti.include('mmh.js');
Ti.include('views.js');

Ti.include('db.js');
Ti.include('http.js');
Ti.include('camera.js');
// this sets the background color of the master UIView (when there are no windows/tab groups on it)

// Defaults
Ti.UI.setBackgroundColor('#000');

// TODO use only one window and call mmh.switchView by passing different views
var winMain;

// check if email and hashed password are stored
userId = mmh.user.getId();
Ti.API.info(userId);

// home window
var winMain = Ti.UI.createWindow({  
    title: mmh.constant('siteName'),
    backgroundColor: '#fff'
});

/*btnStartCamera = mmh.ui.button.create('Start Camera', {width: 150});
btnStartCamera.addEventListener('click', function() { 
  camera.start({
    success: mmh.camera.callback.success,
    failure: mmh.camera.callback.failure
  });
});
winHome.add(btnStartCamera);*/

winMain.open();
mmh.switchView('One', Ti.UI.createView());
mmh.switchView('Two', Ti.UI.createView());
