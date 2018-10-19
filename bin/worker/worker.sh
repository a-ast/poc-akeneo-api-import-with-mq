#!/usr/bin/env bash

set -ex

token='MzBhYmNjZTJhM2EyMjVmMjk0YzM5MjkxZDg5NzY3OTVmZDZkMmIzZGIwNWNjMTA1NjFkZTFhYzM4NGQ0NTI5Nw'
host="host.docker.internal:8080"

data=$(echo $1 | base64 --decode)

statusCode=$(curl --header "Content-Type: application/json" \
     --header "Authorization:Bearer ${token}" \
     --request POST \
     --data ${data} \
     --write-out %{http_code} \
     --silent --output /dev/null \
     http://${host}/api/rest/v1/products)

echo ${response}

if [[ "${statusCode}" -ne 201 ]] ; then
    exit 3
fi
