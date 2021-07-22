# chocoflake

[![Build Status](https://travis-ci.org/ada-u/chocoflake.svg?branch=master)](https://travis-ci.org/ada-u/chocoflake)
[![Coverage Status](https://img.shields.io/coveralls/ada-u/chocoflake.svg)](https://coveralls.io/r/ada-u/chocoflake?branch=master)

### 64bit ID Generator for PHP

`chocoflake` is an implementation of twitter Snowflake concept. This provides generating IDs based on time in a distributed environment.

## Usage

### Prerequisites

 - PHP 5.5 or later

### Installation

#### Command

```sh
$ composer.phar require ada-u/chocoflake:dev-master
```

#### composer.json

```json
{
  "require": {
    "ada-u/chocoflake": "dev-master"
  }
}
```

```sh
$ composer.phar install
```

### Sample

Configuration

 - 41 bit        - for timestamp
 - 5  bit        - for region id
 - 5  bit        - for server id
 - 12 bit        - for sequence per milliseconds
 - 1414334507356 - service start epoch (unix timestamp)


```php
$config = new IdConfig(41, 5, 5, 12, 1414334507356);
$service = new ChocoflakeService($config);

$worker = $service->createIdWorkerOnSharedMemory(new RegionId(1), new ServerId(1));

$id = $worker->generate();
4194439168
```

### ID Generator

I implemented two ID generators, Redis and SharedMemory version.


#### SharedMemory version

Using shared memory and semaphore (as mutex) to prevent multiple processes are in the critical section at the same time.


#### Redis version

Using Redis atomic increment operation to count up sequence.


### ID Specification

The IDs consist of four elements:

 - timestamp
 - region id
 - server id
 - sequence

You can specify any bit length to each element.



### License
This software is released under the MIT License, see LICENSE
