<html lang="en">
<head>
  <title><?php echo sprintf("%s's page on %s", $child['c_name'], getConfig()->get('site')->name); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo getAsset('css', array('plugins/jquery.lightbox-0.5.css',"theme-{$theme['t_name']}.css")); ?>">
  <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div id="header">
    <div class="container">
      <h1><?php echo posessive($child['c_name']); ?> page<h1>
      <h2>Born <?php echo date('l jS \of F', $child['c_birthdate']); ?></h2>
      <a href="<?php echo getConfig()->get('urls')->base; ?>"><div></div></a>
    </div>
  </div>
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
  <script>
    $(document).ready(function() {
      var _gaq = _gaq || [], mpq = [];
      $("a.child-photo").lightBox();
      mpq.push(["init", "<?php echo getSecret('mp_token'); ?>"]);
      mpq.push(["track", "page-view", {"path": "<?php echo normalizeRoute(getRoute()->route()); ?>"}]); 
      mpq.push(["track", "<?php echo normalizeRoute(getRoute()->route()); ?>"]); 
      <?php if(getConfig()->get('site')->mode == 'dev') { ?>
        mpq.push(["set_config", {"test": true}]);
      <?php } ?>
      (function() {
        var mp = document.createElement("script"); mp.type = "text/javascript"; mp.async = true;
        mp.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') + "//api.mixpanel.com/site_media/js/api/mixpanel.js";
        var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(mp, s);
      })(); 
    });
  </script>
</body>
</html>
