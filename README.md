<img src="docs/img/logo.png" width="375" height="90">

[![Build Status](https://travis-ci.org/blue-strawberry/query-mule.svg?branch=master)](https://travis-ci.org/blue-strawberry/query-mule)

# About
Database abstraction layer (DBAL) and fluent query builder for PHP. Developers can use this package on its own or in conjunction with a framework.

## Features
* Build select, insert, update & delete queries. 
* Use table filters to store & recall query logic. 
* Chain query statements together.
* Switch database connections with ease.
* Query sanitation.
* Supports MySQL, PostgreSQL & SQLite.
* Drivers PDO & MySQLi.

# Installation

## Composer
```json
"repositories": [
        {
            "url": "https://github.com/blue-strawburry/query-mule",
            "type": "git"
        }
    ],
"require": {
        "blue-strawberry/query-mule" : "0.1.0"
},
```

# Documentation
* [Connections](./docs/CONNECTIONS.md)
    * [Database](./docs/CONNECTIONS.md)
        * [Config](./docs/CONNECTIONS.md)
        * [Handler](./docs/CONNECTIONS.md)
* [Tables](./docs/TABLES.md)
    * [Table](./docs/TABLES.md)
        * [Create](./docs/TABLES.md)
        * [Filters](./docs/TABLES.md)

## Query Builder
* Select
* Insert
* Update
* Delete

# Other
CREATE TABLE book(
  id int(11),
  name varchar(225)
)





