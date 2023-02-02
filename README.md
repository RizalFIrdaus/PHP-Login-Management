# Rizal Framework

> About
> The results of learning the basics of PHP programming language, object-oriented programming (OOP), unit testing, web PHP, Composer, MVC, logging, and software development architecture techniques have been documented in this case study for learning purposes only

## Structure Database

We have 2 database : (1) php_login_management and (2) php_login_management_test

Database (1) is original based for raw data, while database (2) is replica from database (1) that contains dirty raw data for used testing

```sql
CREATE DATABASE php_login_management;
CREATE DATABASE php_login_management_test;

CREATE TABLE users(
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)
CREATE TABLE sessions(
    id VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
)
ALTER TABLE session
ADD CONSTRAINT fk_session_user
FOREIGN KEY (user_id)
REFERENCES users(id);

```

## Connect Database

### Built By

Muhammad Rizal Firdaus
