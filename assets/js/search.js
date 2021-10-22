
import axios from 'axios';

document.getElementById("searchbox").addEventListener("input", function () {
    if (this.value.length > 2) {
        console.log(this.value);

        axios.get(autocompleteurl, { params: { q: this.value } })
            .then(function (response) {
                var autocomplete = document.getElementById("search-dropdown-template").content.cloneNode(true);

                var searchContainer = document.getElementById("search-container");
                if (!searchContainer.querySelector(".dropdown")) {
                    searchContainer.appendChild(autocomplete);
                }

                var dropdownUl = searchContainer.querySelector("ul");
                dropdownUl.innerHTML = "";

                response.data.forEach(function (result) {
                    var autocompleteItem = document.getElementById("search-dropdown-item-template").content.cloneNode(true);
                    autocompleteItem.querySelector("a").innerHTML = result.highlights[0].snippet
                    autocompleteItem.querySelector("a").href = "/view/" + result.document.title;


                    dropdownUl.appendChild(autocompleteItem);
                    console.log(result.document.title);
                });
            })
    }
});

document.getElementById("searchbutton").addEventListener("click", function () {
    window.location = "/search/" + document.getElementById("searchbox").value;
});

