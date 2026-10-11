# Changelog

All notable changes to this project are documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added

- Pointcut expression language: `execution`, `within`, `this`, `target`, `args`, `bean`, `@annotation`, `@within`, `@target`, `@args`, with `&&`, `||`, `!` and named pointcuts.
- Advice types `#[Before]`, `#[After]`, `#[AfterReturning]`, `#[AfterThrowing]` and `#[Around]`, with `#[Order]` and argument binding.
- Subclass and interface proxy generation, including self-invocation handling through `AopContext`.
- Introductions through `#[DeclareParents]`.
- Per-instance aspects through `#[Aspect(scope: ...)]` (`perthis`, `pertarget`).
- `ProxyFactory` for using the library without a framework.
