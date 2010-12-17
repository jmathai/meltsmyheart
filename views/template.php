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
    <li><a href="/logout">Logout</a></li>
  </ul>
  <?php include $body; ?>
  <ul>
    <li>Footer goes here</li.
  </ul>
  <script src="/js/jquery.js"></script>
  <script src="/js/javascript.js"></script>
  <?php if(isset($javascript)) { ?>
    <script>
      <?php echo $javascript; ?>
    </script>
  <?php } ?>
</body>
</html>
