<h2>Select photos for <?php echo posessive($child['c_name']); ?> page</h2>
<?php if(!empty($photos)) { ?>
  <ul id="photo-list">
    <?php foreach($photos as $photo) { ?>
      <li>
        <div>
          <img src="<?php echo $photo['thumbUrl']; ?>" <?php if(isset($ids[$photo['internalId']])) { ?> class="selected"<?php } ?>>
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
<button class="close"><div>Close</div></button>
<form action="<?php echo Child::getPageUrl($child); ?>">
  <button type="submit" id="button-view-page"><div>View <?php echo posessive($child['c_name']); ?> page</div></button>
</form>
