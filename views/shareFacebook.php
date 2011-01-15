<div class="status parse fb-font">
  <div class="pic"><img src="<?php echo $photoUrl; ?>"></div>
  <strong><fb:name uid="<?php echo $uid; ?>" useyou="false" linked="true"></fb:name></strong>
  <?php echo getstring('opportunity_status'); ?>
  <p>
    <strong><a href="<?php echo getConfig()->get('urls')->base; ?>"><?php echo getConfig()->get('urls')->base; ?></a></strong>
    <br/>
    <?php echo getString('opportunity_status_link_caption'); ?>
    <br/><br/>
    <?php echo getString('opportunity_status_link_description'); ?>
  </p>
</div>
