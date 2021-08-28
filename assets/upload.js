import Dropzone from 'dropzone'

document.addEventListener("DOMContentLoaded", function() {

    if (typeof upload_url === 'undefined') {
        return console.error("upload_url is not defined");
    }

    var previewTemplate = document.getElementById("file-template").innerHTML;

    var myDropzone = new Dropzone("#upload-form", { // Make the whole body a dropzone
        url: upload_url, // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        // autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#gallery", // Define the container to display the previews
        clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.

        maxFilesize: 4000, // 4GB
        timeout: 30000, // 30s
        chunking: true,
        chunkSize: 10000000, // 10MB
    });

    function dropHandler(ev) {
        console.log('File(s) dropped');
    }
    function dragOverHandler(ev) {
        console.log('File(s) in drop zone');
      
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();
      }
      


    myDropzone.on("addedfile", function(file) {
        // Start button
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };

        var filename = file.upload.filename;
        if (file.upload.filename.length >= 30) {
            filename = file.upload.filename.substring(0, 27) + "...";
        }

        file.previewElement.querySelector(".filename").innerHTML = filename;
    });
    
    // // Update the total progress bar
    // myDropzone.on("totaluploadprogress", function(progress, bytes, bytesSent) {
    //     console.log(progress, bytes, bytesSent);

    //     document.querySelector("#total-progress-text").innerHTML = progress + "%";
    //     document.querySelector("#total-progress").style.width = progress + "%";
    // });

    // Update individual progress bar
    myDropzone.on("uploadprogress", function(file, progress) {
        // console.log(Math.round(progress))
        file.previewElement.querySelector(".progress-text").innerHTML = Math.round(progress) + "%";
        file.previewElement.querySelector(".progress").style.width = progress + "%";
        // console.log("File progress", progress, file);
    });

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        // document.querySelector("#total-progress").style.opacity = "1";
        file.previewElement.querySelector(".progress-container").classList.remove("opacity-0");
        // And disable the start button
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });

    // // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("complete", function(file) {

        console.log("File Complete", file.status);

        if (file.status == "success") {
            var complete_text = "Done";
            var colour = "green";

            var response = JSON.parse(file.xhr.responseText);

            console.log(response.video_id, response.video_filename);

            var video_option = document.createElement("option");
            video_option.value = response.video_id;
            video_option.text = response.video_filename;
            video_option.selected = true;

            document.getElementById("post_videos").add(video_option, null);

        } else if (file.status == "error") {
            var complete_text = "Error";
            var colour = "red";
        }

        file.previewElement.querySelector(".progress-status-text").innerHTML = complete_text;
        file.previewElement.querySelector(".progress-status-text").classList.remove("text-blue-600", "bg-blue-200");
        file.previewElement.querySelector(".progress-status-text").classList.add(`text-${colour}-600`, `bg-${colour}-200`);

        file.previewElement.querySelector(".progress").classList.remove("bg-blue-500");
        file.previewElement.querySelector(".progress").classList.add(`bg-${colour}-500`);

        file.previewElement.querySelector(".progress-text").classList.remove("text-blue-600");
        file.previewElement.querySelector(".progress-text").classList.add(`text-${colour}-600`);

        file.previewElement.querySelector(".start").remove();
    });
    
    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        console.log("Queue Complete");
        // document.querySelector("#total-progress").style.opacity = "0";
    });

    myDropzone.on("removedfile", function(file) {
        // [TODO] Tell the server we've removed the file and it can be deleted
        var response = JSON.parse(file.xhr.responseText);
        document.querySelector(
            `#post_videos option[value="${response.video_id}"]`
            ).remove();
    });
    
    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    // document.querySelector("#actions #submit").onclick = function() {
    //     myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    // };

    // document.querySelector("#actions #cancel").onclick = function() {
    //     myDropzone.removeAllFiles(true);
    // };

});