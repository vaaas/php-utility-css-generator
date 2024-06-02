<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Str {
	/** @return callable(string): string */
	public static function prefix(string $a): callable {
		return function (string $b) use ($a): string {
			return $a . $b;
		};
	}

	/** @return callable(string): string */
	public static function suffix(string $a): callable {
		return function (string $b) use ($a): string {
			return $b . $a;
		};
	}

	/** @return callable(string): string */
	public static function concatWith(string $a, string $join): callable {
		return function (string $b) use ($a, $join): string {
			if (strlen($b) === 0) return $a;
			else return $a . $join . $b;
		};
	}
}
