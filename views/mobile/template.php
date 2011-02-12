<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="<?php echo getAsset('css', array('jquery.mobile.css')); ?>">
  <script src="<?php echo getAsset('js', array('jquery.min.js','jquery.mobile.min.js')); ?>"></script>
  <title><?php echo getConfig()->get('site')->name; ?></title>
</head>

<body>
<div data-role="page" data-theme="c">
  <div data-role="header" data-theme="e">
    <?php if(!isset($noHeaderButtons) || !$noHeaderButtons) { ?>
      <a href="/" data-role="button" data-inline="true" data-icon="back" data-rel="back">back</a>
    <?php } ?>
    <h1><?php echo $title; ?></h1>
    <?php if(User::isLoggedIn() && (!isset($noHeaderButtons) || !$noHeaderButtons)) { ?>
      <a href="/logout" data-role="button" data-inline="true" data-icon="delete">logout</a>
    <?php } ?>
  </div>

  <div data-role="content">
    <?php include getConfig()->get('paths')->views."/mobile/{$body}"; ?>
  </div>
  
  <!--<div data-role="footer" id="persistent-footer" data-position="fixed" data-theme="a">
		<div data-role="navbar"> 
			<ul> 
				<li><a href="/children" class="ui-btn-active">Children</a></li> 
				<li><a href="/camera">Camera</a></li> 
				<li><a href="/friends">Friends</a></li> 
			</ul> 
		</div>
  </div>-->
</div>
<script>
</script>
</body>
</html>

