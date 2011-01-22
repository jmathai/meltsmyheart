<h1>Welcome to <?php echo getConfig()->get('site')->name; ?></h1>
<?php if(!empty($children)) { ?>
  <p>
    You've currently added <?php echo $cntChildren; ?> of your children.
    <?php foreach($children as $child) { ?>
      <div class="h2 child-name"><?php echo $child['c_name']; ?></div>
      <p>
        <?php if(count($child['photos']) > 0) { ?>
          <ul class="home-photos">
          <?php foreach($child['photos'] as $num => $photo) { ?>
            <li>
              <?php if(!empty($photo['p_basePath'])) { ?>
                <img src="<?php echo Photo::generateUrl($photo['p_basePath'], 100, 100, array(Photo::square)); ?>" class="frame-polaroid" title="<?php echo quoteEncode($child['c_name']) . ' at ' . displayAge($child['c_birthdate'], $photo['p_dateTaken']); ?> old."></li>
              <?php } else { ?>
                <img src="/img/logo-heart-100.png" class="frame-polaroid" title="Processing..."></li>
              <?php } ?>
            <?php if($num >= 11) break; ?>
          <?php } ?>
          </ul>
          <br>
          <form action="/photos/source/<?php echo $child['c_id']; ?>"><button class="yellow" type="submit"><div>Add photos</div></button></form>
          <form action="<?php echo Child::getPageUrl($child); ?>"><button class="yellow"><div>View page</div></button></form>
        <?php }else{ ?>
          You haven't added any photos of <?php echo $child['c_name']; ?> yet.
          Click the button below to get started.
          <form action="/photos/source/<?php echo $child['c_id']; ?>"><button class="yellow" type="submit"><div>Add photos</div></button></form>
        <?php } ?>
      </p>
      <hr>
    <?php } ?>
  </p>
<?php } else { ?>
  <h2>You haven't added any of your children</h2>
  <p>
    It's easy to get started. Click the button below to add a child.
    <form action="/child/new"><button type="submit" class="yellow"><div>Add child</div></button></form>
  </p>
<?php } ?>
