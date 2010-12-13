<?php if(!empty($photos)) { ?>
  <ul>
    <?php foreach($photos as $photo) { ?>
      <li>
        <div>
          <img src="<?php echo $photo['thumbUrl']; ?>" vspace="5" hspace="5">
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
