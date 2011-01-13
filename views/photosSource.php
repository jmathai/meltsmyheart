<h1>Where would you like to select photos from?</h1>

<p>
  We want to make it easy for you to select photos of your child.
  You can choose photos you've uploaded to Facebook, Smugmug or have stored on your computer.
</p>
<p>
  <h2>Get started by selecting one now.</h2>
  <ul id="thirdparty">
    <li><a href="<?php echo $fbUrl; ?>" class="facebook"></a></li>
    <li><a href="<?php echo $smugUrl; ?>" class="smugmug"></a></li>
    <?php if(getSession()->get('userId') == 1) { ?>
      <li><a href="<?php echo $ptgUrl; ?>" class="photagious"></a></li>
    <?php } ?>
    <li><hr></li>
    <li><a href="/photos/add/<?php echo $childId; ?>" class="computer">My computer</a></li>
  </ul>
</p>
