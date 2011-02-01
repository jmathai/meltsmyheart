<p>
Congratulations! One of your referals upgraded their <?php echo getConfig()->get('site')->name; ?> account.
</p>

<p>
You can monitor your affiliate account by going to <?php printf('%s/affiliate', getConfig()->get('urls')->base); ?>.
</p>

<p>
If you have any questions feel free to send an email to <?php echo getConfig()->get('email')->from_email; ?>. 
</p>

<?php echo nl2br(getConfig()->get('email')->signature); ?>
