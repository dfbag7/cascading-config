Inspired by the [phanan/cascading-config](https://github.com/phanan/cascading-config) package.

## Installation

```bash
composer require dimbo/cascading-config
```

## Usage

Add the `LoadCascadingConfiguration` class to the Kernel's `bootstrappers` section right after `LoadConfiguration`:

```php
    protected $bootstrappers = [
        //...
        \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        \Dimbo\CascadingConfig\LoadCascadingConfiguration::class,
        //...
    ];
```

Remember to do that in both `app\Console\Kernel.php` and `app\Http\Kernel.php`.
