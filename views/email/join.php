<p>
Thank you for signing up! We hope you love creating memorable pages of your children using <?php echo getConfig()->get('site')->name; ?>.
</p>

<p>
You signed up with <?php echo $email; ?>.
</p>

<p>
If you have any questions feel free to send an email to <?php echo getConfig()->get('email')->from_email; ?>. 
</p>

<?php echo nl2br(getConfig()->get('email')->signature); ?>
