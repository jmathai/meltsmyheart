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
            <li><img src="<?php echo !empty($photo['p_thumbPath']) ? Photo::generateUrl($photo['p_basePath'], 100, 100, array(Photo::square)) : '/img/processing.png'; ?>" class="frame-polaroid" title="<?php echo quoteEncode($child['c_name']) . ' at ' . displayAge($child['c_birthdate'], $photo['p_dateTaken']); ?> old."/></li>
            <?php if($num >= 11) break; ?>
          <?php } ?>
          </ul>
          <br>
          <form action="/photos/source/<?php echo $child['c_id']; ?>"><button class="yellow" type="submit"><div>Add photos</div></button></form>
          <form action="<?php echo Child::getPageUrl($child); ?>"><button class="yellow"><div>View page</div></button></form>
        <?php }else{ ?>
           
        <?php } ?>
      </p>
    <?php } ?>
  </p>
<?php } else { ?>

<?php } ?>
