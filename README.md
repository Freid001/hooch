<img src="docs/logo.png" width="375" height="90">

[![Build Status](https://travis-ci.org/blue-strawburry/query-mule.svg?branch=master)](https://travis-ci.org/blue-strawburry/query-mule)

## About
Database abstraction layer (DBAL) and fluent query builder for PHP. Developers can use this package on its own or in conjunction with a framework.

### Features
* Build select, insert, update & delete queries. 
* Store and recall query logic.
* Chain query statements.
* Switch database connections with ease.
* Query sanitization.
* Supported drivers PDO & MySQLi.
* Supports MySQL, PostgreSQL & SQLite.

### Contributing
Here are a few rules to follow in order to ease code reviews, 
and discussions.
 
* You MUST follow the PSR-1 and PSR-2 coding standards.  
* You MUST run the test suite.
* You MUST write (or update) unit tests.
* You SHOULD write documentation.
* Please, write commit messages that make sense, and rebase your branch before submitting your Pull Request.
* When creating your Pull Request on GitHub, you MUST write a description which gives the context and/or explains why you are creating it.


## Installation

### Composer
```json
"repositories": [
        {
            "url": "https://github.com/freidcreations/query-mule",
            "type": "git"
        }
    ],
"require": {
        "freidcreations/query-mule" : "1.0"
},
```

## Documentation
* Connections
* Tables
* Select Querys
* Insert Querys
* Update Querys
* Delete Querys
* Inegrations



### Other
CREATE TABLE book(
  id int(11),
  name varchar(225)
)






