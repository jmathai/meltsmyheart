Ti.include('libs/mmh.js');
Ti.include('libs/db.js');
Ti.include('libs/http.js');
Ti.include('libs/camera.js');

Ti.include('views/signin.js');
Ti.include('views/home.js');
// this sets the background color of the master UIView (when there are no windows/tab groups on it)

/*Ti.API.info(Ti.Platform.id);
Ti.API.info(Ti.Platform.model);
Ti.API.info(Ti.Platform.name);
Ti.API.info(Ti.Platform.osname);
Ti.API.info(Ti.Platform.username);
Ti.API.info(Ti.Platform.version);*/
Ti.API.info(JSON.stringify(Ti.Platform));

// Defaults
Ti.UI.setBackgroundColor('#fff');

// check if email and hashed password are stored
userId = mmh.user.getId();
Ti.API.info(userId);

/*btnStartCamera = mmh.ui.button.create('Start Camera', {width: 150});
btnStartCamera.addEventListener('click', function() { 
  camera.start({
    success: mmh.camera.callback.success,
    failure: mmh.camera.callback.failure
  });
});
winHome.add(btnStartCamera);*/
if(userId === null) {
  mmh.user.clearCredentials();
  winSignIn.open();
} else {
  winHome.open();
}
