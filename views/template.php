<html lang="en">
<head>
  <title></title>
</head>
<body>
  <ul>
    <li><a href="/">Home</a></li>
    <li><a href="/child/new">New Child</a></li>
    <li><a href="/login">Login</a></li>
    <li><a href="/logout">Logout</a></li>
  </ul>
  <?php include $body; ?>
  <ul>
    <li>Footer goes here</li.
  </ul>
  <script src="/js/jquery.js"></script>
  <?php if(isset($javascript)) { ?>
    <script>
      <?php echo $javascript; ?>
    </script>
  <?php } ?>
</body>
</html>
