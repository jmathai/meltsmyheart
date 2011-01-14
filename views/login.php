<h1>Login to your account</h1>

<?php getTemplate()->display('partials/paragraphRight.php'); ?>

<p>
  <form method="post" id="loginForm">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required="required" />

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required="required">

    <input type="hidden" name="r" value="<?php echo $r; ?>">
    <button type="submit">Login</button>
    <br>
    <em>Need an account? <a href="/join">Join here</a>.</em>
    <br>
    <em>Forgot your password? <a href="/forgot">Reset it here</a>.</em>
  </form>
</p>
