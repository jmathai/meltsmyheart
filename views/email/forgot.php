<!--html-->
<b>
<!--/html-->
Someone (hopefully you) requested a link to reset your password. To do so you can click the link below.
<!--html-->
</b>
<!--/html-->

<?php echo getConfig()->get('urls')->base; ?>/reset/<?php echo $email; ?>/<?php echo $token; ?>

If you didn't request to reset your password then simply ignore this email.

<?php echo getConfig()->get('email')->signature; ?>
