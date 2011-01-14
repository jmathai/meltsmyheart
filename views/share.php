<h1>Share with friends and family</h1>

<?php foreach($children as $child) { ?>
  <p>
    <h2>Share <?php echo posessive($child['c_name']); ?> page</h2>
    <a href="/share/facebook/<?php echo $child['c_id']; ?>" class="facebook overlay_target" rel="#modal"></a>
  </p>
<?php } ?>
