<h1>Customize <?php echo posessive($child['c_name']); ?> page</h1>

<p>You can choose how <?php echo posessive($child['c_name']); ?> page will look when others see it.</p>

<form method="post" id="child-customize" action="<?php echo getConfig()->get('urls')->base; ?>/child/page/customize/<?php echo $child['c_id']; ?>">
  <h2>Select a background</h2>
  <table border="0" cellspacing="10" width="400" align="center">
    <tr height="100">
      <td width="190" background="/img/page-background-cork.jpg"></td>
      <td width="20"></td>
      <td width="190" background="/img/page-background-tartan.png"></td>
    </tr>
    <tr>
      <td align="center"><input type="radio" name="background" value="" checked="checked"></td>
      <td></td>
      <td align="center"><input type="radio" name="background" value="theme-background-tartan.css"></td>
    </tr>
  </table>

  <h2>Choose photo layout</h2>
  <table border="0" cellspacing="10" width="400" align="center">
    <tr height="100">
      <td width="190"><img src="/img/page-photo-layout-straight.jpg"></td>
      <td width="20"></td>
      <td width="190"><img src="/img/page-photo-layout-rotated.jpg"></td>
    </tr>
    <tr>
      <td align="center"><input type="radio" name="photo-layout" value="" checked="checked"></td>
      <td></td>
      <td align="center"><input type="radio" name="photo-layout" value="theme-photo-rotate.css"></td>
    </tr>
  </table>
  <input type="hidden" name="r" value="<?php echo Child::getPageUrl($child); ?>">
  <button type="submit"><div>Save</div></button> or <a href="#" class="close">close</a>
</form>
<script>
  <?php foreach((array)$theme['css'] as $css) { ?>
    $("form#child-customize input[value=<?php echo $css; ?>]").attr("checked", true);
  <?php } ?>
</script>
