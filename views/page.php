<html lang="en">
<head>
  <title><?php echo sprintf("%s's page on %s", $child['c_name'], getConfig()->get('site')->name); ?></title>
  <link rel="stylesheet" type="text/css" href="/css/page.css">
  <link href="http://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here|Unkempt|Covered+By+Your+Grace" rel="stylesheet" type="text/css">
  <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div id="container">
    <div id="header">
    </div>
    <div id="content">
      <?php getTemplate()->display('childPage.php', array('child' => $child, 'photos' => $photos)); ?>
    </div>
    <div id="footer">
    </div>
  </div>
  <script src="/js/jquery.js"></script>
  <script src="/js/page.js"></script>
</body>
</html>
