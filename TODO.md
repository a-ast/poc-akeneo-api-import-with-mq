# TODO

## Idea

2. Implement generation of models and products
3. Write short post.
4. Confirm with Benoit.
5. Provide code talk.
6. Write blog post.

## Plan

1. Introduce ProductCreator.
1. Finalize product creation with API

1. Check if a php worker is as fast as a shell script. 
   If so, get rid of shell variant.
1. Monitoring.
1. Find better lib for AMQP/rabbit.
    1. http://php.net/manual/fa/book.amqp.php (enable amqp in docker)
    1. https://github.com/php-enqueue/enqueue-dev
1. Learn supervisord.
1. Try to compare with CSV import.


### Upserter

1. log all unrecoverable messages
   // identifier|code, status_code, message, errors


## Timing

Without batch/upserts
20.10.2018: 1000 products - 11 min

Batch/upsert (1 model consumer / 3 product consumers)
22.10.2018: 1000 models / 5000 products - 6 min
