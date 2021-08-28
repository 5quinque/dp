
if (typeof tagsearch === 'undefined') {
    console.error("tagsearch is not defined");
}

tagsearch.forEach(element => {
    console.log(element.id, element.name);
});

var upTags = tagsearch.map(function(value) {
    value.name = value.name.toUpperCase();
    return value;
});


document.getElementById("tag-search").addEventListener("input", function(event) {
    var searchTerm = this.value.toUpperCase();

    document.getElementById("tag-suggestions").innerHTML = "";

    // [TODO] If search is empty, or no suggestions
    //          change classes on the input for rounded corners 
    if (searchTerm === "") {
        return;
    }

    upTags.forEach(tag => {
        if (tag.name.includes(searchTerm)) {
            console.log("Found", tag.name);
            addSuggestion(tag.name); // [TODO]: Get original case?
        }
    });

});


function addSuggestion(value) {
    var tagSuggestions = document.getElementById("tag-suggestions");
    var template = document.getElementById("tag-template");
    var tagEl = template.content.cloneNode(true);

    tagEl.querySelector('li').innerHTML = value;

    console.log(tagEl);

    tagSuggestions.appendChild(tagEl);
}
