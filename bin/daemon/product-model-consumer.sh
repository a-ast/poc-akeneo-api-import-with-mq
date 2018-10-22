#!/usr/bin/env bash

./bin/daemon/rabbitmq-cli-consumer \
    --url amqp://guest:guest@rabbitmq:5672 \
    --queue-name create_product_model_queue \
    --executable "./bin/console poc:product:update --type=model" \
    --strict-exit-code \
    --verbose
