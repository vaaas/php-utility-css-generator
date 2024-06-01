<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

class Selector {
	public function __construct(
		public string $base,
		public string $prefix = '',
		public string $suffix = '',
		public string $media = '',
	) {}

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
		return new Selector(
			"{$x}\\:{$this->base}",
			$this->prefix,
			$this->suffix,
			$this->media,
		);
	}

	public function addPseudo(string $x): Selector {
		return new Selector(
			"{$this->base}:{$x}",
			$this->prefix,
			$this->suffix,
			$this->media,
		);
	}
}
