<?php

header("Access-Control-Allow-Origin: *");

?>

<head>
  <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet" />
</head>

<body>

<video-js id=example-video width=960 height=540 class="vjs-default-skin" controls>
</video-js>




<script src="https://vjs.zencdn.net/7.15.4/video.min.js"></script>
<script src="https://cdn.dashjs.org/latest/dash.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-dash/5.1.0/videojs-dash.min.js" integrity="sha512-kTsVsYBqVykD9dGEO8IXNnqiNy5LoG8QRc+c34QsXCli3aMZlVncbDzc7AkxpkBZrMRQSzHNYa45J461rcXnyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>


var player = videojs('example-video');

var src = {
  src: "https://b2.ryanl.co.uk/theroad/theroad.mpd",
  type: "application/dash+xml"
};
player.src(src);

</script>

</body>

<!--

VIDEO_IN=The.Walking.Dead.S11E08.For.Blood.1080p.AMZN.WEB-DL.DDP5.1.H.264-LAZY.mkv
VIDEO_OUT=thewalkingdead.s11e08/thewalkingdead.s11e08.mpd
HLS_TIME=4
FPS=25
GOP_SIZE=100
PRESET_P=veryfast
V_SIZE_1=768x432
V_SIZE_2=1920x1080

ffmpeg -i $VIDEO_IN \
  -preset $PRESET_P -keyint_min $GOP_SIZE -g $GOP_SIZE -sc_threshold 0 \
  -r $FPS -c:v libx264 -pix_fmt yuv420p -c:a aac -b:a 128k -ac 1 -ar 44100 \
  -map v:0 -s:0 $V_SIZE_1 -b:v:0 500k -maxrate:0 500k -bufsize:0 250k \
  -map v:0 -s:1 $V_SIZE_2 -b:v:1 2M -maxrate:1 2M -bufsize:1 1M \
  -map 0:a \
  -init_seg_name init\$RepresentationID\$.\$ext\$ -media_seg_name chunk\$RepresentationID\$-\$Number%05d\$.\$ext\$ \
  -use_template 1 -use_timeline 1  \
  -seg_duration 4 -adaptation_sets "id=0,streams=v id=1,streams=a" \
  -f dash $VIDEO_OUT


INSERT INTO `media` (`id`, `post_id`, `filename`, `size`, `mime_type`, `created`, `original_filename`, `filesystem`, `codec`, `object_url`, `processed`) VALUES (NULL, '39', 'thewalkingdead.s11e06', '0', 'application/dash+xml', '2021-10-11 10:50:02.000000', 'thewalkingdead.s11e06.mpd', 'media', 'x264/aac', 'https://b2.ryanl.co.uk/thewalkingdead.s11e06/thewalkingdead.s11e06.mpd', '1'); 