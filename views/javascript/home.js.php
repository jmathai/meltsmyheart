// initialize tooltip
$("ul.home-photos li img[title]").tooltip({
   // tweak the position
   offset: [10, 2],
   // use the "slide" effect
   effect: 'slide'
// add dynamic plugin with optional configuration for bottom edge
}).dynamic({ bottom: { direction: 'down', bounce: true } });

