import axios from 'axios';


async function getTags() {
    return await axios.get('/api/tags')
        .then(res => {
            return res.data;
        })
        .catch(function (error) {
            return false;
        });
}


async function createTag(tagname) {
    // [TODO]: Handle failure
    return await axios.post('/api/new_tag', {
            name: tagname,
            _token: tagcreate_csrf,
        })
        .then(response => {
            tagcreate_csrf = response.data.csrf;
            return response.data.id;
        })
        .catch(function (error) {
            return false;
        });
}

function handleNewTagResp(response) {
    // console.log(response.data.id);
}

export { createTag, getTags };

