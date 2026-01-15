<?php

declare(strict_types=1);

namespace Anarchitecture\combinatorics\Tests;

use PHPUnit\Framework\TestCase;
use Anarchitecture\pipe as p;

use function Anarchitecture\combinatorics\iterable_permutations;

final class IterablePermutationsTest extends TestCase
{
    public function test_empty_iterable_yields_one_empty_permutation() : void
    {
        $result = []
            |> iterable_permutations()
            |> p\collect(...);

        self::assertSame([[]], $result);
    }

    public function test_single_item() : void
    {
        $result = ['x' => 1]
            |> iterable_permutations()
            |> p\collect(...);

        self::assertSame([['x' => 1]], $result);
    }

    public function test_two_items_preserves_keys_and_expected_order() : void
    {
        $result = ['a' => 1, 'b' => 2]
            |> iterable_permutations()
            |> p\collect(...);

        self::assertSame(
            [
                ['a' => 1, 'b' => 2],
                ['b' => 2, 'a' => 1],
            ],
            $result
        );
    }

    public function test_works_with_generator_input() : void
    {
        $items = (function () {
            yield 'a' => 1;
            yield 'b' => 2;
        })();

        $result = $items
            |> iterable_permutations()
            |> p\collect(...);

        self::assertSame(
            [
                ['a' => 1, 'b' => 2],
                ['b' => 2, 'a' => 1],
            ],
            $result
        );
    }
}
