# Login Management (Architecture MVC - Repository Pattern)

> About
> The results of learning the basics of PHP programming language, object-oriented programming (OOP), unit testing, web PHP, Composer, MVC, logging, and software development architecture techniques have been documented in this case study for learning purposes only

## Structure Database

We have 2 databases : (1) php_login_management and (2) php_login_management_test

Database (1) is original based for raw data, while database (2) is
replica from database (1) that contains dirty raw data
for used testing

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

### Testing View

This testing will check the expected regex displayed on the website page.

```php
    public function testRender()
    {
        View::render("Home/index", [
            "title" => "Login"
        ]);
        self::expectOutputRegex("[Login]");
        self::expectOutputRegex("[Register]");
    }
```

The Result

```shell
PHPUnit 9.5.8 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.067, Memory: 4.00 MB

OK (1 test, 1 assertion)
```

## Repository

This architecture uses the MVC architecture and is combined with the Repository Pattern to avoid overwhelming logic in the MVC Controller. The Controller will call the Service, the Service
will call the Repository, and the Repository will retrieve data from the Domain and
directly access the database

### Domain

The Domain represents the table data in the database.

```php
class User
{
    private string $id;
    private string $name;
    private string $password;

    // SETTER AND GETTER
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
```

### User Repository

The User Repository has several methods such as save for saving data into the database, getById for retrieving data based on Id, and deleteAll for testing purposes

```php
class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    // Save user into database
    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users(id,name,password) VALUES (?,?,?)");
        $statement->execute([
            $user->getId(),
            $user->getName(),
            $user->getPassword()
        ]);
        return $user;
    }

    public function getById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT id,name,password FROM users WHERE id=?");
        $statement->execute([$id]);

        // Trying to fetch data from id
        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->setId($row["id"]);
                $user->setName($row["name"]);
                $user->setPassword($row["password"]);
                return $user;
            } else {
                return null;
            }
        } finally {
            // Close Query
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM users");
    }
}

```

### Testing User Repository

Testing the User Repository will check if method user has saved data into the database and
the second one will check if method getById is null

```php
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->setId("rizal");
        $user->setName("rizal");
        $user->setPassword("rahasia");

        $this->userRepository->save($user);
        $result = $this->userRepository->getById($user->getId());
        self::assertNotNull($result);
        self::assertEquals($user->getId(), $result->getId());
    }

    public function testIdNotFound()
    {
        $user = $this->userRepository->getById("awdawd");
        self::assertNull($user);
    }

```

The Result

```shell
PHPUnit 9.5.8 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 00:00.033, Memory: 4.00 MB

OK (2 tests, 3 assertions)
```

### Built By

Muhammad Rizal Firdaus
