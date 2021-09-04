# setup

# minio

```bash
docker run \
  --detach \
  --name minio \
  -p 9000:9000 \
  -p 9001:9001 \
  -e MINIO_DOMAIN="domain.name" \
  -e MINIO_ROOT_USER="username" \
  -e MINIO_ROOT_PASSWORD="password" \
  -v /data/media/minio_data:/data \
  minio/minio server \
  /data \
  --console-address ":9001"
```

# rabbitmq

```bash
docker run \
  --detach \
  --hostname dp-rabbit \
  --name rabbitmq \
  -p 5672:5672 \
  -p 8081:15672 \
  rabbitmq:3-management
```

# message consumer

https://symfony.com/doc/current/messenger.html#deploying-to-production

`php bin/console messenger:consume async -vv`