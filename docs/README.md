# Documentation

Developer documentation for `phauthentic/aop`, the framework-free core (namespace `Phauthentic\AOP\`).
The Symfony integration, `phauthentic/symfony-aop`, is documented in its own repository, [Phauthentic/aop-symfony](https://github.com/Phauthentic/aop-symfony/blob/main/docs/README.md).
A complete runnable demo application lives in [Phauthentic/aop-symfony-example](https://github.com/Phauthentic/aop-symfony-example).

## Reading order

Read top to bottom if you are new to the library.
Jump straight to a page if you know what you are looking for.

| # | Page | Read it when |
|---|---|---|
| 1 | [Concepts](concepts.md) | You want the vocabulary (join point, pointcut, advice, aspect, advisor, proxy, weaving) and how a call flows through a proxy. |
| 2 | [Pointcut expressions](pointcuts.md) | You write or debug an expression and need the grammar, every designator, and the pattern rules. |
| 3 | [Advice](advice.md) | You write an aspect and need the five advice types, parameter binding, and execution order. |
| 4 | [Proxies](proxies.md) | You wonder why a method is not advised, which strategy a class gets, or what the generated code looks like. |
| 5 | [Introductions](introductions.md) | You want advised objects to implement an extra interface through `#[DeclareParents]`. |
| 6 | [Standalone usage](standalone.md) | You use the core without a framework through `ProxyFactory`. |
| 7 | [Examples](examples.md) | You want complete, copy-pasteable aspects: logging, validation, retry, caching, interface-proxied final class. |
| 8 | [Architecture](architecture.md) | You contribute to the library and need the package layout, class responsibilities, extension points, and the test setup. |

## Symfony

Installation, configuration, compile-time weaving, `debug:aop` and troubleshooting for the Symfony bundle are in the [bundle documentation](https://github.com/Phauthentic/aop-symfony/blob/main/docs/symfony.md).

## Quick commands

```bash
composer check   # php-cs-fixer, phpstan (level max), phpunit with a coverage gate
```

## Conventions in these pages

Every code example is taken from, or checked against, the code in the package repositories and the demo application.
Where the documentation and the code disagree, the code wins; please open an issue or fix the page.
