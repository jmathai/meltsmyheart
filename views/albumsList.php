<h1>Select photos from <?php echo $service; ?></h1>
<ul id="album-list">
  <li>Your albums</li>
  <?php foreach($albums as $album) { ?>
    <li><img src="<?php echo $album['cover']; ?>"><br/><a href="<?php echo $album['link']; ?>" class="album"><?php echo $album['name']; ?></a></li>
  <?php } ?>
</ul>
<div id="preview"></div>
<br clear="all">
