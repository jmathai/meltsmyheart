<h1><?php echo posessive($child['c_name']); ?> page<h1>
<h2>Born <?php echo date('l jS \of F', $child['c_birthdate']); ?></h2>
<div id="child" class="clearfix">
  <ul>
    <?php foreach($photos as $photo) { ?>
      <li>
        <div>
          <img src="/photos<?php echo Photo::generateUrl($photo['p_basePath'], 200, 200, array(Photo::square, Photo::contrast)); ?>" title="<?php echo date('Y-m-d', $photo['p_dateTaken']); ?>">
          <label><?php echo sprintf('%s old', displayAge($child['c_birthdate'], $photo['p_dateTaken'])); ?></label>
        </div>
      </li>
    <?php } ?>
  </ul>
</div>
