import axios from 'axios';

// const instance = axios.create({
//     baseURL: 'https://some-domain.com/api/',
//     timeout: 1000,
//     headers: {'X-Custom-Header': 'foobar'}
//   });

function getTags() {
    axios.get('/api/tags')
        .then(res => {
            console.log(res.data);
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        });
}

async function createTag(tagname) {
    return await axios.post('/api/new_tag', {
            name: tagname,
        })
        .then(response => {
            return response.data.id;
        })
        .catch(function (error) {
            return false;
        });
}

function handleNewTagResp(response) {
    // console.log(response.data.id);
}

export { createTag };

// createTag("Test2");

    // curl --header "Content-Type: application/json" \
    // --request POST \
    // --data '{"name":"test_json_post"}' \ 
    // https://127.0.0.1:8000/api/new_tag
  