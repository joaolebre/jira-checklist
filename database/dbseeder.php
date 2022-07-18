<?php

use tebazil\dbseeder\Seeder;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$seeder = new Seeder($pdo);
$generator = $seeder->getGeneratorConfigurator();
$faker = $generator->getFakerConfigurator();

// Create database tables

$statements = [
        "CREATE TABLE users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );",
        'CREATE TABLE tickets (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );',
        'CREATE TABLE tabs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        ticket_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        position INT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (ticket_id) REFERENCES tickets(id)
    );',
        'CREATE TABLE sections (
        id INT PRIMARY KEY AUTO_INCREMENT,
        tab_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        position INT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tab_id) REFERENCES tabs(id)
    );',
        'CREATE TABLE item_statuses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        label VARCHAR(255) NOT NULL,
        color VARCHAR(255) NOT NULL
    );',
        "INSERT INTO item_statuses (label, color)
        VALUES ('To Do', 'gray'),
        ('In Progress', 'blue'),
        ('Blocked', 'red'),
        ('Skipped', 'yellow'),
        ('Completed', 'green'
    );",
        'CREATE TABLE items (
        id INT PRIMARY KEY AUTO_INCREMENT,
        section_id INT NOT NULL,
        status_id INT NOT NULL DEFAULT 1,
        summary TEXT NOT NULL,
        is_checked TINYINT(1) NOT NULL DEFAULT 0,
        is_important TINYINT(1) NOT NULL DEFAULT 0,
        position INT NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (section_id) REFERENCES sections(id),
        FOREIGN KEY (status_id) REFERENCES item_statuses(id)
    );'
];

foreach ($statements as $statement) {
    $pdo->exec($statement);
}

// Populate tables with data

$seeder->table('users')->columns([
    'id',
    'name' => $faker->name,
    'email'=> $faker->email,
    'password'=> '$2y$10$ZqqPOqjxjSxnJkV7/zYuVuB28zJsQSrQUibcGktffDjkglTz8HZIy', // Hash for Password123456?
    'role' => $faker->randomElement(['admin', 'user'])
])->rowQuantity(30);

$seeder->table('tickets')->columns([
    'id',
    'user_id' => $generator->relation('users', 'id'),
    'title' => $faker->text(20),
    'description'=> $faker->text
])->rowQuantity(10);

$seeder->table('tabs')->columns([
    'id',
    'ticket_id' => $generator->relation('tickets', 'id'),
    'name' => $faker->word,
    'position'=> $faker->unique()->numberBetween(1, 200)
])->rowQuantity(20);

$seeder->table('sections')->columns([
    'id',
    'tab_id' => $generator->relation('tabs', 'id'),
    'name' => $faker->word,
    'position'=> $faker->unique()->numberBetween(1, 200)
])->rowQuantity(30);

$seeder->table('items')->columns([
   'id',
   'section_id' => $generator->relation('sections', 'id'),
   'status_id' => $faker->numberBetween(1, 6),
    'summary' => $faker->text(100),
    'is_checked' => $faker->boolean(),
    'is_important' => $faker->boolean(25),
    'position' => $faker->unique()->numberBetween(1, 200)
])->rowQuantity(100);

try {
    $seeder->refill();
} catch (Exception $e) {
    echo $e->getMessage();
}