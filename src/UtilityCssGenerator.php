<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use Stringable;

class UtilityCssGenerator implements Stringable {
	/** @property Rule[] $baseRules */
	private array $baseRules = [];

	/**
	 * @param array<Rule | iterable<Rule>> $rules
	 * @param array<callable(Rule): Rule> $variants
	 * @param array<string> $whitelist
	 */
	public function __construct(
		array $rules = [],
		private array $variants = [],
		private string $prefix = 'util',
		private array $whitelist = [],
	) {
		$this->baseRules = array_reduce(
			$rules,
			function($xs, $x) {
				if ($x instanceof Rule)
					array_push($xs, $x->mapSelector($this->addPrefix(...)));
				else
					array_push($xs, ...Iter::map($this->addPrefix(...))($x));
				return $xs;
			},
			[]
		);
	}

	public function __toString(): string {
		$join = Arr::join("\n");
		return (new Pipeline($this->allRules()))
			->map(Arr::groupBy(fn($x) => $x->media))
			->map(Arr::mapWithKeys(fn (string $k, array $g): string =>
				(new Pipeline($g))
					->map(Arr::map(fn($x) => $x->mapSelector(fn($x) => $x->clearMedia())))
					->map($join)
					->map(function ($x) use ($k) {
						if ($k) return "@media {$k} { {$x} }";
						else return $x;
					})
					->return()
			))
			->map($join)
			->return();
	}

	/** @return Rule[] */
	private function allRules(): array {
		return (new Pipeline($this->variants))
			->map(Arr::powerSet(...))
			->map(Iter::bind(
				fn($fs) => Iter::map(fn($x) => $x->mapSelector(Fun::compose($fs)))($this->baseRules))
			)
			->map(Arr::from(...))
			->return();
	}

	private function addPrefix(Selector $x): Selector {
		return $x->mapBase(Str::prefix($this->prefix . '-'));
	}
}
