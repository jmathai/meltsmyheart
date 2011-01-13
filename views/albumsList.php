<h1>Select photos for <?php echo posessive($child['c_name']); ?> page</h1>

<p>
  Select an album to view the photos you'd like to select.
</p>

<p>
  <h2>Select an album</h2>
  <a class="<?php echo $service; ?>"></a>
  <ul id="album-list">
    <?php foreach($albums as $album) { ?>
      <li>
        <a href="<?php echo $album['link']; ?>" class="album"><img src="<?php echo $album['cover']; ?>"></a>
        <br>
        <a href="<?php echo $album['link']; ?>" class="album"><?php echo $album['name']; ?></a>
      </li>
    <?php } ?>
  </ul>
  <br clear="all">
</p>
