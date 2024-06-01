<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

/** @template T */
class Pipeline {
	/** @property T $x */

	public function __construct(private $x) {}

	/**
	 * @template U
	 * @param callable(T): U $f
	 * @return Pipeline<U>
	 */
	public function map(callable $f): Pipeline {
		return new Pipeline($f($this->x));
	}

	/** @return T */
	public function return(): mixed {
		return $this->x;
	}
}
