<li>
  <div>
    <img src="'+el.thumbUrl+'" vspace="5" hspace="5">
  </div>
  <?php if(!$included) { ?>
    <?php getTemplate()->display('partials/photoSelectionItemAction.php', array('service' => $service, 'childId' => $childId, 'action' => 'add', 'label' => 'Add this photo')); ?>
  <?php } else { ?>
    <?php getTemplate()->display('partials/photoSelectionItemAction.php', array('service' => $service, 'childId' => $childId, 'action' => 'remove', 'label' => 'Remove this photo')); ?>
  <?php } ?>
</li>
