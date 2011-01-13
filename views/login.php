<h1>Login to your account</h1>

<p>
  <form method="post" id="loginForm">
    <label for="email">Email</label>
    <input type="email" name="email" required="required" />

    <label for="password">Password</label>
    <input type="password" name="password" required="required">

    <input type="hidden" name="r" value="<?php echo $r; ?>">
    <button type="submit">Login</button>
  </form>
</p>
