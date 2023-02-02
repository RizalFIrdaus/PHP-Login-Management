# LOGIN MANAGEMENT WITH MVC AND REPOSITORY PATTERN

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

## Architecture Software

MVC - Repository Pattern

## Database GetConection

The creation of getConnection uses a singleton architecture, which means creating an object once and using it multiple times. The getConnection function will accept a "prod" or "test" parameter. The default is set to "test"

### Env

```php

function getDataConfig(): array
{
    return [
        "database" => [
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=php_login_management",
                "username" => "root",
                "password" => ""
            ],
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=php_login_management_test",
                "username" => "root",
                "password" => ""
            ]
        ]
    ];
}
```

### getConnection Function

```php
public static function getConnection(string $env = "test"): \PDO
    {
        // Singleton architecture (User single to many action)
        // if pdo null then create new db if not .. just return back pdo had been created
        if (self::$pdo == null) {
            // create new database
            require_once __DIR__ . "/../../env/database.php";
            $config = getDataConfig();
            self::$pdo = new PDO(
                $config["database"][$env]["url"],
                $config["database"][$env]["username"],
                $cofing["database"][$env]["password"]
            );
        }
        return self::$pdo;
    }
```

### Testing Connection

During testing, two data connection tests will be performed to ensure that the data is not null and to verify that the implemented singleton architecture is functioning properly.

```php
    public function testConnection()
    {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

    public function testSingletonDatabase()
    {
        $con1 = Database::getConnection();
        $con2 = Database::getConnection();
        self::assertSame($con1, $con2);
    }
```

The Result

```shell
    PHPUnit 9.5.8 by Sebastian Bergmann and contributors.
..                                                                  2 / 2 (100%)
Time: 00:00.054, Memory: 4.00 MB
OK (2 tests, 2 assertions)
```

## View Templating

Templating is used to separate the header and footer for clean code in the visual aspect. By separating the header, footer, and body, we only need to import the header and footer while the body is always changing

```php
    public static function render(string $view, $model)
    {
        require __DIR__ . "/../View/Layouts/header.php";
        require __DIR__ . '/../View/' . $view . '.php';
        require __DIR__ . "/../View/Layouts/footer.php";
    }
```

### Built By

Muhammad Rizal Firdaus
