<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use Stringable;

class Rule implements Stringable {
	public readonly Selector $selector;

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
		$text = '.' . $this->selector->base . $this->selector->pseudo;
		if ($this->selector->prefix)
			$text = $this->selector->prefix . ' ' . $text;
		if ($this->selector->suffix)
			$text = $text . ' ' . $this->selector->suffix;
		$text = "{$text} { {$this->declarations} }";
		if ($this->selector->media)
			return "@media {$this->selector->media} { {$text} }";
		else
			return $text;
	}
}
