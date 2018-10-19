# Proof of Concept for the data import to Akeneo using its REST API and RabbitMQ


## Commands

Bash to the php-cli container:

```
docker-compose run php-cli bash
```

Check queues in RabbittMQ

```
rabbitmqctl list_queues
```

Run consumer (from container)

```
./daemon/rabbitmq-cli-consumer -c ./daemon/daemon.conf --executable "php worker.php" --verbose
```


## Additional info:

* [How to access rabbitmq from other containers](https://mindbyte.nl/2018/04/05/run-rabbitmq-using-docker-compose-with-guest-user.html)


