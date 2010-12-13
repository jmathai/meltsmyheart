<li>
  <div>
    <img src="'+el.thumbUrl+'" vspace="5" hspace="5">
  </div>
  <?php if(!$included) { ?>
    <?php getTemplate()->display('partials/photoSelectItemAction.php', array('childId' => $childId, 'action' => 'add', 'label' => 'Add this photo')); ?>
  <?php } else { ?>
    <?php getTemplate()->display('partials/photoSelectItemAction.php', array('childId' => $childId, 'action' => 'remove', 'label' => 'Remove this photo')); ?>
  <?php } ?>
</li>
