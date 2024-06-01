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

	public function addMedia(string $x): Selector {
		return $this->mapMedia(fn($media) => strlen($media) === 0 ? $x : "{$media} and {$x}");
	}

	public function clearMedia(): Selector {
		return $this->mapMedia(fn($x) => '');
	}

	public function addPrefix(string $x): Selector {
		return $this->mapPrefix(fn($prefix) => strlen($prefix) === 0 ? $x : "{$x} {$prefix}");
	}

	public function addSuffix(string $x): Selector {
		return $this->mapSuffix(Str::suffix($x));
	}

	public function addModifier(string $x): Selector {
		return $this->mapBase(Str::prefix("{$x}\\:"));
	}

	public function addPseudo(string $x): Selector {
		return $this->mapPseudo(Str::suffix(':' . $x));
	}
}
