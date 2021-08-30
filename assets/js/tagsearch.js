
import {createTag} from './createtag';

if (typeof tagsearch === 'undefined') {
    console.error("tagsearch is not defined");
}

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

    addSuggestion(this.value, true);

    upTags.forEach(tag => {
        if (tag.name.includes(searchTerm)) {
            // console.log("Found", tag.name);
            addSuggestion(tag.name); // [TODO]: Get original case?
        }
    });

});


function addSuggestion(value, newTag=false) {
    var tagSuggestions = document.getElementById("tag-suggestions");
    var template = document.getElementById("tag-template");
    var tagEl = template.content.cloneNode(true);

    tagEl.querySelector('li').innerHTML = value;

    if (newTag) {
        tagEl.querySelector('li').classList.add('bg-green-50');
        tagEl.querySelector('li').addEventListener('click', async () => {
            var tag_id = await createTag(value);
            console.log(value, tag_id); // [TODO] ...
        });
    }

    tagSuggestions.appendChild(tagEl);
}
