<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="<?php echo getAsset('css', array('jquery.mobile.css')); ?>">
  <script src="<?php echo getAsset('js', array('jquery.min.js','jquery.mobile.min.js')); ?>"></script>
  <title><?php echo getConfig()->get('site')->name; ?></title>
</head>

<body>
<div data-role="page" data-theme="e">
  <div data-role="header">
    <h1><?php echo $title; ?></h1>
  </div>

  <div data-role="content">
    hello world.
  </div>
  
  <div data-role="footer" id="persistent-footer" data-position="fixed">
		<div data-role="navbar"> 
			<ul> 
				<li><a href="/children" class="ui-btn-active">Children</a></li> 
				<li><a href="/camera">Camera</a></li> 
				<li><a href="/friends">Friends</a></li> 
			</ul> 
		</div>
  </div>
</div>
<script>
</script>
</body>
</html>

