#!/usr/bin/env bash

set -ex

token='OTMxYTIyNTNjNzA4OWNkYWNmYzMyNDU3Y2MwOWQxNTlmZjE0ZTNmYmM5ZDBmNDdmMWRmYzgyNzJmMzgzMDk4ZA'
host="host.docker.internal:8080"

data=$(echo $1 | base64 --decode)

statusCode=$(curl --header "Content-Type: application/json" \
     --header "Authorization:Bearer ${token}" \
     --request POST \
     --data ${data} \
     --write-out %{http_code} \
     --silent --output /dev/null \
     http://${host}/api/rest/v1/products)

if [[ "${statusCode}" -eq 422 ]] ; then
    exit 4
fi

if [[ "${statusCode}" -ne 201 ]] ; then
    exit 3
fi
