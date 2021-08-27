import Dropzone from 'dropzone'

document.addEventListener("DOMContentLoaded", function() {

    if (typeof upload_url === 'undefined') {
        return console.error("upload_url is not defined");
    }

    var previewTemplate = document.getElementById("file-template").innerHTML;

    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        url: upload_url, // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually added
        previewsContainer: "#gallery", // Define the container to display the previews
        clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.

        maxFilesize: 4000, // 4GB
        timeout: 30000, // 30s
        chunking: true,
        chunkSize: 10000000, // 10MB
    });


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
    
    // // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        console.log("Queue Complete");
        // document.querySelector("#total-progress").style.opacity = "0";
    });
    
    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    document.querySelector("#actions #submit").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };

    document.querySelector("#actions #cancel").onclick = function() {
        myDropzone.removeAllFiles(true);
    };

});