# Phauthentic AOP

Aspect-oriented programming for PHP 8.3+.
Pointcut expressions, advice types and ordering follow Spring AOP, adapted to PHP.

This repository is `phauthentic/aop`, the framework-free core: pointcut language, advice, proxy generation.
It also holds the documentation for both packages.

- [Phauthentic/symfony-aop](https://github.com/Phauthentic/symfony-aop) is the Symfony bundle: aspect discovery, compile-time weaving, `debug:aop`.
- [floriankraemer/symfony-aop](https://github.com/floriankraemer/symfony-aop) is a runnable demo application.

## Install

Requires PHP 8.3 or later.

```bash
composer require phauthentic/aop
```

The bundle additionally requires Symfony 7.2 components and Composer 2.
The packages are not on Packagist yet, so install the bundle through its VCS repository:

```bash
composer config repositories.aop vcs git@github.com:Phauthentic/aop.git
composer config repositories.symfony-aop vcs git@github.com:Phauthentic/symfony-aop.git
composer require phauthentic/aop:dev-main phauthentic/symfony-aop:dev-main
```

Register `Phauthentic\AOP\Symfony\PhauthenticAopBundle` in `config/bundles.php` and add `config/packages/phauthentic_aop.yaml`:

```yaml
phauthentic_aop:
    include: ['App\**']
    # strip_final: false   # opt-in and risky: subclass proxies for final classes, see docs/symfony.md
```

## Quick start with Symfony

```php
use Phauthentic\AOP\Attribute\{Aspect, Before};
use Phauthentic\AOP\JoinPoint\JoinPointInterface;

#[Aspect]
final class LoggingAspect
{
    public function __construct(private readonly LoggerInterface $logger) {}

    #[Before('execution(* App\Service\**::*(..))')]
    public function before(JoinPointInterface $jp): void
    {
        $this->logger->info('calling '.$jp->toShortString());
    }
}
```

Services in `App\Service` are now woven.
No further configuration is needed.

```bash
bin/console debug:aop      # what is advised, and by whom
```

## Quick start without a framework

```php
use Phauthentic\AOP\Attribute\{Aspect, Before};
use Phauthentic\AOP\JoinPoint\JoinPointInterface;
use Phauthentic\AOP\Proxy\ProxyFactory;

#[Aspect]
final class TraceAspect
{
    #[Before('execution(* Greeter::*(..))')]
    public function before(JoinPointInterface $jp): void
    {
        echo 'calling '.$jp->toShortString().PHP_EOL;
    }
}

class Greeter
{
    public function greet(string $name): string
    {
        return "Hello $name";
    }
}

$factory = ProxyFactory::fromAspects([new TraceAspect()]);
$greeter = $factory->newInstance(Greeter::class);
echo $greeter->greet('World');
```

See [Standalone usage](docs/standalone.md) for the interface strategy, caching and the full `ProxyFactory` API.

## Spring to PHP

| Spring | Phauthentic AOP |
|---|---|
| `@Aspect` | `#[Aspect]` |
| `@Order` | `#[Order(int)]`, lower runs outermost |
| `@Pointcut` | `#[Pointcut('...')]` on a method, referenced as `Class::name()` |
| `@Before`, `@After`, `@Around` | `#[Before]`, `#[After]`, `#[Around]` |
| `@AfterReturning(returning=)` | `#[AfterReturning(pointcut, returning:)]` |
| `@AfterThrowing(throwing=)` | `#[AfterThrowing(pointcut, throwing:)]` |
| `JoinPoint`, `ProceedingJoinPoint` | `JoinPointInterface`, `ProceedingJoinPointInterface` |
| `execution(* com.x..*.*(..))` | `execution(* App\X\**::*(..))` |
| `within`, `this`, `target`, `args`, `bean` | same |
| `@annotation`, `@within`, `@target`, `@args` | same, matching PHP attributes |
| `argNames` | not needed, PHP reflection provides parameter names |
| `AopContext.currentProxy()` | `AopContext::currentProxy()` |
| JDK dynamic proxy | interface proxy |
| CGLIB proxy | subclass proxy |
| Introduction, `@DeclareParents` | `#[DeclareParents(typePattern, interface, defaultImpl)]` on the aspect class |
| `perthis`, `pertarget` | `#[Aspect(scope: AspectScope::PER_THIS)]`, `#[Aspect(scope: AspectScope::PER_TARGET)]` |

## Proxy strategies

- Subclass: a generated subclass overrides the advised methods.
  Used for non-final classes.
  Self-invocation is advised.
- Interface: a generated class implements the same interface and delegates to the target.
  Used for final classes that implement an interface.
  Inject the interface.
  Self-invocation is not advised.

Both are written to the kernel build directory (`var/cache/<env>/phauthentic_aop/` by default) and never touch your `src/` directory.

## Limitations

- Only method execution is a join point.
- Interface proxies skip self-invocation; use `AopContext::currentProxy()`, the Symfony bundle's `debug:aop` lists the affected calls.
- Methods returning by reference are not advised; methods with by-reference parameters are.
- Private, static, final (subclass strategy) and magic methods are not advised.

## Documentation

Start at the [documentation index](docs/README.md).

- [Concepts](docs/concepts.md): terminology, Spring mapping, invocation and weaving flows
- [Pointcut expressions](docs/pointcuts.md): grammar, designators, patterns, binding, errors
- [Advice](docs/advice.md): the five advice types, parameters, ordering
- [Proxies](docs/proxies.md): strategies, eligibility, generated code, limitations
- [Introductions](docs/introductions.md): `#[DeclareParents]`, introduced interfaces and their default implementations
- [Symfony bundle](docs/symfony.md): installation, configuration, weaving, `debug:aop`, troubleshooting
- [Standalone usage](docs/standalone.md): `ProxyFactory` without Symfony
- [Examples](docs/examples.md): logging, validation, retry, caching, interface-proxied final class
- [Architecture](docs/architecture.md): package layout, extension points, tests

## Development

```bash
composer check   # php-cs-fixer, phpstan max, phpunit with a coverage gate (needs Xdebug)
```
