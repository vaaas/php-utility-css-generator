<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Variant {
	/** @return callable(Selector): Selector */
	public static function ancestor(string $prefix, string $ancestor): callable {
		return fn($x) => $x->addModifier($prefix)
		                   ->mapPrefix(Str::concatWith($ancestor, ' '));
	}

	/** @return callable(Selector): Selector */
	public static function media(string $prefix, string $query): callable {
		return fn($x) => $x->addModifier($prefix)
		                   ->mapMedia(Str::concatWith($query, ' and '));
	}

	/** @return callable(Selector): Selector */
	public static function pseudo(string $prefix, string $selector): callable {
		return fn($x) => $x->addModifier($prefix)
		                   ->mapPseudo(Str::suffix($selector));
	}

	/** @return callable(Selector): Selector */
	public static function successor(string $prefix, string $successor): callable {
		return fn($x) => $x->addModifier($prefix)
		                   ->mapSuffix(Str::suffix($successor));
	}
}
