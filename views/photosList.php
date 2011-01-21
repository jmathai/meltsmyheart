<h2>Select photos for <?php echo posessive($child['c_name']); ?> page</h2>
<?php if(!empty($photos)) { ?>
  <ul id="photo-list">
    <?php foreach($photos as $photo) { ?>
      <li>
        <div>
          <img src="<?php echo $photo['thumbUrl']; ?>" class="frame-polaroid">
        </div>
        <?php if(isset($ids[$photo['internalId']])) { ?>
          <?php getTemplate()->display('partials/photoSelectItemAction.php', array('childId' => $childId, 'photoId' => $photo['internalId'], 'action' => 'remove')); ?>
        <?php } else { ?>
          <?php getTemplate()->display('partials/photoSelectItemAction.php', array('childId' => $childId, 'photoId' => $photo['internalId'], 'action' => 'add')); ?>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
<?php } ?>
<button class="close"><div>Continue</div></button>
