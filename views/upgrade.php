<h1>You need to upgrade for that</h1>

<p>
  You can purchase a subscription for under $10 per year. Here's a list of features you get by upgrading <?php echo getConfig()->get('site')->name; ?>.
</p>

<h2>What you get for upgrading</h2>

<ul class="upgrade">
  <li>Create unlimited pages for your children</li>
  <li>A family page that showcases each child's page <sup>*</sup></li>
  <li>Additional premium themes and photo filters <sup>*</sup></li>
  <li>Top level domain for your child (i.e. www.childsname.com)<sup>*</sup></li>
</ul>

<p>
  <form action="<?php echo getConfig()->get('thirdparty')->paypal_host; ?>/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="hosted_button_id" value="<?php echo getConfig()->get('thirdparty')->paypal_button_id; ?>">
  <button type="submit"><div>Upgrade now</div></button>
  <img alt="" border="0" src="<?php echo getConfig()->get('thirdparty')->paypal_host; ?>/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
</p>

<p>
  <sup>*</sup> We just launched and are working hard on building out paid premium features!
</p>
