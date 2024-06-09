# Koriym.Loop

[![codecov](https://codecov.io/gh/koriym/Koriym.Loop/branch/master/graph/badge.svg?token=eh3c9AF4Mr)](https://codecov.io/gh/koriym/Koriym.Loop)
[![Type Coverage](https://shepherd.dev/github/koriym/Koriym.Loop/coverage.svg)](https://shepherd.dev/github/koriym/Koriym.Loop)
![Continuous Integration](https://github.com/koriym/Koriym.Loop/workflows/Continuous%20Integration/badge.svg)

Koriym.Loop is a PHP library that converts various iterable data sets, such as database result sets, CSV files, or any other iterable structures, into an entity list generator. This makes it easy to use in views by providing looping information such as isFirst, isLast, index, and iteration, similar to the loop processing found in template engines like Twig.

## Installation

```bash
composer require koriym/loop
```

## Usage

## Basic Example

The following example demonstrates how to use Koriym.Loop with a simple User class and a result set:

```php
class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ){}
}

// Database result sets or csv content
$resultSet = [ 
    ['id' => 1, 'name' => 'ray'],
    ['id' => 2, 'name' => 'bear'],
    ['id' => 3, 'name' => 'alps'],
];

/** @var Generator<Loop, User, mixed, void> $users */
$users = (new LoopGen)($resultSet, User::class);
foreach ($users as $user) {
    echo $user->name;
}
```

### Loop information

Loop information can be accessed from the array keys, providing details such as whether the current item is the first or last in the list:

```php
/** @var Loop $loop */
foreach ($users as $loop => $user) {
    echo match(true) {
        $loop->isFirst && $loop->isLast => "<ul><li>{$user->name}</ul>",
        $loop->isFirst => "<ul><li>{$user->name}",
        $loop->isLast => "<li>{$user->name}</ul>",
        default => "<li>{$user->name}"
    };
}
```

### Loop Properties

| property  | type | Description            |
| --------- | ---- | ---------------------- |
| isFirst   | bool | Is first on the list?  |
| isLast    | bool | Is last on the list?   |
| index     | int  | Loop count of 0 origin |
| iteration | int  | Loop count of 1 origin |

## Injection

Dependent instances can be injected into the entity by specifying them as the third argument.
Specify the key as the name of the parameter and the value as the instance.

```php
$dependencies = [
    $varName => $insntance,
    'dateTime' => new DateTime(), // DateTime instance is injected
];
$users = (new LoopGen)($resultSet, User::class, $dependencies);
```

## Iterator Support

Koriym.Loop supports iterators as well as arrays. Here's an example using a CSV iterator:

```php
class Row
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ){}
}

// Retrieve contents of csv file as list<Row>
$csvIterator = new ogrrd\CsvIterator\CsvIterator($csvFilePath);
/** @var list<Row> $csvRowList */
$csvRowList = (new LoopGen)($csvIterator, Row::class);

foreach ($csvRowList as $loop => $row) {
    if ($loop->isFirst) {
        continue; // Skip header
    }
    echo $row->name;
}
```

This library provides a flexible and convenient way to handle looping through result sets and other iterable data structures in PHP, with additional context information about the loop's state.

