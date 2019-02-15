# Å++

### Quick start using Docker Compose

```sh
docker-compose pull
docker-compose up -d
```

```sh
docker-compose exec phpfpm /app/scripts/update
```

```sh
docker-compose exec phpfpm /app/bin/console fos:user:create --super-admin
```

Open the site:

```
open http://aaplusplus.docker.localhost:$(docker-compose port reverse-proxy 80 | cut -d: -f2)
```

**Note**: You may have to add the line

```
127.0.0.1	aaplusplus.docker.localhost
```

to your `/etc/hosts` file to make the url work in your browser.


## Scripts

* `./scripts/update` Updates the project
