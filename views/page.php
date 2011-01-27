<html lang="en">
<head>
  <title><?php echo sprintf("%s's page on %s", $child['c_name'], getConfig()->get('site')->name); ?></title>
  <?php if($isOwner) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo getAsset('css', getAssetCssMember()); ?>">
  <?php } ?>
  <link rel="stylesheet" type="text/css" href="<?php echo getAsset('css', $theme['css']); ?>">
  <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div id="header">
    <div class="container">
      <h1><?php echo posessive($child['c_name']); ?> page<h1>
      <h2>Born <?php echo date('l jS \of F', $child['c_birthdate']); ?></h2>
      <a href="<?php echo getConfig()->get('urls')->base; ?>"><div></div></a>
      <?php if($isOwner) { ?>
        <div id="nav-owner">
          <button id="child-page-edit" href="/child/page/customize/<?php echo $child['c_id']; ?>"><div>Customize</div></button>
          <form action="<?php echo getConfig()->get('urls')->base; ?>/photos/source/<?php echo $child['c_id']; ?>"><button type="submit"><div>Add photos</div></button>
        </div>
        <?php } ?>
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
  <?php if($isOwner) { ?>
    <div id="modal" class="apple_overlay modal"></div>
    <div id="modal-wide" class="apple_overlay modal-wide"></div>
    <div id="message" class="ui-state-highlight"></div>
    <div id="error" class="ui-state-error ui-state-error-text"></div>
    <div id="tooltip"></div>
    <script src="<?php echo getAsset('js', getAssetJsMember()); ?>"></script>
  <?php } else { ?>
    <script src="<?php echo getAsset('js', getAssetJsVisitor()); ?>"></script>
  <?php } ?>
  <script>
    var _gaq = _gaq || [], mpq = [];
    $(document).ready(function() {
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
      <?php if(isset($js)) { ?>
        <?php echo $js; ?>
      <?php } ?>
    });
  </script>
</body>
</html>
