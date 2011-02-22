Titanium.include('mmh.js');
Titanium.include('http.js');
Titanium.include('camera.js');
// this sets the background color of the master UIView (when there are no windows/tab groups on it)

// Defaults
Titanium.UI.setBackgroundColor('#000');

var winMain = Titanium.UI.createWindow({  
    title:'Melts My Heart',
    backgroundColor:'#fff'
});
var lblMain = Titanium.UI.createLabel({
	color:'#999',
	text:'Melts My Heart',
	font:{fontSize:20,fontFamily:'Helvetica Neue'},
	textAlign:'center',
	width:'auto'
});
winMain.add(lblMain);

btnStartCamera = mmh.ui.button.create('Start Camera', {width: 150});
btnStartCamera.addEventListener('click', function() { 
  camera.start({
    success: mmh.camera.callback.success,
    failure: mmh.camera.callback.failure
  });
});

winMain.add(btnStartCamera);
winMain.open();
