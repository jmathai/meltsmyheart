var winShare, viewShare, jsShare, viewShareContainer, winShareOpened = false;
viewShareContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewShare = mmh.ui.scrollView.create(mmh.constant('viewContent'));

viewShareContainer.add(mmh.ui.view.create({height:50}));
viewShareContainer.add(viewShare);
winShare = mmh.ui.window.create('Share This Photo', viewShareContainer);

jsShare = (function() {
  var image, textArea;
  var fitToScreen = function(imageView) {
    mmh.util.log(imageView);
    var percentShrink, imageDimensions = mmh.image.getDimensions(imageView);
    percentShrink = parseInt((imageDimensions.width / mmh.util.device.display.width), 10);
    imageView.width = parseInt((percentShrink * imageDimensions.width), 10);
    imageView.height = parseInt((percentShrink * imageDimensions.height), 10);
    //left:10,right:10,image:image,top:125,
    imageView.top = 125;
    mmh.util.log(imageView);
    return imageView;
  };
  return {
    open: function () {
      var hr, imageView, hintText, btnCancel, btnShare;
      mmh.util.log('opening share window');
      //image = 'http://s3.photos.jaisenmathai.com/original/201102/1298858393_IMAG0015.jpg';
      imageView = Ti.UI.createImageView({left:10,right:10,image:image,top:225});
      //imageView.width = mmh.util.device.display.width - 20;

      btnCancel = mmh.ui.button.create('Cancel');
      btnCancel.addEventListener('click', function(){ mmh.util.log('opening home'); mmh.ui.window.animateTo(winShare, winHome); });
      btnCancel.top = 15;

      hr = mmh.ui.hr.create();

      // TODO font size does not match
      textArea = mmh.ui.textArea.create({backgroundColor:'#fff',borderRadius:5,height:100, width:300, top:70});
      hintText = mmh.ui.label.create('Enter a message (optional)', {top:-250,left:15,color:'#ccc'});
      textArea.addEventListener('focus', function(){ hintText.hide(); });
      textArea.addEventListener('blur', function(){ hintText.show(); });

      btnShare = mmh.ui.button.create('Share');
      btnShare.addEventListener('click', mmh.upload.post);
      btnShare.top = 180;

      viewShare.add(btnCancel);
      viewShare.add(hr);
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
