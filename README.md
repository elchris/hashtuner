# hashtuner
Framework to automatically suggest settings for password hashing functions, starting with Argon2id.
This library was inspired by [Bryan Burman's article](https://www.twelve21.io/how-to-choose-the-right-parameters-for-argon2/).

It requires php 7.3 or greater.

[![Maintainability](https://api.codeclimate.com/v1/badges/7dab2d6867ce7b0c6a6e/maintainability)](https://codeclimate.com/github/elchris/hashtuner/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7dab2d6867ce7b0c6a6e/test_coverage)](https://codeclimate.com/github/elchris/hashtuner/test_coverage)
[![CircleCI](https://circleci.com/gh/elchris/hashtuner.svg?style=svg)](https://circleci.com/gh/elchris/hashtuner)

# What it does
Various algorithms give us different "levers" to control the cost of computing a hash.

## Argon2id
With a minimum of `3 iterations`, it strives to dominate the cost of password hashing with memory, up to a specified `hard memory limit`, to achieve an execution time within a given range.

Once it achieves 75% of the upper execution time limit, or the `hard memory limit` has been reached, it stops augmenting the memory then tries to get even closer to the upper execution time limit by augmenting iterations.

For the time-being, "`threads`" are locked-down to `1`, because the libsodium integration with password_hash won't accept a value other than `1`. - Insert link to article explaining this behavior here -

# Hard Memory Limit
* Estimate a server's available memory for concurrent password-hashing processes, say `8GB`
* Estimate a worst-case scenario for concurrent users logging-in at a given time, say `50`.
* Divide the two: 8GB / 50 = 160MB
* `Hard Memory Limit` should be set to `160000` aka 160,000KB

# Quickest Usage with Phar File
* Download [hashtuner.phar](https://github.com/elchris/hashtuner/blob/master/hashtuner.phar)
* scp it to your server

## Usage with Defaults
```
php ./hashtuner.phar
```

* Assumes your `hard memory limit` is the value of "`mem_limit`" in php.ini
  * in most instances, this is way too high.
* Assumes a `desired execution time` between `0.5 and 1.0 seconds`

## Usage with Custom Hard Memory Limit
```
php ./hastuner.phar 128000
```
* Sets your `hard memory limit` to roughly `128 Megabytes`
* Assumes a `desired execution time` between `0.5 and 1.0 seconds`

## Usage with Custom Hard Memory Limit and Desired Execution Time
```
php ./hastuner.phar 128000 0.8 1.3
```
* Sets your `hard memory limit` to roughly `128 Megabytes`
* Sets your `desired execution time` between `0.8 and 1.3 seconds`

# Usage in a Project: Composer

## Installation

```
composer require chrisholland/hashtuner
```

## API

```
(new ArgonTuner())->getTunedSettings()->toJson()
```

```
(new ArgonTuner())->getTunedSettingsForMemoryLimit(128000)->toJson()
```

```
(new ArgonTuner())->getTunedSettingsForSpeedAndMemoryLimit(
0.5,
1.0,
128000
)->toJson()
```

# More Reading
## Symfony

* [Native Password Hasher in Symfony 4.3](https://symfony.com/blog/new-in-symfony-4-3-native-password-encoder)
* [Adding Password Rehashing Capibilities in Symfony](https://github.com/symfony/symfony/pull/31153)

## Other Links

* [Paragonie's Argon2 Refiner](https://github.com/paragonie/argon2-refiner)
* [Modern Hashing Algorithms](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html#modern-algorithms)

# Coding Practices & Contributions

Contributions are welcome and gratefuly appreciated. Please ensure that every commit includes a new test signaling intent, or a fix to an existing test to correct erroneous or missing assumptions.

## Standards
* [Style](https://github.com/squizlabs/PHP_CodeSniffer): [PSR-2](https://www.php-fig.org/psr/psr-2/)
* [PHPStan](https://github.com/phpstan/phpstan): Level 7

## Driving Code, with Tests
Aside from src/index.php, 100% of this code was driven by tests, which is how I achieved "100% coverage". Having said this, I don't test-drive code to achieve any "code coverage" percentage, I only do it to more quickly understand what I'm trying to deliver, more easily arrive at a solution, and in the end deliver better software, faster than I would without doing any testing whatsoever. A high "code coverage" percentage just happens to be a mere byproduct of this process.









