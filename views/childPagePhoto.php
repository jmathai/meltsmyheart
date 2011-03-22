<div id="child-photo" style="text-align:center; margin:100px 0 50px 0;">
  <img src="<?php echo Photo::generateUrl($photo['p_basePath'], 800, 600, array(Photo::contrast)); ?>" style="border:20px solid #fff; border-bottom:60px solid #fff;">
  <p style="margin-top:-40px;">
    Taken on <?php echo date('l jS \of F \a\t g:i a', $photo['p_dateTaken']); ?> - <?php echo sprintf('%s old', displayAge($child['c_birthdate'], $photo['p_dateTaken'])); ?>
  </p>
</div>
