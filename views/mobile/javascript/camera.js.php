$("a#start-camera").click(function(e) {
  if(navigator.camera) {
    navigator.camera.getPicture(
      cameraSuccess, 
      cameraError,
      {
        quality : 75, 
        destinationType : Camera.DestinationType.DATA_URL, 
        sourceType : Camera.PictureSourceType.CAMERA, 
        allowEdit : true
      }
    );
    return false;
  }
});
