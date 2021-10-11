import axios from 'axios';


async function getCollections() {
    return await axios.get('/api/collections')
        .then(res => {
            return res.data;
        })
        .catch(function (error) {
            return false;
        });
}


async function createCollection(collectionname) {
    // [TODO]: Handle failure
    return await axios.post('/api/new_collection', {
        name: collectionname,
        _token: collectioncreate_csrf,
    })
        .then(response => {
            collectioncreate_csrf = response.data.csrf;
            return response.data.id;
        })
        .catch(function (error) {
            return false;
        });
}

function handleNewCollectionResp(response) {
    // console.log(response.data.id);
}

export { createCollection, getCollections };

