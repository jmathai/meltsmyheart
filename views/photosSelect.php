<h1>Select photos from <?php echo $service; ?></h1>
<div id="preview"></div>
<ul>
  <?php foreach($albums as $album) { ?>
    <li><img src="<?php echo $album['cover']; ?>"><br/><a href="/proxy/p/<?php echo $service; ?>/<?php echo $album['id']; ?>/photos" class="album"><?php echo $album['name']; ?></a></li>
  <?php } ?>
</ul>