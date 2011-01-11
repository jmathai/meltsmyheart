<h1>Welcome to <?php echo getConfig()->get('site')->name; ?></h1>
<?php if(!empty($children)) { ?>
  <p>
    You've currently added <?php echo $cntChildren; ?> of your children.
    <?php foreach($children as $child) { ?>
      <h2 class="child-name"><?php echo $child['c_name']; ?></h2>
      <p>
        <ul class="home-photos">
        <?php foreach($child['photos'] as $num => $photo) { ?>
          <li><img src="<?php echo !empty($photo['p_thumbPath']) ? ('/photos'.Photo::generateUrl($photo['p_basePath'], 50, 50, array(Photo::square))) : '/img/processing.png'; ?>" class="ridge-sm" /></li>
          <?php if($num >= 11) break; ?>
        <?php } ?>
        </ul>
        <br>
        <button class="yellow"><div>Add photos</div></button>
        <button class="yellow"><div>View page</div></button>
        <!-- <?php echo $child['c_name']; ?> (<a href="/photos/source/<?php echo $child['c_id']; ?>">Add photos</a> or <a href="/child/<?php echo $child['c_domain']; ?>">view page</a>)-->
      </p>
    <?php } ?>
  </p>
<?php } ?>
