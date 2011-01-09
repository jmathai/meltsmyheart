<html lang="en">
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
  <ul id="navigation">
    <li><a href="/">Home</a></li>
    <li><a href="/child/new">New Child</a></li>
    <li><a href="/login">Login</a> or <a href="/join">join</a></li>
    <li>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="hosted_button_id" value="<?php echo getConfig()->get('thirdparty')->paypal_button_id; ?>">
      <input type="image" src="/img/upgrade.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
      </form>
    </li>
    <li><a href="/logout">Logout</a></li>
  </ul>
  <?php include $body; ?>
  <ul>
    <li>Footer goes here</li.
  </ul>
  <script src="/js/jquery.js"></script>
  <script src="/js/swfupload.js"></script>
  <script src="/js/plugins/swfupload.queue.js"></script>
  <script src="/js/javascript.js"></script>
  <?php if(isset($javascript)) { ?>
    <script>
      <?php echo $javascript; ?>
    </script>
  <?php } ?>
</body>
</html>
