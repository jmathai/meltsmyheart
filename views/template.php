<html lang="en">
<head>
  <title><?php echo getConfig()->get('site')->name; ?> - An easy and beautiful way to share photos of your children</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="description" content="The easiest most beautiful way to share photos of your children. Let us automatically group your child's photos by age and apply filters to make your photos look even better.">
  <meta name="Child photos, children photos, baby photos, share baby photos, share children photo, print baby photos.">
  <meta name="robots" content="index, follow" />
  <link rel="stylesheet" type="text/css" href="<?php echo getAsset('css', getAssetCssMember()); ?>">
  <!--[if IE]>
  <link rel="stylesheet" type="text/css" href="/css/ie.css">
  <![endif]-->
  <link rel="icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div id="header">
    <div class="container">
      <a href="<?php echo getConfig()->get('urls')->base; ?>" title="<?php echo getConfig()->get('site')->name; ?>"><img src="/img/logo.png" id="logo" alt="<?php echo getConfig()->get('site')->name; ?>"></a>
      <?php if(User::isLoggedIn()) { ?>
        <div class="loginlogout">You're logged in as <?php echo getSession()->get('email'); ?>, <a href="/logout">logout</a>.</div>
        <ul id="navigation">
          <li><a href="/child/new" class="add-child"><div></div>Add Child</a></li>
          <li><a href="/share" class="share"><div></div>Share</a></li>
          <li><a href="/upgrade" class="upgrade"><div></div>Upgrade</a></li>
          <!--<li><a href="/login">Login</a> or <a href="/join">join</a></li>-->
          <!--<li><a href="/logout">Logout</a></li>-->
        </ul>
      <?php } else { ?>
        <div class="loginlogout"><a href="/login">Login</a> or <a href="/join">join</a>.</div>
        <div class="quote"><?php echo getQuote(); ?></div>
      <?php } ?>
    </div>
  </div>
  <div id="header-bar"></div>
  <div id="content" class="container">
    <?php include $body; ?>
    <div id="modal" class="apple_overlay modal"></div>
    <div id="modal-wide" class="apple_overlay modal-wide"></div>
    <div id="message" class="ui-state-highlight"></div>
    <div id="error" class="ui-state-error ui-state-error-text"></div>
    <div id="tooltip"></div>
  </div>
  <div id="footer">
    <hr>
    <div class="container">
      <ul>
        <li><h4>&copy; <?php echo date('Y'); ?> <?php echo getConfig()->get('site')->name; ?></h4></li>
        <li><a href="/about">About us</a></li>
        <li><a href="/help">FAQ / help </a></li>
        <li><a href="/terms">Terms </a></li>
        <li><a href="/privacy">Privacy </a></li>
      </ul>
      <!--<ul>
        <li><h4>Additional resources</h4></li>
        <li><a href="/share-baby-photos">Share baby photos</a></li>
        <li><a href="/create-baby-scrapbook">Create baby scrapbook</a></li>
        <li><a href="/make-childs-photoblog">Make a child's photo blog</a></li>
        <li><a href="/design-childs-webpage">Design child's webpage</a></li>
      </ul>-->
      <?php if($_SERVER['REQUEST_URI'] == '/' && !User::isLoggedIn()) { ?>
        <!--<p>
          "<?php echo getConfig()->get('site')->name; ?> is my favorite way to share my baby's photos. 
          I already upload them to Facebook so it was easy to create my child's page."
          <em>Suja Brane - Cincinnati, OH (<a href="http://anna.meltsmyheart.com">http://anna.meltsmyheart.com</a>)</em>
        <p>
        <p>
          "My favorite part of <?php echo getConfig()->get('site')->name; ?> is how great the site makes my boring photos look. 
          The site makes me feel like a professional photographer."
          <em>Lisa Mcfarlane - Redwood City, CA (<a href="http://jack.meltsmyheart.com">http://jack.meltsmyheart.com</a>)</em>
        <p>
        <p>
          "Everyone I shared Gabriel's <?php echo getConfig()->get('site')->name; ?> webpage with loved it.
          They kept commenting on how fast he's growing up!"
          <em>Leeja Thomas - Houston, TX (<a href="http://gabriel.meltsmyheart.com">http://gabriel.meltsmyheart.com</a>)</em>
        <p>-->
      <?php } ?>
    </div>
  </div>
  <script src="<?php echo getAsset('js', getAssetJsMember()); ?>"></script>
  <script>
    var _gaq = _gaq || [], mpq = [];
    $(document).ready(function() {
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
      <?php if(isset($_GET['e'])) { ?>
        mmh.displayError(<?php echo json_encode(getString($_GET['e'])); ?>);
      <?php } ?>
      <?php if(isset($js)) { ?>
        <?php echo $js; ?>
      <?php } ?>
    });
  </script>
</body>
</html>
