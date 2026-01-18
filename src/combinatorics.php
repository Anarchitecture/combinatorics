<?php

declare(strict_types=1);

namespace Anarchitecture\combinatorics;

use Closure;
use Generator;
use InvalidArgumentException;

/**
* @param int $total
* @return Closure(iterable<array-key, mixed>) : Generator<array-key, array<array-key, int>>
 */
function iterable_allocations(int $total): Closure {

    if ($total < 0) {
        throw new InvalidArgumentException('iterable_allocations(): $total must be >= 0');
    }

    return static function (iterable $items) use ($total): Generator {

        $items = \is_array($items) ? $items : \iterator_to_array($items, true);

        $iterable_allocations = function (array $items, int $remaining) use (&$iterable_allocations): Generator {
            if ($items === []) {
                if ($remaining === 0) {
                    yield [];
                }

                return;
            }

            $current = \array_key_first($items);
            unset($items[$current]);

            if ($items === []) {
                yield [$current => $remaining];
                return;
            }

            for ($i = 0; $i <= $remaining; $i++) {

                foreach ($iterable_allocations($items, $remaining - $i) as $allocations) {

                    yield [$current => $i] + $allocations;
                }
            }
        };

        yield from $iterable_allocations($items, $total);
    };
}

/**
 * Yield all combinations (subsets) of size $n from the given items (lazy).
 *
 * Preserves original keys.
 *
 * @throws InvalidArgumentException
 * @return Closure(iterable<array-key, mixed>, int): iterable<array-key, array<array-key, mixed>>
 */
function iterable_combinations(int $n): Closure {

    if ($n < 0) {
        throw new InvalidArgumentException("iterable_combinations_n: n must be >= 0");
    }

    return static function (iterable $items) use ($n): Generator {

        $items = \is_array($items) ? $items : \iterator_to_array($items, true);

        $iterable_combinations = static function (array $items, int $n) use (&$iterable_combinations): Generator {

            if ($n === 0) {
                yield [];
                return;
            }

            if ($items === [] || $n > \count($items)) {
                return;
            }

            $k = \array_key_first($items);
            $v = $items[$k];
            unset($items[$k]);

            /** @var array<array-key, mixed> $subset */
            foreach ($iterable_combinations($items, $n) as $subset) {
                yield $subset;
            }

            /** @var array<array-key, mixed> $subset */
            foreach ($iterable_combinations($items, $n - 1) as $subset) {
                yield [$k => $v] + $subset;
            }
        };

        yield from $iterable_combinations($items, $n);
    };
}


/**
 * Generate (lazy) permutation of iterable (consumed)
 *
 * @return Closure(array<array-key, mixed>) : Generator<list<mixed>>
 */
function iterable_permutations() : Closure {

    return static function(iterable $items) : Generator {

        $items = \is_array($items) ? $items : \iterator_to_array($items, true);

        $iterable_permutations = function (array $items) use (&$iterable_permutations): Generator {

            if (count($items) === 0) {
                yield [];
            }

            foreach ($items as $key => $item) {

                $rest = $items;
                unset($rest[$key]);

                foreach ($iterable_permutations($rest) as $permutation) {
                    yield [$key => $item] + $permutation;
                }

            }
        };

        yield from $iterable_permutations($items);
    };
}


/**
 * Yield all possible combinations (subsets) of the given items (lazy).
 *
 * @return Closure(iterable<array-key, mixed>) : iterable<array-key, array<array-key, mixed>>
 */
function iterable_powerset() : Closure {

    return static function (iterable $items) : Generator {

        $items = \is_array($items) ? $items : \iterator_to_array($items, true);

        $iterable_powerset = static function (array $items) use (&$iterable_powerset): Generator {

            if ($items === []) {
                yield [];
                return;
            }

            $k = \array_key_first($items);
            $v = $items[$k];
            unset($items[$k]);

            /** @var array<array-key, mixed> $subset */
            foreach ($iterable_powerset($items) as $subset) {
                yield $subset;
                yield [$k => $v] + $subset;
            }
        };

        yield from $iterable_powerset($items);
    };
}