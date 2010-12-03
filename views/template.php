<html lang="en">
<head>
  <title></title>
</head>
<body>
  <ul>
    <li>Home</li>
    <li>Children</li>
    <li>New Child</li>
    <li>Login</li>
    <li>Logout</li>
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
