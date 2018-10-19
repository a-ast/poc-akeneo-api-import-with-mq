# Docker

Bash to the php-cli container:

```
docker-compose run php-cli bash
```

Check queues in RabbittMQ
`rabbitmqctl list_queues`

Run consumer
./rabbitmq-cli-consumer --verbose --url amqp://guest:guest@rabbitmq --queue-name hello --executable worker.php

WIth config
`docker@ba236f4b624b:/usr/src/app/daemon$ ./rabbitmq-cli-consumer -c daemon.conf --verbose --executable "php ../worker.php`


Additional info:

How to access rabbitmq from other containers
https://mindbyte.nl/2018/04/05/run-rabbitmq-using-docker-compose-with-guest-user.html


