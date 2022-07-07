<?php

use tebazil\dbseeder\Seeder;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}", $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
$seeder = new Seeder($pdo);
$generator = $seeder->getGeneratorConfigurator();
$faker = $generator->getFakerConfigurator();

$seeder->table('users')->columns([
    'id',
    'name' => $faker->name,
    'email'=> $faker->email,
    'password'=> '$2y$10$ZqqPOqjxjSxnJkV7/zYuVuB28zJsQSrQUibcGktffDjkglTz8HZIy' // Hash for Password123456?
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
])->rowQuantity(30);

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