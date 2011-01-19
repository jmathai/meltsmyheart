<html lang="en">
<head>
  <title><?php echo sprintf("%s's page on %s", $child['c_name'], getConfig()->get('site')->name); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo getAsset('css', array('page.css')); ?>">
  <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div id="header">
    <div class="container">
      <h1><?php echo posessive($child['c_name']); ?> page<h1>
      <h2>Born <?php echo date('l jS \of F', $child['c_birthdate']); ?></h2>
      <a href="<?php echo getConfig()->get('urls')->base; ?>"><img src="/img/page-logo.png"></a>
    </div>
  </div>
  <div id="header-bar"></div>
  <div class="container">
    <div id="content">
      <?php getTemplate()->display('childPage.php', array('child' => $child, 'photos' => $photos)); ?>
    </div>
  </div>
  <div id="footer">
    <hr>
    <div class="container">&copy; <?php echo date('Y'); ?> <?php echo getConfig()->get('site')->name; ?></div>
  </div>
  <script src="<?php echo getAsset('js', array('jquery.min.js', 'plugins/jquery.lightbox-0.5.min.js','page.js')); ?>"></script>
</body>
</html>
