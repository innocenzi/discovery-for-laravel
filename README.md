<h1 align="center">Discovery for Laravel</h1>
<p align="center">
Automatically locate controller actions, console commands, configuration files and other components of your application without relying on filesystem-based conventions nor manual configuration.
</p>
<p align="center">
  <pre><div align="center">composer require innocenzi/discovery-for-laravel</div></pre>
</p>

&nbsp;

## What it does

If you ever wanted to architecture your application however you want, for instance by using modules or vertical slices, you probably had to give up on some of Laravel's conveniences, such as automatic Artisan command registration.

This package brings [Tempest's discovery](https://tempestphp.com/3.x/essentials/discovery) into Laravel applications, which allows for:

- Registering routes from controller method attributes,
- Registering artisan commands by discovering command classes,
- Loading modular config files ending with `.config.php`,
- Registering container bindings through dedicated initializer classes,
- Registering global middleware using attributes.

&nbsp;

## Installation

Install via Composer:

```bash
composer require innocenzi/discovery-for-laravel
```

Optionally, publish the configuration file when you want to customize behavior:

```bash
php artisan vendor:publish --provider="Innocenzi\Discovery\DiscoveryServiceProvider"
```

&nbsp;

## Production

To avoid any performance overhead in production, it's important to create a discovery cache. You may do so by adding `php artisan discovery:generate` to your deployment script.

```bash
php artisan discovery:generate
```

&nbsp;

## Discoveries

This package comes with a set of default discoveries. They can be disabled individually by updating the `skip_classes` option in `config/discovery.php`.

By default, we provide the following:

- `\Innocenzi\Discovery\CommandsDiscovery`
- `\Innocenzi\Discovery\ConfigDiscovery`
- `\Innocenzi\Discovery\InitializerDiscovery`
- `\Innocenzi\Discovery\RoutesDiscovery`
- `\Innocenzi\Discovery\MiddlewareDiscovery`

&nbsp;

### Artisan commands

Any class extending `Illuminate\Console\Command` is discovered and registered, no matter where it's placed. It does not have to be in the `app/Console/Commands` directory.

```php
namespace Module\Reports;

use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('reports:prune')]
final class PruneReportsCommand extends Command
{
    public function handle(): int
    {
        // ...

        return self::SUCCESS;
    }
}
```

&nbsp;

### Configuration files

Any file ending in `<name>.config.php` will be registered under the `<name>` namespace. This is useful to co-locate configuration files with the components they configure.

```php
// src/Modules/Billing/billing.config.php
return [
    'enabled' => true,
    'retry_attempts' => 3,
];
```

The example above becomes available as `config('billing.enabled')`.

&nbsp;

### Dependency initializers

This package provides [dependency initializers](https://tempestphp.com/3.x/essentials/container#dependency-initializers), a concept borrowed from Tempest. Initializers are simple classes responsible for initializing and configuring a specific dependency.

In a typical Laravel application, this is done in service providers. Using this package, you can create a class implementing `Innocenzi\Discovery\Container\Initializer` anywhere (preferably, near related code), and it will be automatically discovered and registered in the container.

```php
namespace Modules\Strip;

use Innocenzi\Discovery\Container\Initializer;
use Illuminate\Container\Attributes\Singleton;
use Psr\Container\ContainerInterface;

#[Singleton]
final class StripeClientInitializer implements Initializer
{
    public function initialize(ContainerInterface $container): StripeClient
    {
        return new StripeClient(
            key: config('services.stripe.key'),
        );
    }
}
```

To register the initialized service as a singleton, add the built-in `Illuminate\Container\Attributes\Singleton` attribute on the initializer class.

&nbsp;

### Routing

One of the best features of discovery is the ability to register routes from attributes on controller methods.

This is something that Spatie [implemented](https://github.com/spatie/laravel-route-attributes) when PHP attributes were initially released, but for some reason, this practice was never really popular in the Laravel ecosystem.

If you want to see the routes your application has, you can use `php artisan route:list` as usual. With attribute-based routes, you no longer need to go back-and-forth between your `routes/web.php` and your controllers—and for large applications, you no longer have to deal with gigantic route files.

This example declares two routes: `GET /billing/invoices` and `POST /billing/invoices`:

```php
namespace Module\Billing;

use Innocenzi\Discovery\Routing\Api;
use Innocenzi\Discovery\Routing\Get;
use Innocenzi\Discovery\Routing\Post;
use Innocenzi\Discovery\Routing\Prefix;
use Innocenzi\Discovery\Routing\Web;

#[Prefix(name: 'billing', uri: 'billing')]
final class InvoiceController
{
    #[Get(uri: 'invoices', name: 'index'), Web]
    public function index()
    {
        // ...
    }

    #[Post(uri: 'invoices', name: 'store'), Web]
    public function store()
    {
        // ...
    }
}
```

#### Decorators

The `#[Prefix]` and `#[Web]` attributes in the example above are route decorators—another concept [borrowed from Tempest](https://tempestphp.com/3.x/essentials/routing#route-decorators).

A decorator is a class that modifies the underlying route definition in some way. In this case, the `Web` decorator pushes the route into the `web` middleware group, and the `Prefix` decorator adds a URI prefix and a name prefix to all routes in the controller.

They are a great alternative to the `group` method of Laravel's router. This is how the `Web` one is implemented:

```php
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Web implements RouteDecorator
{
    public function decorate(Route $route): Route
    {
        $route->middleware[] = 'web';

        return $route;
    }
}
```

&nbsp;

### Global middleware

Classes annotated with `Innocenzi\Discovery\Routing\Middleware` are pushed into the selected middleware group. Again, this class may be located anywhere in the application, and it will be automatically discovered and registered.

```php
namespace Infrastructure;

use Innocenzi\Discovery\Routing\Middleware;

#[Middleware('api')]
final class TraceRequestId
{
    public function __invoke(mixed $request, \Closure $next): mixed
    {
        // ...

        return $next($request);
    }
}
```

&nbsp;

## Troubleshooting

- Discovery relies on reflection and filesystem scanning. If you don't want some files to be loaded at discovery time, you may adapt `skip_matches` in `config/discovery.php` to exclude them.
