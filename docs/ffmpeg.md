
1. Check codec of video, if vp8/vp9 - OK (y)
2. If not, transcode with below 2 pass method
3. Check filesize.. etc

.. Possibly don't transcode web compatible formats like h264 ..


ffmpeg -i 64k1c89l.mp4 -c:v libvpx-vp9 -b:v 2M -pass 1 -an -f null /dev/null && \
ffmpeg -i 64k1c89l.mp4 -c:v libvpx-vp9 -b:v 2M -pass 2 -c:a libopus output.webm

Faster: ..

ffmpeg  -i 64k1c89l.mp4  -b:v 0  -crf 30  -pass 1  -an -f webm -y /dev/null
ffmpeg  -i 64k1c89l.mp4  -b:v 0  -crf 30  -pass 2  output.webm