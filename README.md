# chocoflake

[![Build Status](https://travis-ci.org/ada-u/chocoflake.svg?branch=master)](https://travis-ci.org/ada-u/chocoflake)
[![Coverage Status](https://img.shields.io/coveralls/ada-u/chocoflake.svg)](https://coveralls.io/r/ada-u/chocoflake?branch=master)

### 64bit ID Generator for PHP

`chocoflake` is an implementation of twitter Snowflake concept. This provides generating IDs based on time in a distributed environment.

### ID Specification

The IDs consist of four elements:

 - timestamp
 - region id
 - server id
 - sequence

You can specify any bit length to each element.

## Usage

### Prerequisites

 - PHP 5.4 or later

### Installation

### Sample

```php
// example:
// 41 bit for timestamp
// 5  bit for region id
// 5  bit for server id
// 12 bit for sequence per milliseconds
// 1414334507356 - service start epoch (unix timestamp)
$config = new IdValueConfig(41, 5, 5, 12, 1414334507356);

$service = new ChocoflakeService($config);

$worker = $service->createIdWorker(new RegionId(1), new ServerId(1));

$id = $worker->generate();
// string(10) "4194439168"
```

### LICENSE
This software is released under the MIT License, see LICENSE
