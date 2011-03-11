var winShare, viewShare, jsShare, viewShareContainer, winShareOpened = false;
viewShareContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewShare = mmh.ui.scrollView.create(mmh.constant('viewContent'));

viewShareContainer.add(mmh.ui.view.create({height:50}));
viewShareContainer.add(viewShare);
winShare = mmh.ui.window.create('Share This Photo', viewShareContainer);

jsShare = (function() {
  var image, textArea;
  var fitToScreen = function(imageView) {
    var percentShrink, imageDimensions = mmh.image.getDimensions(imageView);
    mmh.util.log('dims');
    mmh.util.log(imageDimensions);
    percentageShrink = parseInt((imageDimensions.width - 200) / imageDimensions.width);
    mmh.util.log(percentShrink);
    imageView.width = parseInt((percentShrink * imageDimensions.width), 10);
    imageView.height = parseInt((percentShrink * imageDimensions.height), 10);
    mmh.util.log(imageView.width);
    mmh.util.log(imageView.height);
    mmh.util.log(imageView);
    return imageView;
  };
  return {
    open: function () {
    //if(!mmh.user.hasContacts()) {
    //  winContacts.open();
    //  return;
    //}
      var hr, imageView, hintText, btnCancel, btnShare;
      mmh.util.log('opening share window');
      //image = 'http://s3.photos.jaisenmathai.com/original/201102/1298858393_IMAG0015.jpg';
      //imageView = fitToScreen(Ti.UI.createImageView({borderRadius:5,image:image,top:205}));
      imageView = Ti.UI.createImageView({left:10,right:10,borderRadius:5,image:image,top:205});
      //imageView.width = mmh.util.device.display.width - 20;

      /*btnCancel = mmh.ui.button.create('Cancel');
      btnCancel.addEventListener('click', function(){ mmh.util.log('opening home'); mmh.ui.window.animateTo(winShare, winHome); });
      btnCancel.top = 15;*/

      // TODO font size does not match
      textArea = mmh.ui.textArea.create({backgroundColor:'#fff',borderRadius:5,height:100, width:300, top:10,zIndex:998});
      hintText = mmh.ui.label.create('Enter a message (optional)', {top:-250,left:15,color:'#ccc',zIndex:999});
      textArea.addEventListener('focus', function(){ hintText.hide(); });
      textArea.addEventListener('blur', function(){ hintText.show(); });

      btnShare = mmh.ui.button.create('Share');
      btnShare.addEventListener('click', mmh.upload.post);
      btnShare.top = 135;

      //viewShare.add(btnCancel);
      viewShare.add(textArea);
      viewShare.add(hintText);
      viewShare.add(btnShare);
      viewShare.add(imageView);
      winShare.open();
      winShare.show();
    },
    camera: {
      failure: function(ev) {
        mmh.util.log('camera fiailure');
        mmh.util.log(ev);
        mmh.ui.window.animateTo(winHome, winShare);
      },
      success: function(ev) {
        image = ev.media;
        mmh.util.log(image);
        mmh.ui.window.animateTo(winHome, winShare);
      }
    },
    getImage: function() {
      return image;
    },
    getTextArea: function() {
      return textArea;
    }
  };
})();

winShare.addEventListener('open', jsShare.open);
