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

### Tests

```sh
$ docker exec -it p4-app ./vendor/bin/codecept run
```
or (equal)
```sh
$ docker-compose exec app-service ./vendor/bin/codecept run
```

### Devtools

```sh
$ docker-compose exec app-service ./vendor/bin/phalcon serve
```

or "detached":

```sh
$ docker-compose exec -d app-service ./vendor/bin/phalcon serve
```

url: [http://phalcon4.test:8000/webtools.php](http://phalcon4.test:8000/webtools.php)
