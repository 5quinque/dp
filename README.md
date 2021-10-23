# dp

## Setup

### Docker

```bash
cd docker
vim .env
sudo chown -R 101.101 ..
docker-compose up --build
```

Clean up docker stuff
```bash
sudo rm -rf public/build var vendor node_modules
```

### Typesense
Create typesense docker container

```bash
docker run \
    -p 8108:8108 \
    -v/data:/data \
    typesense/typesense:0.21.0 \
    --data-dir /data \
    --api-key=KEY
```

```bash
php bin/console typesense:create
php bin/console typesense:create
```

