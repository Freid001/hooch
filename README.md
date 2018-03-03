<img src="docs/img/logo.png" width="375" height="90">

[![Build Status](https://travis-ci.org/blue-strawberry/query-mule.svg?branch=master)](https://travis-ci.org/blue-strawberry/query-mule)
[![Total Downloads](https://poser.pugx.org/blue-strawberry/query-mule/downloads)](https://packagist.org/packages/blue-strawberry/query-mule)
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE)

# About
Database abstraction layer (DBAL) and fluent query builder for PHP. Developers can use this package on its own or in conjunction with a framework.

## Features
* Build select, insert, update & delete queries. 
* Chain query statements together.
* Use filters to store & recall query logic. 
* Switch database connections with ease.
* PSR simple-cache & logging compatible.
* Supported database drivers: 
    * PDO
    * MySQLi
* Supported databases: 
    * MySQL
    * PostgreSQL
    * SQLite

## Requirements
* PHP >= 7.0
* PDO or MySQLi extension.

# Installation

## via Composer
```bash
composer require blue-strawberry/query-mule
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
    
# Demo
```bash
php demo/run.php
```
