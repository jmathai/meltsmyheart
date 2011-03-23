var userId = null;

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
Ti.include('views/addchild.js');
Ti.include('views/home.js');
Ti.include('views/share.js');
Ti.include('views/confirm.js');

// Defaults
Ti.UI.setBackgroundColor('#000');

// checks if userId is set and loads proper screen
mmh.init();
