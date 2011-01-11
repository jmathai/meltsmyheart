<html lang="en">
<head>
  <title></title>
  <link rel="stylesheet" type="text/css" href="/css/styles.css">
</head>
<body>
  <div id="header">
    <div class="container">
      <a href="/" title="<?php echo getConfig()->get('site')->name; ?>"><img src="/img/logo.png" id="logo" alt="<?php echo getConfig()->get('site')->name; ?>"></a>
      <ul id="navigation">
        <li><a href="/child/new" class="add-child"><div></div>Add Child</a></li>
        <li><a href="/share" class="share"><div></div>Share</a></li>
        <li><a href="/upgrade" class="upgrade"><div></div>Upgrade</a></li>
        <!--<li><a href="/login">Login</a> or <a href="/join">join</a></li>-->
        <!--<li><a href="/logout">Logout</a></li>-->
      </ul>
    </div>
  </div>
  <div id="header-bar"></div>
  <div id="content" class="container">
    <?php include $body; ?>
  </div>
  <div id="footer" class="container">
    <ul>
      <li>Footer goes here</li.
    </ul>
  </div>
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
