var userId;

// libraries
Ti.include('libs/user.js');
Ti.include('libs/mmh.js');
Ti.include('libs/db.js');
Ti.include('libs/http.js');
Ti.include('libs/camera.js');
Ti.include('libs/contactList.js');

// views
Ti.include('views/signin.js');
Ti.include('views/create.js');
Ti.include('views/home.js');
Ti.include('views/share.js');

mmh.util.log(JSON.stringify(Ti.Platform));
mmh.util.log(mmh.util.device.display.width);

// Defaults
Ti.UI.setBackgroundColor('#000');

// checks if userId is set and loads proper screen
mmh.init();
