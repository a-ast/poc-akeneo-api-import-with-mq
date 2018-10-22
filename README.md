# Proof of Concept: product import to Akeneo using REST API and RabbitMQ

## Problem

The CSV import of large datasets is slow and memory-demanding.

## Proposal

The idea is to import data using Akeneo REST API.
The benefit can be achieved by parallelizing API calls.
Multi-core processors can handle more requests than single-core ones.
However, even for single-core CPUs, code running 
across multiple threads can still be faster than single-threaded code.

Additionally to it, REST API calls don't have memory issues 
of long-running PHP processes that use Doctrine.


## Solution

1. Generate product data and publish it to RabbitMQ.
1. Configure queue consumers to send product data to Akeneo REST API.

## PoC Implementation

### Data flow

![Data flow](doc/images/dataflow.png)


### Publishers

Publisher responsibilities:
 
1. Fetch product data from a data provider. 
   Examples of data providers: product generator, API of another system (e.g. legacy PIM), file readers of a specific file format.

   The implemented data provider for this demo application: [FakeDataProvider](src/DataProvider/FakeProductProvider.php)
   (partly implemented).

2. Format product data to the [standard format](https://docs.akeneo.com/2.1/technical_architecture/standard_format/).

3. Pack data to batches of the given size (100 items).

4. Publish data to RabbitMQ.

Example of a message published in a product data queue:

```json
{"identifier":"product-0","family":"clothing","enabled":false,"parent":"product-model-0","groups":[],"categories":[],"values":{"size":[{"locale":null,"scope":null,"data":"s"}]},"associations":[],"created":"2018-10-21T21:23:03+00:00","updated":"2018-10-21T21:23:03+00:00"}
{"identifier":"product-1","family":"clothing","enabled":false,"parent":"product-model-1","groups":[],"categories":[],"values":{"size":[{"locale":null,"scope":null,"data":"m"}]},"associations":[],"created":"2018-10-21T21:23:03+00:00","updated":"2018-10-21T21:23:03+00:00"}
```

Implementation: [AmqpMessagePublisher](src/Publisher/AmqpMessagePublisher.php)

## Consumers

Consumers receive messages from RabbitMQ by subscription.

We know, that PHP is not the best language for the development 
of long-running processes.
Moreover, consumers don't need to implement a specific business logic, 
so, it makes sense to use existing tools instead of inventing the wheel.

One of the widely used utilities of this kind is [RabbitMQ cli consumer](https://github.com/corvus-ch/rabbitmq-cli-consumer).

Usage:

```bash
rabbitmq-cli-consumer \
    --url amqp://guest:guest@rabbitmq:5672 \
    --queue-name create_product_queue \
    --executable "./bin/console poc:product:update --type=product" \
    --strict-exit-code \
    --verbose
```

## Workers

Worker responsibilities:

1. Send product batches to the 
   [PATCH products](https://api.akeneo.com/api-reference.html#patch_products) endpoint of the Akeneo REST API

2. Return correct result of processing to consumer to acknowledge, reject or re-queue messages.

Implementation: [ApiProductBatchUpdater](src/ProductUpdater/ApiProductBatchUpdater.php)


## Current results

I was running the application in my local docker setup (2 CPUs, 5 GB RAM).

* Dataset: 1000 product models, 5000 products.
* Consumers: 1 consumer for product model data, 3 consumers for product data.
* Total time: 6 min.

**TO DO:** compare execution time and memory consumption with CSV import of the very same dataset. 

## Potential improvements

* Messages in RabbitMQ are stored in a format fully compatible with the PATCH API endpoint.
  Current implementation requires to decode messages for Akeneo PHP API client.
  The idea is to replace PHP API client with a custom solution in order to avoid decoding/encoding of json messages.
* Use `supervisord` and experiment with different numbers of consumers.
