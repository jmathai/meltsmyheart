  $('#slideshow').crossSlide({
    sleep: 2,
    fade: 1
  }, [
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-page-anna.jpg')); ?>' },
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-tavin.jpg')); ?>' },
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-naomi.jpg')); ?>' },
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-malia.jpg')); ?>' },
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-anna.jpg')); ?>' },
    { src: '<?php printf('%s%s', $host, getAssetImage('/img/creative/home-slide-page-tavin.jpg')); ?>' }
  ]);
