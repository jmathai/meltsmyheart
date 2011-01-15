<h2>This is what your post will look like</h2>
<div class="status fb-font">
  <div class="pic"><img src="<?php echo $photoUrl; ?>"></div>
  <div><strong>Your name</strong></div>
  <?php echo getstring('facebookStatus', $child); ?>
  <p>
    <strong><a href="<?php echo getConfig()->get('urls')->base . Child::getPageUrl($child); ?>"><?php echo getConfig()->get('urls')->base; ?></a></strong>
    <br/>
    <?php echo getString('facebookCaption'); ?>
    <br/><br/>
    <?php echo getString('facebookDescription', $child); ?>
  </p>
</div>

<button id="facebook-submit"><div>Post to Facebook</div></button>
<span> or <a href="#" id="facebook-cancel" class="close">Cancel</a></span>

<script>
  $("button#facebook-submit").click(
    function(ev){
      var btn = ev.target;
      $.post('/share/facebook/<?php echo $childId; ?>', {}, function(response) {
        if(mmh.ajax.isSuccess(response)) {
          mmh.displayConfirm("Your Facebook status has been updated!");
        } else {
          mmh.displayError("There was a problem updating your Facebook status.");
        }
        $("a#facebook-cancel").trigger('click');
      }, 'json');
    }
  );
</script>
