# hashtuner
Framework to automatically suggest settings for password hashing functions, starting with Argon2id.
This library was inspired by [Bryan Burman's article](https://www.twelve21.io/how-to-choose-the-right-parameters-for-argon2/).

# What it does
Various algorithms give us different "levers" to control the cost of computing a hash.

## Argon2id
With a minimum of `3 iterations`, it strives to dominate the cost of password hashing with memory, up to a specified `hard memory limit`, to achieve an execution time within a given range.

Once it achieves 75% of the upper execution time limit, or the `hard memory limit` has been reached, it stops augmenting the memory then tries to get even closer to the upper execution time limit by augmenting iterations.

# Hard Memory Limit
* Estimate a server's available memory for concurrent password-hashing processes, say `8GB`
* Estimate a worst-case scenario for concurrent users logging-in at a given time, say `50`.
* Divite the two: 8GB / 50 = 160MB
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

## Usage with Custom Hard Memory Limit and Desired Execution
```
php ./hastuner.phar 128000 0.8 1.3
```
* Sets your `hard memory limit` to roughly `128 Megabytes`
* Sets your `desired execution time` between `0.8 and 1.3 seconds`

# Usage in a Project: Composer

## Installation

```
composer require elchris/hashtuner
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
