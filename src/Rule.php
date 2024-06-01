<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use Stringable;

class Rule implements Stringable {
	private Selector $selector;

	public function __construct(
		string | Selector $selector,
		private string $declarations
	) {
		if ($selector instanceof Selector)
			$this->selector = $selector;
		else
			$this->selector = new Selector($selector);
	}

	/** @param callable(Selector): Selector $f */
	public function mapSelector(callable $f): Rule {
		return new Rule(
			$f($this->selector),
			$this->declarations,
		);
	}

	public function __toString(): string {
		$parts = [];
		if ($this->selector->prefix) array_push($parts, $this->selector->prefix);
		array_push($parts, '.' . $this->selector->base);
		if ($this->selector->suffix) array_push($parts, $this->selector->suffix);
		$selector = implode(' ', $parts);
		$result = "{$selector} { {$this->declarations} }";
		if ($this->selector->media)
			return "@media {$this->selector->media} { {$result} }";
		else
			return $result;
	}
}
