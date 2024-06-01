<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Iter {
	/**
	 * @template A
	 * @template B
	 * @param callable(A): B $f
	 * @return callable(iterable<A>): iterable<B>
	 */
	public static function map(callable $f): callable {
		return function (iterable $xs) use ($f): iterable {
			foreach ($xs as $x) yield $f($x);
		};
	}

	/**
	 * @template A
	 * @template B
	 * @param callable(A): iterable<B> $f
	 * @return callable(iterable<A>): iterable<B>
	 */
	public static function bind(callable $f): callable {
		return function(iterable $xs) use ($f): iterable {
			foreach ($xs as $x) yield from $f($x);
		};
	}
}
