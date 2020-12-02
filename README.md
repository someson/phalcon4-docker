### Setup

```sh
$ git clone https://github.com/someson/phalcon4-docker.git
$ docker-compose up -d --build
```

- add to your ```[...]/etc/hosts```

```sh
127.0.0.1 phalcon4.test
```

- composer update from the host:

```sh
$ docker-compose run --rm composer-service composer update --ignore-platform-reqs --no-scripts
```

- run tests:

```sh
$ docker exec -it p4-app ./vendor/bin/codecept run
```
or (equal)
```sh
$ docker-compose exec app-service ./vendor/bin/codecept run
```
