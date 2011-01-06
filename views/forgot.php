<?php if($confirm == 'confirm') { ?>
  <h1>Please check your email for a link to reset your password.</h1>
<?php } else { ?>
  <h1>Forgot your password?</h1>
  <form method="post">
    <input type="text" name="email"><br>
    <br>
    <button type="submit">Reset</button>
  </form>
<?php } ?>
