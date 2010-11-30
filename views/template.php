<html lang="en">
<head>
  <title></title>
</head>
<body>
  <?php include $body; ?>
  <script src="/js/jquery.js"></script>
  <?php if(isset($javascript)) { ?>
    <script>
      <?php echo $javascript; ?>
    </script>
  <?php } ?>
</body>
</html>
