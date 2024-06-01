<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Arr {
	/**
	 * @template T
	 * @param callable(T, string) $f
	 * @return callable(array<T>): array<string, T>
	 */
	public static function groupBy(callable $f): callable {
		return function(array $xs) use ($f) {
			$grouped = [];
			foreach ($xs as $x) {
				$k = $f($x);
				if (!(array_key_exists($k, $grouped)))
					$grouped[$k] = [];
				array_push($grouped[$k], $x);
			}
			return $grouped;
		};
	}

	/**
	 * @template T
	 * @param array<T> $xs
	 * @return array<array<T>>
	 */
	public static function powerSet(array $xs) {
		$powerset = [[]];
		foreach ($xs as $x)
			foreach ($powerset as $subset)
				array_push($powerset, [...$subset, $x]);
		return $powerset;
	}

	/**
	 * @template A
	 * @template B
	 * @param callable(A): B $f
	 * @return callable(array<A>): array<B>
	 */
	public static function map(callable $f): callable {
		return function (array $xs) use ($f): array {
			return array_map($f, $xs);
		};
	}

	/**
	 * @template A
	 * @template B
	 * @param callable(A): B $f
	 * @return callable(array<string, A>): array<string B>
	 */
	public static function mapWithKeys(callable $f): callable {
		return function (array $xs) use ($f): array {
			return array_map($f, array_keys($xs), array_values($xs));
		};
	}

	public static function join(string $separator = ''): callable {
		return function (array $xs) use ($separator): string {
			return implode($separator, $xs);
		};
	}

	/**
	 * @template T
	 * @param iterable<T> $xs
	 * @return array<T>
	 */
	public static function from(iterable $xs): array {
		return iterator_to_array($xs, false);
	}
}
