var winShare, viewShare, jsShare, viewShareContainer, winShareOpened = false;
viewShareContainer = mmh.ui.view.create(mmh.constant('viewContainer'));
viewShare = mmh.ui.view.create(mmh.constant('viewContent'));

viewShareContainer.add(mmh.ui.view.create({height:50}));
viewShareContainer.add(viewShare);
winShare = mmh.ui.window.create('Share This Photo', viewShareContainer);

jsShare = (function() {
  var image;
  return {
    open: function () {
      var imageView, btnCancel, btnShare;
      mmh.util.log('opening share window');
      imageView = Titanium.UI.createImageView({left:10,right:10,image:image,top:125,backgroundColor:'#000'});
      btnShare = mmh.ui.button.create('Share');
      btnShare.addEventListener('click', mmh.upload.post);
      btnShare.top = 15;
      btnCancel = mmh.ui.button.create('Cancel');
      btnCancel.addEventListener('click', function(){ mmh.util.log('opening home'); mmh.ui.window.animateTo(winShare, winHome); });
      btnCancel.top = 70;
      viewShare.add(btnShare);
      viewShare.add(btnCancel);
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
    }
  };
})();

winShare.addEventListener('open', jsShare.open);
