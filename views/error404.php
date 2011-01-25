<?php if($ajax) { ?>
  <h1>One of our children must have deleted the page you were looking for</h1>
<?php } else { ?>

<?php } ?>

<p>
The page you were looking for could not be found. 
Usually this is because you followed a bad link. 
If you believe this is a problem with the site then send an email to <?php echo emailLink(); ?>
</p>

<p>
  <img src="/img/creative/polaroid-3-500-1.jpg" class="auto-500">
</p>

<?php if(isset($ajax) && $ajax) { ?>
  <button class="close"><div>Close</div></button>
<?php } ?>
