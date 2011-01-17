<h1>Everyone participating in the beta is upgraded for free!</h1>

<p>
  As our way of saying thanks for participating in our beta we're giving everyone a premium account for free.
  You'll have access to all the features of <?php echo getConfig()->get('site')->name; ?> for life.
</p>

<h2>Enjoy!</h2>

<?php return; ?>

<h1>The perks of upgrading</h1>

<p>
  <span class="first">T</span>here are two options for upgrading.
  You can purchase a subscription for under $10 per year or invite 5 friends to join <?php echo getConfig()->get('site')->name; ?>.
</p>

<p>
  Pick the one that's right for you.
</p>

<p>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="hosted_button_id" value="<?php echo getConfig()->get('thirdparty')->paypal_button_id; ?>">
  <button type="submit"><div>Upgrade now</div></button>
  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
</p>
