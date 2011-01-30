<h1>Getting started is easy</h1>

<div class="clearfix">
  <?php getTemplate()->display('partials/paragraphRight.php', array('context' => $context)); ?>

  <p class="left">
    <form method="post" id="joinForm" action="/join">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required="required" tabindex="1">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required="required" tabindex="2">

      <label for="passwordConfirm">Confirm password</label>
      <input type="password" id="passwordConfirm" name="passwordConfirm" required="required" data-equals="password" tabindex="3">
      <input type="hidden" name="r" value="<?php echo $r; ?>">
      <input type="hidden" name="context" value="<?php echo $context; ?>">

      <button type="submit" tabindex="4">Join us</button>
      <br>
      <em>Already have an account? <a href="/login?r=<?php echo $r; ?>" tabindex="5">Sign in</a>.</em>
    </form>
  </p>
</div>
