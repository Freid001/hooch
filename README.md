![](/docs/img/logo.png)

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
    * [Database](./docs/CONNECTIONS.md#database)
        * [Config](./docs/CONNECTIONS.md#config)
        * [Handler](./docs/CONNECTIONS.md#handler)
* [Repositories](./docs/REPOSITORIES.md)
    * [Table](./docs/REPOSITORIES.md#table)
        * [Class](./docs/REPOSITORIES.md#class)
        * [Instantiate](./docs/REPOSITORIES.md#instantiate)
        * [Filters](./docs/REPOSITORIES.md#filters)
* [Query Builder](./docs/QUERY_BUILDER.md)      
    * Select
    * Insert
    * Update
    * Delete
