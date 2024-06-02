<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Selector {
	public function __construct(
		public readonly string $base,
		public readonly string $pseudo = '',
		public readonly string $prefix = '',
		public readonly string $suffix = '',
		public readonly string $media = '',
	) {}

	/** @param callable(string): string $f */
	public function mapBase(callable $f): Selector {
		return new Selector(
			$f($this->base),
			$this->pseudo,
			$this->prefix,
			$this->suffix,
			$this->media,
		);
	}

	/** @param callable(string): string $f */
	public function mapPseudo(callable $f): Selector {
		return new Selector(
			$this->base,
			$f($this->pseudo),
			$this->prefix,
			$this->suffix,
			$this->media,
		);
	}

	/** @param callable(string): string $f */
	public function mapPrefix(callable $f): Selector {
		return new Selector(
			$this->base,
			$this->pseudo,
			$f($this->prefix),
			$this->suffix,
			$this->media,
		);
	}

	/** @param callable(string): string $f */
	public function mapSuffix(callable $f): Selector {
		return new Selector(
			$this->base,
			$this->pseudo,
			$this->prefix,
			$f($this->suffix),
			$this->media,
		);
	}

	/** @param callable(string): string $f */
	public function mapMedia(callable $f): Selector {
		return new Selector(
			$this->base,
			$this->pseudo,
			$this->prefix,
			$this->suffix,
			$f($this->media),
		);
	}

	public function addModifier(string $x): Selector {
		return $this->mapBase(Str::prefix("{$x}\\:"));
	}
}
