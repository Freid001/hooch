# QueryMule

[![Build Status](https://travis-ci.org/blue-strawberry/query-mule.svg?branch=master)](https://travis-ci.org/blue-strawberry/query-mule)
[![Maintainability](https://api.codeclimate.com/v1/badges/407b96ee7766eb73ba22/maintainability)](https://codeclimate.com/github/blue-strawberry/query-mule/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/407b96ee7766eb73ba22/test_coverage)](https://codeclimate.com/github/blue-strawberry/query-mule/test_coverage)
[![Latest Stable Version](https://poser.pugx.org/blue-strawberry/query-mule/v/stable)](https://packagist.org/packages/blue-strawberry/query-mule)
[![Total Downloads](https://poser.pugx.org/blue-strawberry/query-mule/downloads)](https://packagist.org/packages/blue-strawberry/query-mule)
[![Latest Unstable Version](https://poser.pugx.org/blue-strawberry/query-mule/v/unstable)](https://packagist.org/packages/blue-strawberry/query-mule)
[![License](https://poser.pugx.org/blue-strawberry/query-mule/license)](https://packagist.org/packages/blue-strawberry/query-mule)

## About
Database abstraction layer (DBAL) and fluent query builder for PHP. Developers can use this package on its own or in conjunction with a framework.

### Features
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

### Requirements
* PHP >= 7.0
* PDO or MySQLi extension.

## Installation

### via Composer
```bash
composer require blue-strawberry/query-mule
```

## Documentation
https://blue-strawberry.github.io/query-mule/