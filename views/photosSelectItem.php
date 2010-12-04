<li>
  <div>
    <img src="'+el.thumbUrl+'" vspace="5" hspace="5">
  </div>
  <?php if(!$included) { ?>
    <a href="/photo/select/add/<?php echo $childId; ?>/'+el.internalId+'">Add this photo</a>
  <?php } ?>
</li>
