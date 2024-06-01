<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Selector {
	public function __construct(
		public readonly string $base,
		public readonly string $prefix = '',
		public readonly string $suffix = '',
		public readonly string $media = '',
	) {}

	/** @param callable(string): string $f */
	public function mapBase(callable $f): Selector {
		return new Selector($f($this->base), $this->prefix, $this->suffix, $this->media);
	}

	public function addMedia(string $x): Selector {
		$media = strlen($this->media) === 0 ? $x : "{$this->media} and {$x}";
		return new Selector(
			$this->base,
			$this->prefix,
			$this->suffix,
			$media,
		);
	}

	public function clearMedia(): Selector {
		return new Selector(
			$this->base,
			$this->prefix,
			$this->suffix,
			'',
		);
	}

	public function addPrefix(string $x): Selector {
		$prefix = strlen($this->prefix) === 0 ? $x : "{$x} {$this->prefix}";
		return new Selector(
			$this->base,
			$prefix,
			$this->suffix,
			$this->media,
		);
	}

	public function addSuffix(string $x): Selector {
		$suffix = "{$this->suffix}{$x}";
		return new Selector(
			$this->base,
			$this->prefix,
			$suffix,
			$this->media,
		);
	}

	public function addModifier(string $x): Selector {
		return $this->mapBase(Str::prefix("{$x}\\:"));
	}

	public function addPseudo(string $x): Selector {
		return $this->mapBase(Str::suffix($x));
	}
}
