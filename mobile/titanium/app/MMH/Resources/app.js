var userId;

Ti.include('libs/mmh.js');
Ti.include('libs/db.js');
Ti.include('libs/http.js');
Ti.include('libs/camera.js');

Ti.include('views/signin.js');
Ti.include('views/home.js');
Ti.include('views/contacts.js');
Ti.include('views/share.js');

/*Ti.API.info(Ti.Platform.id);
Ti.API.info(Ti.Platform.model);
Ti.API.info(Ti.Platform.name);
Ti.API.info(Ti.Platform.osname);
Ti.API.info(Ti.Platform.username);
Ti.API.info(Ti.Platform.version);*/
Ti.API.info(JSON.stringify(Ti.Platform));
Ti.API.info(mmh.util.device.display.width);

// Defaults
Ti.UI.setBackgroundColor('#000');

// check if email and hashed password are stored
userId = mmh.user.getId();
Ti.API.info(userId);

if(userId === null) {
  mmh.user.clearCredentials();
  winSignIn.open();
} else {
  winHome.open();
}
