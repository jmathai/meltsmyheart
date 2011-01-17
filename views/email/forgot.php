<p>
Someone (hopefully you) requested a link to reset your password. To do so you can click the link below.
</p>

<a href="<?php echo getConfig()->get('urls')->base; ?>/reset/<?php echo $email; ?>/<?php echo $token; ?>">
<?php echo getConfig()->get('urls')->base; ?>/reset/<?php echo $email; ?>/<?php echo $token; ?>
</a>

<p>
If you didn't request to reset your password then simply ignore this email.
</p>

<p>
<?php echo nl2br(getConfig()->get('email')->signature); ?>
</p>
