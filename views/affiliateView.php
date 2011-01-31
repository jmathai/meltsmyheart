<h1>Your affiliate account</h1>

<p>
  Thanks for being an affiliate of <?php echo getConfig()->get('site')->name; ?>.
  From here you can get your <a href="#link">affiliate link</a>, <a href="#statistics">view statistics</a> and <a href="#payment">request a payment</a>.
</p>

<a name="link"></a>
<h2>Get your affiliate link</h2>
<p>
  Use your affiliate link to refer users to <?php echo getConfig()->get('site')->name; ?> and earn money when they sign up for a paid account. 
  Just follow the instructions below to embed the link into your site.
</p>
<p>
  Your affiliate link is <strong><em><?php printf('%s/a/%s', getConfig()->get('urls')->base, $affiliate['a_key']); ?></em></strong>. Use it anyway you wish but a simple example is below.
</p>
<pre>&lt;a href="<?php printf('%s/a/%s', getConfig()->get('urls')->base, $affiliate['a_key']); ?>"&gt;
  Enter text or image tag here
&lt;/a&gt;</pre>

<a name="statistics"></a>
<h2>View your statistics</h2>
<p>
  Quickly see how many people clicked through from your page, what percentage signed up and how many upgraded.
</p>

<a name="payment"></a>
<h2>Request a payment</h2>
<p>
  You can request a payment at any time.
  Fill out this form below and we'll mail you a check.
</p>
<p>
  <form method="post" action="/affiliate/payment" id="affiliateForm">
    <label for="name">Amount <em>(Your balance is $<?php echo number_format($balance, 2); ?>)</em></label>
    <input type="text" id="name" name="name" required="required">

    <label for="name">Name</label>
    <input type="text" id="name" name="name" required="required">

    <label for="name">Address</label>
    <input type="text" id="address" name="address" required="required">

    <label for="name">City, State Zip</label>
    <input type="text" id="citystatezip" name="citystatezip" required="required">

    <button type="submit"><div>Send payment</div></button>
  </form>
</p>
