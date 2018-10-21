#!/usr/bin/env bash

./bin/daemon/rabbitmq-cli-consumer \
    --url amqp://guest:guest@rabbitmq:5672 \
    --queue-name create_product_queue \
    --executable "./bin/console poc:product:post --type=product" \
    --strict-exit-code \
    --verbose
