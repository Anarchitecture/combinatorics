# anarchitecture/combinatorics

[![CI](https://github.com/Anarchitecture/combinatorics/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/Anarchitecture/combinatorics/actions/workflows/ci.yml) [![Latest Version](https://img.shields.io/packagist/v/anarchitecture/combinatorics)](https://packagist.org/packages/anarchitecture/combinatorics) [![License](https://img.shields.io/packagist/l/anarchitecture/combinatorics)](https://github.com/Anarchitecture/combinatorics/blob/main/LICENSE)


Combinatorics helpers for functional pipelines in **PHP 8.5+**, designed to pair nicely with the pipe operator (`|>`) and the `anarchitecture/pipe` library.

This package intentionally focuses on _search-space_ helpers (often exponential). Keep your core FP primitives in a smaller library, and pull these in when you need them.

## Install

```bash
composer require anarchitecture/combinatorics
```

## Usage

```php
<?php

use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

$items = ['a' => 1, 'b' => 2];

$subsets = $items
    |> c\iterable_powerset()
    |> p\collect(...)

// [
//  [],
//  ['a'=>1],
//  ['b'=>2],
//  ['a'=>1,'b'=>2]
// ]
```

## Functions

### `iterable_combinations(int $n)`

Yield all subsets of **exactly** size `$n` from an iterable. Output is lazy; input is materialized into an array first.

- Preserves the original keys in each subset.
- Yields `C(count(items), n)` combinations (binomial coefficient).
- For `$n = 0`, yields the empty subset only: `[[]]`.
- Throws `InvalidArgumentException` if `$n < 0`.

```php
use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

$items = ['a' => 1, 'b' => 2, 'c' => 3];

$pairs = $items
    |> c\iterable_combinations(2)
    |> p\collect(...);

// [
//   ['b' => 2, 'c' => 3],
//   ['a' => 1, 'c' => 3],
//   ['a' => 1, 'b' => 2],
// ]
```

### `iterable_powerset()`

Yield all subsets (the power set) of an iterable. Output is lazy; input is materialized into an array first.

- Yields **2^n** subsets, including the empty subset `[]`.
- Preserves the original keys in each subset.

### `iterable_permutations()`

Yield all permutations of an iterable (materialized into an array). Output is lazy.

- Yields **n!** permutations.
- Preserves keys (keys stay attached to values; order changes).

### `iterable_allocations(int $total)`

Given a list of items (keys), yield all allocations of a non-negative integer total across those keys.

- Each yielded allocation is an array keyed by the input keys, with non-negative integer values summing to `$total`.
- Yields `C(total + n - 1, n - 1)` allocations (`n = number of items`).

Generate all non-negative integer allocations that sum to a fixed total.

```php
use Anarchitecture\combinatorics as c;
use Anarchitecture\pipe as p;

$allocations = ['a' => null, 'b' => null, 'c' => null]
    |> c\iterable_allocations(2)
    |> p\collect(...);

// [
//   ['a' => 0, 'b' => 0, 'c' => 2],
//   ['a' => 0, 'b' => 1, 'c' => 1],
//   ['a' => 0, 'b' => 2, 'c' => 0],
//   ['a' => 1, 'b' => 0, 'c' => 1],
//   ['a' => 1, 'b' => 1, 'c' => 0],
//   ['a' => 2, 'b' => 0, 'c' => 0],
// ]
```

## License

MIT
