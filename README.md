# State pattern for any object in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-stateful.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-stateful)

This package adds state support to non-Eloquent objects in Laravel.

If you need support for Eloquent models, there are excellent alternatives:

- [Eloquent State Machines](https://github.com/asantibanez/laravel-eloquent-state-machines)
- [Model States](https://github.com/spatie/laravel-model-states)

⚠️ Minor releases of this package may cause breaking changes as it has no stable release yet.

## What problem does this package solve?

Usually, when defining "states" or "statuses" on objects, enumeration-like values/objects are used to represent the
current state of the object.

While this approach is pragmatic and good enough for simple use cases, it tends to become a
mess rapidly when complex domain logic has to be incorporated.

This package solves this problem by using the **state pattern**
and the concept of **state machines**.

## Installation

You can install the package via composer:

```bash
composer require dive-be/laravel-stateful
```

## Usage

The best example is a practical example.

### Context

Let's say there is a `CheckoutWizard` class that has many possible states: `AddressSelect`, `ShippingSelect`, `Summary`, `Complete`.
Each of these states should map to the current index of the Wizard in the front-end, and each step has an associated view that will be resolved by Blade.

<p align="center"><img src="https://raw.githubusercontent.com/dive-be/laravel-stateful/master/img/viz.png" width="300"></p>

### Abstract state

That's why first, we should create an abstract `CheckoutState` class and define all possible transitions, as well as the requirements for each state (which view would we like to show, and what is the index of this state?).

It's important to think carefully about the possible transitions: it makes absolutely zero sense to go back to an `AddressSelect` state if the `CheckoutWizard` has reached the `Complete` state. However, it is perfectly fine for the user to go back to a previous step as long as the `Complete` state is not reached (not shown below, though).

Here's what an abstract version of `CheckoutState` might look like:

```php
abstract class CheckoutState extends State
{
    /* You can define which methods need to be implemented for each particular state.
     * For example, you might want each state to have an associated view to resolve. */
    abstract public function view(): string;

    /* Like `view()`, each state should also have an associated index. 
     * We'll enforce that this method needs to be implemented by each class 
     * that inherits from this one. */
    abstract public function step(): int;
    
    /* Set up which state transitions are allowed. 
     * This is required for the states to function as expected. */
    public static function config(): Config
    {
        return parent::config()
            ->allowTransition(AddressSelect::class, ShippingSelect::class)
            ->allowTransition(ShippingSelect::class, Summary::class)
            ->allowTransition(Summary::class, Complete::class);
    }
}
```

Here's what the `AddressSelect` state might look like, which is extending the abstract `CheckoutState`:

```php
class AddressSelect extends CheckoutState
{
    public function view(): string
    { 
        return 'checkout.address_select'; 
    }
    
    public function step(): int
    {
        return 0; 
    }
}
```

### The stateful class

Your stateful classes (classes that will make use of states and possible transitions) should implement the `Stateful` contract and use the `InteractsWithState` trait.
(The latter is optional, but recommended. Adhering to the contract is sufficient.)

Also, do not forget to define the initial state in the constructor.

```php
class CheckoutWizard implements Stateful
{
    use InteractsWithState;
    
    public function __construct() 
    {
        $this->state = AddressSelect::make($this);
    }
    
    // Your code here
}
```

### Transitioning

Let's continue from our `CheckoutWizard` example. To transition to another state, you can do the following:

```php
$checkout = new CheckoutWizard();

$checkout->getState()->transitionTo(Complete::class);
```

Unless the transition is allowed, a `TransitionFailedException` will be thrown preventing impossible state transitions.

### "Custom" transitions

You may define your own `Transition` classes that will have access to the `guard` & `after`/`before` hooks.
Passing the FQCN to `allowTransition` in the config will suffice.

```php
class AdvanceToShipping extends Transition
{
    public function from(): string { return Address::class; }
    
    public function to(): string { return Address::class; }
}
```

```php
public static function config(): Config
{
    return parent::config()->allowTransition(AdvanceToShipping::class);
}
```

Note: when registering a transition without a custom class, a `DefaultTransition` is created for you automatically.

### Guards (validation)

Sometimes, even when a specific transition is allowed, certain conditions may have to be met in order to transition.
In this case, guards may be used to achieve this behavior. A custom `Transition` is compulsory to use this feature.

- Returning `true` from the guard will allow the transition to take place.
- Returning `false` will cause a `TransitionFailedException` to be thrown.

```php
class AdvanceToShipping extends Transition
{
    // omitted for brevity
    
    public function guard(CheckoutWizard $object, MyService $service)
    {
        return $service->isValid($object);
    }
}
```

You can access the stateful object by defining a method argument called `$object`. Any other type-hinted dependency will
be injected by the container using method injection.

Now, every time a transition is attempted, the guard will be executed first.

### Side effects / hooks

You can use the `after` or `before` hooks on the `Transition` class to define methods that should be executed
before or after a certain transition has taken place.

```php
class AdvanceToShipping extends Transition
{
    // omitted for brevity
    
    public function after(CheckoutWizard $object)
    {
        // run immediately after the transition
    }
    
    public function before(CheckoutWizard $object)
    {
        // run just before the transition
    }
}
```

Like any guard, hooks can access the stateful object by defining a method argument called `$object`.
Any other type-hinted dependency will be injected by the container using method injection.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email oss@dive.be instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)
- Spatie for their superb [Model States](https://github.com/spatie/laravel-model-states) package that has heavily inspired this package

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
