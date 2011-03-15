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
      var hr, btnCancel, btnShare;
      mmh.util.log('opening share window');

      textArea = mmh.ui.textArea.create({backgroundColor:'#fff',hintText:'Enter a message (optional)',borderRadius:5,height:100, width:300, top:10});

      btnShare = mmh.ui.button.create('Share');
      btnShare.addEventListener('click', mmh.upload.post);
      btnShare.top = 135;

      contactList = new contactList(viewShare, {top:180});
      contactList.render();

      viewShare.add(textArea);
      viewShare.add(btnShare);

      mmh.ui.window.openAndShow(winShare);
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
