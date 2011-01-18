<h1>Select photos for <?php echo posessive($child['c_name']); ?> page</h1>

<p>
  Select an album to view the photos you'd like to select.
  <form action="<?php echo Child::getPageUrl($child); ?>">
    <button type="submit"<?php if($photoCount == 0) { ?> id="button-view-page"<?php } ?>><div>View page</div></button>
  </form>
</p>

<h2>Select an album</h2>
<p>
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
