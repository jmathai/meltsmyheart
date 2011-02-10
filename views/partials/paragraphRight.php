<?php if(isMobile()) { return; } ?>
<p class="right">
  <?php if(isset($context) && $context === 'affiliate') { ?>
    <?php getTemplate()->display('partials/affiliateRight.php'); ?>
  <?php } else { ?>
    <img src="/img/creative/polaroid-3-300-<?php echo rand(1,2); ?>.jpg">
    <span>
      <?php echo getQuote(); ?>
    </span>
  <?php } ?>
</p>
