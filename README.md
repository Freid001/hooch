<img src="docs/logo.png" width="375" height="90">

[![Build Status](https://travis-ci.org/blue-strawberry/query-mule.svg?branch=master)](https://travis-ci.org/blue-strawberry/query-mule)
[![License](https://img.shields.io/badge/License-BSD%203--Clause-blue.svg)](LICENSE)

# About
Database abstraction layer (DBAL) and fluent query builder for PHP. Developers can use this package on its own or in conjunction with a framework.

## Features
* Build select, insert, update & delete queries. 
* Store and recall query logic.
* Chain query statements.
* Switch database connections with ease.
* Query sanitization.
* PDO & MySQLi are supported drivers.
* Supports MySQL, PostgreSQL & SQLite.

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
        "blue-strawberry/query-mule" : "1.0"
},
```

# Documentation
* Connections
* Tables
* Select Querys
* Insert Querys
* Update Querys
* Delete Querys
* Inegrations



# Other
CREATE TABLE book(
  id int(11),
  name varchar(225)
)






