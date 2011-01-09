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
  <input type="image" src="/img/upgrade.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
</p>
