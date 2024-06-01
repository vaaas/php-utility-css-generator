<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Variant {
	/** @return callable(Selector): Selector */
	public static function ancestor(string $prefix, string $ancestor): callable {
		return fn(Selector $x) => $x->addModifier($prefix)->addPrefix($ancestor);
	}

	/** @return callable(Selector): Selector */
	public static function media(string $prefix, string $query): callable {
		return fn(Selector $x) => $x->addModifier($prefix)->addMedia($query);
	}

	/** @return callable(Selector): Selector */
	public static function pseudo(string $prefix, string $selector): callable {
		return fn(Selector $x) => $x->addModifier($prefix)->addPseudo($selector);
	}

	/** @return callable(Selector): Selector */
	public static function successor(string $prefix, string $successor): callable {
		return fn(Selector $x) => $x->addModifier($prefix)->addSuffix($successor);
	}
}
