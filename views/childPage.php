<h1><?php echo $child['c_name']; ?><h1>
<h3>Born <?php echo date('l jS \of F', $child['c_birthdate']); ?></h3>
<?php foreach($photos as $week => $weekPhotos) { ?>
  <h5><?php echo $child['c_name']; ?> week <?php echo $week; ?></h5>
  <?php foreach($weekPhotos as $photo) { ?>
    <img src="/photos<?php echo $photo['p_thumbPath'] ?>" hspace="5" vspace="5">
  <?php } ?>
<?php } ?>