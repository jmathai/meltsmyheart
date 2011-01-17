<h1>Share with friends and family</h1>

<p>
  Easily share with your friends on Facebook. Just click the child's name you'd like to share.
</p>

<?php foreach($children as $child) { ?>
  <p>
  <a href="/share/facebook/<?php echo $child['c_id']; ?>" class="overlay_target" rel="#modal"><button><div><?php echo $child['c_name']; ?></div></button></a>
  </p>
<?php } ?>
