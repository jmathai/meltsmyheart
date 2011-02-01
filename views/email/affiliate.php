<p>
Welcome to the <?php echo getConfig()->get('site')->name; ?> affiliate program. We hope you have fun earning money by helping us spread the word.
</p>

<p>
You can monitor your affiliate account by going to <?php printf('%s/affiliate', getConfig()->get('urls')->base); ?>.
</p>

<p>
If you have any questions feel free to send an email to <?php echo getConfig()->get('email')->from_email; ?>. 
</p>

<?php echo nl2br(getConfig()->get('email')->signature); ?>
