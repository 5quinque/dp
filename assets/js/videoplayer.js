import videojs from 'video.js';
import 'video.js/dist/video-js.css';

var options = {
    autoplay: false,
    controls: true,
    fluid: true,
    // width: "640",
    // height: "264",
    preload: "auto",

    playbackRates: [0.5, 1, 1.5, 2],
};

console.log("loaded videoplayer.js");
 
var player = videojs(document.querySelector('.video-js'), options, function onPlayerReady() {
  videojs.log('Your player is ready!');
 
  // In this context, `this` is the player that was created by Video.js.
  this.play();
 
  // How about an event listener?
  this.on('ended', function() {
    videojs.log('Awww...over so soon?!');
  });
});
