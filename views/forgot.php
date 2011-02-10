<?php if($confirm == 'confirm') { ?>
  <h1>Please check your email for a link to reset your password.</h1>
  
  <p>
    An email has been sent to your address.
    It contains a link to reset your password.
    We'll wait right for you to check your email and come back.
  </p>

  <p>
    If you don't receive an email then send us an email at <?php echo emailLink(); ?>.
  </p>

  <p>
    <img src="/img/creative/polaroid-3-500-1.jpg" class="auto-500">
  </p>
<?php } else { ?>
  <h1>Forgot your password?</h1>

  <div class="clearfix">
    <?php getTemplate()->display('partials/paragraphRight.php'); ?>
    
    <p>
      <form method="post" id="forgotForm">
        <div data-role="fieldcontain">
          <label for="email">What's your email address?</label>
          <input type="email" id="email" name="email" required="required" tabindex="1">
        </div>

        <button type="submit" data-theme="b"><div>Reset</div></button>
        <br>
        <em>Need an account? <a href="/join" tabindex="2">Join here</a>.</em>
        <br>
        <em>Already have an account? <a href="/login" tabindex="3">Sign in</a>.</em>
      </form>
    </p>
  </div>
<?php } ?>
