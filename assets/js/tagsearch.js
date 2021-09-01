
import {createTag, getTags} from './createtag';

// Clear tags provided by symfony form
document.getElementById("post_tags").innerHTML = "";

var upperTags;

async function getUpperTags() {
    var tags = await getTags();

    var upperTags = tags.map(function(value) {
        value.original_name = value.name;
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
        document.getElementById("tag-search").classList.add("rounded");
        document.getElementById("tag-search").classList.remove("rounded-t");

        return;
    }

    document.getElementById("tag-search").classList.remove("rounded");
    document.getElementById("tag-search").classList.add("rounded-t");

    addSuggestion(this.value, null, true);

    upperTags.forEach(tag => {
        if (tag.name.includes(searchTerm)) {
            addSuggestion(tag.original_name, tag.id);
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

            // Clear tag search
            document.getElementById("tag-search").value = "";
            document.getElementById("tag-suggestions").innerHTML = "";
        });

        tagEl.querySelector('li').classList.add('bg-green-50');
    
        // Ensure that the new tag is always at the top
        tagSuggestions.prepend(tagEl);
    } else {
        tagEl.querySelector('li').addEventListener('click', () => {
            addTag(id, value);

            // Clear tag search
            document.getElementById("tag-search").value = "";
            document.getElementById("tag-suggestions").innerHTML = "";
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

    tagEl.querySelector('.tag-remove').addEventListener('click', (ev) => {
        document.querySelector(`#tags-added div[data-id="${id}"]`).remove();

        document.querySelector(
            `#post_tags option[value="${id}"]`
            ).remove();
    });

    // console.log("AddTag", id, value);

    tagsAdded.append(tagEl);
    

    var tag_option = document.createElement("option");
    tag_option.value = id;
    tag_option.text = value;
    tag_option.selected = true;

    document.getElementById("post_tags").add(tag_option, null);

    // [TODO]: Add click event on 'x' to remove tag
}

