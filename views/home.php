<h1>Hi there</h1>
<?php if(!empty($children)) { ?>
  <ol>
    <?php foreach($children as $child) { ?>
      <li>
        <a href="/photos/source/<?php echo $child['c_id']; ?>"><?php echo $child['c_name']; ?></a>
        <?php foreach($child['photos'] as $photo) { ?>
          <img src="<?php echo !empty($photo['p_thumbPath']) ? ('/photos'.Photo::generateUrl($photo['p_basePath'], 200, 200, array(Photo::greyscale))) : '/img/processing.png'; ?>" hspace="4" vspace="4" />
        <?php } ?>
      </li>
    <?php } ?>
  </ol>
<?php } ?>
Click <a href="/child/new">here</a> to add a new child.
