
1. Check codec of video, if vp8/vp9 - OK (y)
2. If not, transcode with below 2 pass method
3. Check filesize.. etc

.. Possibly don't transcode web compatible formats like h264 ..

5. Create a thumbnail?


ffmpeg -i 64k1c89l.mp4 -c:v libvpx-vp9 -b:v 2M -pass 1 -an -f null /dev/null && \
ffmpeg -i 64k1c89l.mp4 -c:v libvpx-vp9 -b:v 2M -pass 2 -c:a libopus output.webm

Faster: ..

ffmpeg  -i 64k1c89l.mp4  -b:v 0  -crf 30  -pass 1  -an -f webm -y /dev/null
ffmpeg  -i 64k1c89l.mp4  -b:v 0  -crf 30  -pass 2  output.webm


Thumbnail - 

ffmpeg -ss 00:00:01.00 -i input.mp4 -vf 'scale=640:640:force_original_aspect_ratio=decrease' -vframes 1 output.png
ffmpeg -ss 00:00:01.000 -i input.mp4 -vframes 1 output.png


ffmpeg -i myvideo.avi -vf 'scale=640:640:force_original_aspect_ratio=decrease,fps=1/5' img%03d.png

h264 - faster transcoding, vp8/vp9/av1 way too slow...

ffmpeg -i 61336bd244c7b.mp4 -vcodec h264 -acodec aac -strict -2 61336bd244c7b_test.mp4




ffprobe -v error -select_streams v:0 -show_entries stream=codec_name \
  -of default=noprint_wrappers=1:nokey=1 1599013833458.webm

https://gist.github.com/jaydenseric/220c785d6289bcfd7366
https://gist.github.com/Vestride/278e13915894821e1d6f
https://github.com/markus-perl/ffmpeg-build-script
https://trac.ffmpeg.org/wiki/Encode/AV1
https://github.com/master-of-zen/Av1an
