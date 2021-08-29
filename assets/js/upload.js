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
        previewsContainer: "#gallery", // Define the container to display the previews
        clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.

        acceptedFiles: "image/*, video/*",

        maxFilesize: 4000, // 4GB
        timeout: 30000, // 30s
        chunking: true,
        chunkSize: 10000000, // 10MB
    });


    myDropzone.on("addedfile", function(file) {
        var filename = file.upload.filename;
        if (file.upload.filename.length >= 30) {
            filename = file.upload.filename.substring(0, 27) + "...";
        }

        file.previewElement.querySelector(".filename").innerHTML = filename;

        // Disable the 'save' button
        var postSaveButton = document.getElementById("post_save");

        postSaveButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Uploading Files...`;

        postSaveButton.setAttribute("disabled", "disabled");

        postSaveButton.classList.remove("bg-blue-600");
        postSaveButton.classList.add("bg-blue-400", "cursor-not-allowed");
    });

    // Update individual progress bar
    myDropzone.on("uploadprogress", function(file, progress) {
        file.previewElement.querySelector(".progress-text").innerHTML = Math.round(progress) + "%";
        file.previewElement.querySelector(".progress").style.width = progress + "%";
        // console.log("File progress", progress, file);
    });

    myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
        file.previewElement.querySelector(".progress-container").classList.remove("opacity-0");
    });

    // // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("complete", function(file) {
        console.log("File Complete", file.status);

        if (file.status == "success") {
            var complete_text = "Done";
            var colour = "green";

            var response = JSON.parse(file.xhr.responseText);

            var media_option = document.createElement("option");
            media_option.value = response.media_id;
            media_option.text = response.media_filename;
            media_option.selected = true;

            document.getElementById("post_media").add(media_option, null);

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
    });
    
    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
        console.log("Queue Complete");

        var postSaveButton = document.getElementById("post_save");

        postSaveButton.innerHTML = "Save";

        postSaveButton.removeAttribute("disabled");

        postSaveButton.classList.remove("bg-blue-400", "cursor-not-allowed");
        postSaveButton.classList.add("bg-blue-600");
    });

    myDropzone.on("removedfile", function(file) {
        // [TODO] Tell the server we've removed the file and it can be deleted
        var response = JSON.parse(file.xhr.responseText);
        document.querySelector(
            `#post_media option[value="${response.media_id}"]`
            ).remove();
    });

});