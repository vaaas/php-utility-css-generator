<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use Stringable;

class UtilityCssGenerator implements Stringable {
	/** @property Rule[] $baseRules */
	private array $baseRules = [];

	/** @property array<string, true> */
	private array $whitelist = [];

	/**
	 * @param array<Rule | iterable<Rule>> $rules
	 * @param array<callable(Rule): Rule> $variants
	 * @param array<string> $whitelist
	 */
	public function __construct(
		array $rules = [],
		private array $variants = [],
		private string $prefix = 'util',
		array $whitelist = [],
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
		$this->whitelist = array_reduce(
			$whitelist,
			function($xs, $x) {
				$xs[$x] = true;
				return $xs;
			},
			[],
		);
	}

	public function __toString(): string {
		$join = Arr::join("\n");
		return (new Pipeline($this->allRules()))
			->map(Arr::groupBy(fn($x) => $x->selector->media))
			->map(Arr::mapWithKeys(fn (string $k, array $g): string =>
				(new Pipeline($g))
					->map(Iter::map(fn($x) => $x->mapSelector(fn($x) => $x->mapMedia(fn($x) => ''))))
					->map(Iter::filter($this->isWhitelisted(...)))
					->map(Arr::from(...))
					->map($join)
					->map(function ($x) use ($k) {
						if (strlen($x) === 0 || strlen($k) === 0) return $x;
						else {
							$x = str_replace("\n", "\n\t", $x);
							return "@media {$k} {\n\t{$x}\n}";
						}
					})
					->return()
			))
			->map(Arr::filter(fn($x) => strlen($x) > 0))
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

	private function isWhitelisted(Rule $x): bool {
		if (count($this->whitelist) === 0)
			return true;
		else
			return array_key_exists($x->selector->base, $this->whitelist);
	}
}
