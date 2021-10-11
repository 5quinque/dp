
import { createCollection, getCollections } from './createcollection';

// Clear collections provided by symfony form
document.getElementById("post_collections").innerHTML = "";

var upperCollections;

async function getUpperCollections() {
    var collections = await getCollections();

    var upperCollections = collections.map(function (value) {
        value.original_name = value.name;
        value.name = value.name.toUpperCase();
        return value;
    });

    return upperCollections;
}

getUpperCollections().then((collections) => {
    upperCollections = collections;
});

document.getElementById("collection-search").addEventListener("input", function (event) {
    var searchTerm = this.value.toUpperCase();

    document.getElementById("collection-suggestions").innerHTML = "";

    // [TODO] If search is empty, or no suggestions
    //          change classes on the input for rounded corners 
    if (searchTerm === "") {
        document.getElementById("collection-search").classList.add("rounded");
        document.getElementById("collection-search").classList.remove("rounded-t");

        return;
    }

    document.getElementById("collection-search").classList.remove("rounded");
    document.getElementById("collection-search").classList.add("rounded-t");

    addSuggestion(this.value, null, true);

    upperCollections.forEach(collection => {
        if (collection.name.includes(searchTerm)) {
            addSuggestion(collection.original_name, collection.id);
        }
    });

});


function addSuggestion(value, id, newCollection = false) {
    var collectionSuggestions = document.getElementById("collection-suggestions");
    var template = document.getElementById("collection-template");
    var collectionEl = template.content.cloneNode(true);

    collectionEl.querySelector('li').innerHTML = value;

    collectionEl.querySelector('li').dataset.id = id;

    if (newCollection) {
        collectionEl.querySelector('li').addEventListener('click', async () => {
            var collection_id = await createCollection(value);
            addCollection(collection_id, value);

            // Clear collection search
            document.getElementById("collection-search").value = "";
            document.getElementById("collection-suggestions").innerHTML = "";
        });

        collectionEl.querySelector('li').classList.add('bg-green-50');

        // Ensure that the new collection is always at the top
        collectionSuggestions.prepend(collectionEl);
    } else {
        collectionEl.querySelector('li').addEventListener('click', () => {
            addCollection(id, value);

            // Clear collection search
            document.getElementById("collection-search").value = "";
            document.getElementById("collection-suggestions").innerHTML = "";
        });

        collectionSuggestions.append(collectionEl);
    }
}

function addCollection(id, value) {
    var collectionsAdded = document.getElementById("collections-added");
    var template = document.getElementById("collection-added-template");
    var collectionEl = template.content.cloneNode(true);

    if (collectionsAdded.querySelector(`[data-id='${id}']`)) {
        console.log("Collection already added", id, value);

        return;
    }

    collectionEl.querySelector('div').prepend(value);
    collectionEl.querySelector('div').dataset.id = id;

    collectionEl.querySelector('.collection-remove').addEventListener('click', (ev) => {
        document.querySelector(`#collections-added div[data-id="${id}"]`).remove();

        document.querySelector(
            `#post_collections option[value="${id}"]`
        ).remove();
    });

    // console.log("AddCollection", id, value);

    collectionsAdded.append(collectionEl);


    var collection_option = document.createElement("option");
    collection_option.value = id;
    collection_option.text = value;
    collection_option.selected = true;

    document.getElementById("post_collections").add(collection_option, null);

    // [TODO]: Add click event on 'x' to remove collection
}

