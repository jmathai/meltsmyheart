<h1>Welcome to <?php echo getConfig()->get('site')->name; ?></h1>
<?php if(!empty($children)) { ?>
  <p>
    You've currently added <?php echo $cntChildren; ?> of your children.
    <?php foreach($children as $child) { ?>
      <h2 class="child-name"><?php echo $child['c_name']; ?></h2>
      <p>
        <?php if(count($child['photos']) > 0) { ?>
          <ul class="home-photos">
          <?php foreach($child['photos'] as $num => $photo) { ?>
            <li><img src="<?php echo !empty($photo['p_thumbPath']) ? ('/photos'.Photo::generateUrl($photo['p_basePath'], 50, 50, array(Photo::square))) : '/img/processing.png'; ?>" class="ridge-sm" /></li>
            <?php if($num >= 11) break; ?>
          <?php } ?>
          </ul>
          <br>
          <form action="/photos/source/<?php echo $child['c_id']; ?>"><button class="yellow" type="submit"><div>Add photos</div></button></form>
          <form action="<?php echo Child::getPageUrl($child); ?>"><button class="yellow"><div>View page</div></button></form>
          <!-- <?php echo $child['c_name']; ?> (<a href="/photos/source/<?php echo $child['c_id']; ?>">Add photos</a> or <a href="/child/<?php echo $child['c_domain']; ?>">view page</a>)-->
        <?php }else{ ?>
          
        <?php } ?>
      </p>
    <?php } ?>
  </p>
<?php } ?>
