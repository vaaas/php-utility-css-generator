<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use Stringable;

class Rule implements Stringable {
	public readonly Selector $selector;

	/** @param array<string> $declarations */
	public readonly array $declarations;

	public function __construct(
		string | Selector $selector,
		string ...$declarations,
	) {
		if ($selector instanceof Selector)
			$this->selector = $selector;
		else
			$this->selector = new Selector($selector);
		$this->declarations = $declarations;
	}

	/** @param callable(Selector): Selector $f */
	public function mapSelector(callable $f): Rule {
		return new Rule(
			$f($this->selector),
			...$this->declarations,
		);
	}

	public function __toString(): string {
		$text = '.' . $this->selector->base . $this->selector->pseudo;
		if ($this->selector->prefix)
			$text = $this->selector->prefix . ' ' . $text;
		if ($this->selector->suffix)
			$text = $text . ' ' . $this->selector->suffix;
		$declarations = implode(' ', array_map(Str::suffix(';'), $this->declarations));
		$text = "{$text} { {$declarations} }";
		if ($this->selector->media)
			return "@media {$this->selector->media} { {$text} }";
		else
			return $text;
	}
}
