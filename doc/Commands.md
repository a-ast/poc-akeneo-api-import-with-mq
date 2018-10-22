# Commands

Bash to the php-cli container:

```
docker-compose run php-cli bash
```

Check queues in RabbittMQ

```
docker-compose exec rabbitmq rabbitmqctl list_queues
```

Admin UI: http://localhost:15672/#/
(guest:guest)


Run consumer (from container)

```
./bin/daemon/rabbitmq-cli-consumer -c ./bin/daemon/daemon.conf --executable ./bin/worker/worker.sh --verbose --strict-exit-code
```


## Additional info:

* [How to access rabbitmq from other containers](https://mindbyte.nl/2018/04/05/run-rabbitmq-using-docker-compose-with-guest-user.html)


