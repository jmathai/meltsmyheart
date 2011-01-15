<h1>Reset your password</h1>

<div class="clearfix">
  <?php getTemplate()->display('partials/paragraphRight.php'); ?>

  <p>
    <form method="post">
      <label for="password">New password</label>
      <input type="password" name="password" required="required">

      <label for="confirmpassword">Confirm new password</label>
      <input type="password" name="confirmpassword" required="required" data-equals="password">

      <button type="submit">Join us</button>
      <br>
      <em>Need an account? <a href="/join">Join here</a>.</em>
      <br>
      <em>Already have an account? <a href="/login">Sign in</a>.</em>
    </form>
  </p>
</div>
