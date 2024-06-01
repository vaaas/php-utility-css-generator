<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Fun {
	/**
	 * @param array<callable> $fs
	 * @return callable(mixed): mixed
	 */
	public static function compose(array $fs): callable {
		return function ($x) use ($fs) {
			$a = $x;
			foreach (array_reverse($fs) as $f) $a = $f($a);
			return $a;
		};
	}
}
