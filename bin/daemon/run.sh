#!/usr/bin/env bash

./bin/daemon/rabbitmq-cli-consumer \
    -c ./bin/daemon/daemon.conf \
    --executable ./bin/worker/worker.sh  \
    --strict-exit-code
