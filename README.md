# Å++

## Development setup

Based on https://github.com/wodby/docker4php

```sh
127.0.0.1	aaplusplus.localhost mailhog.aaplusplus.localhost portainer.aaplusplus.localhost
```

The main site: http://aaplusplus.localhost:8000
MailHog: http://mailhog.aaplusplus.localhost:8000
Portainer: http://portainer.aaplusplus.localhost:8000

### Quick start

```sh
make up
```

```sh
make shell
scripts/update
```

```sh
bin/console fos:user:create --super-admin
```

## Scripts

A number of helper scripts make it easier to talk to services running
inside the docker containers:

* `./scripts/composer`
* `./scripts/console`
