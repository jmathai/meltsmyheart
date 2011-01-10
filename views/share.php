Yay.

<ul>
<?php foreach($children as $child) { ?>
  <li><?php echo $child['c_name']; ?> [<a href="/share/facebook/<?php echo $child['c_id']; ?>">Share</a>]</li>
<?php } ?>
</ul>
