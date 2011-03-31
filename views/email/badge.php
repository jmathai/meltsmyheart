<h2>Congratulations!<div style="font-size:smaller; color:#bbb;">...from the <?php echo getConfig()->get('site')->name; ?> team</div></h2>
<p>
<strong><?php echo ucwords($childName); ?></strong> received the "<strong><em><?php echo $badgeName; ?></em></strong>" badge.
</p>
<hr style="border:0; height:1px; background-color:#bbb;">
<?php echo getTemplate()->display('email/badge-detail.php'); ?>
<hr style="border:0; height:1px; background-color:#bbb;">
<p style="color:#bbb; font-size:smaller;">
-- View <?php echo posessive($childName); ?> page at <?php echo $pageUrl; ?>
</p>

