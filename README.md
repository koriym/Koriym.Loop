# Koriym.Loop

Converts database result sets into an entity list generator that is handy to use in views.

The entity list can be iterated with information provided such as `isFirst`, `isLast`, etc. It provides similar functionality for native PHP as the loop processing provided by template engines such as Smarty and Twig.

## Usage

```php
class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ){}
}

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

Loop information is obtained from the array keys.

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

`Loop`

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
    $varName => $insntance
];
$users = (new LoopGen)($resultSet, User::class, $dependencies);
```

