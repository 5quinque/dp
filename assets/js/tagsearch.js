
import {createTag, getTags} from './createtag';

var upperTags;

async function getUpperTags() {
    var tags = await getTags();

    var upperTags = tags.map(function(value) {
        value.name = value.name.toUpperCase();
        return value;
    });

    return upperTags;
}

getUpperTags().then((tags) => {
    upperTags = tags;
});

document.getElementById("tag-search").addEventListener("input", function(event) {
    var searchTerm = this.value.toUpperCase();

    document.getElementById("tag-suggestions").innerHTML = "";

    // [TODO] If search is empty, or no suggestions
    //          change classes on the input for rounded corners 
    if (searchTerm === "") {
        return;
    }

    addSuggestion(this.value, null, true);

    upperTags.forEach(tag => {
        if (tag.name.includes(searchTerm)) {
            // console.log("Found", tag.name);
            addSuggestion(tag.name, tag.id); // [TODO]: Get original case?
        }
    });

});


function addSuggestion(value, id, newTag=false) {
    var tagSuggestions = document.getElementById("tag-suggestions");
    var template = document.getElementById("tag-template");
    var tagEl = template.content.cloneNode(true);

    tagEl.querySelector('li').innerHTML = value;

    tagEl.querySelector('li').dataset.id = id;

    if (newTag) {
        tagEl.querySelector('li').addEventListener('click', async () => {
            var tag_id = await createTag(value);
            addTag(tag_id, value);
        });

        tagEl.querySelector('li').classList.add('bg-green-50');
    
        // Ensure that the new tag is always at the top
        tagSuggestions.prepend(tagEl);
    } else {
        tagEl.querySelector('li').addEventListener('click', () => {
            addTag(id, value);
        });

        tagSuggestions.append(tagEl);
    }
}

function addTag(id, value) {
    var tagsAdded = document.getElementById("tags-added");
    var template = document.getElementById("tag-added-template");
    var tagEl = template.content.cloneNode(true);

    if (tagsAdded.querySelector(`[data-id='${id}']`)) {
        console.log("Tag already added", id, value);

        return;
    }

    tagEl.querySelector('div').prepend(value);
    tagEl.querySelector('div').dataset.id = id;

    console.log("AddTag", id, value);

    tagsAdded.append(tagEl);
    
    // [TODO]: Add click event on 'x' to remove tag
    // [TODO]: Add the tags to some form field to be processed
}

