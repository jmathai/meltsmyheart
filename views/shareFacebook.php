<div class="status parse fb-font">
  <div class="pic"><fb:profile-pic uid="<?php echo $uid; ?>" size="square" linked="false"></fb:profile-pic></div>
  <strong><fb:name uid="<?php echo $uid; ?>" useyou="false" linked="true"></fb:name></strong>
  <?php echo getstring('opportunity_status'); ?>
  <p>
    <strong><a href="<?php echo APP_URL; ?>"><?php echo APP_URL; ?></a></strong>
    <br/>
    <?php echo getString('opportunity_status_link_caption'); ?>
    <br/><br/>
    <?php echo getString('opportunity_status_link_description'); ?>
  </p>
</div>
