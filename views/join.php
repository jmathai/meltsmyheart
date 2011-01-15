<h1>Getting started is easy</h1>

<div class="clearfix">
  <?php getTemplate()->display('partials/paragraphRight.php'); ?>

  <p>
    <form method="post" id="joinForm">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required="required">

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required="required">

      <label for="passwordConfirm">Confirm password</label>
      <input type="password" id="passwordConfirm" name="passwordConfirm" required="required" data-equals="password">

      <button type="submit">Join us</button>
      <br>
      <em>Already have an account? <a href="/login">Sign in</a>.</em>
    </form>
  </p>
</div>
