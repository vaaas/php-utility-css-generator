<?php
declare(strict_types=1);
namespace Vas\UtilityCssGenerator;

use PHPUnit\Framework\TestCase;

class UtilityCssGeneratorTest extends TestCase {
	public function setUp(): void {
		error_reporting(E_ALL);
	}

	private function assertMatchesSnapshot(string $snapshot, string $actual) {
		$pathname = __DIR__ . '/__snapshots__/' . $snapshot;
		$expected = file_get_contents($pathname);
		if ($expected === false) {
			file_put_contents($pathname, $actual);
			$this->assertEquals(true, true);
		} else
			$this->assertEquals(trim($expected), $actual);
	}

	public function testSingleRule(): void {
		$generator = new UtilityCssGenerator(
			[
				new Rule('bold', 'font-weight: bold'),
			]
		);
		$this->assertMatchesSnapshot('testSingleRule', $generator->__toString());
	}

	public function testMultipleRules(): void {
		$generator = new UtilityCssGenerator(
			[
				new Rule('bold', 'font-weight: bold'),
				new Rule('red', 'color: red'),
			]
		);
		$this->assertMatchesSnapshot('testMultipleRules', $generator->__toString());
	}

	public function testVariants(): void {
		$generator = new UtilityCssGenerator(
			[
				new Rule('bold', 'font-weight: bold'),
				new Rule('red', 'color: red'),
			],
			[
				Variant::ancestor('dark', '.dark'),
				Variant::media('md', '(max-width: 768px)'),
				Variant::pseudo('hover', 'hover'),
			]
		);
		$this->assertMatchesSnapshot('testVariants', $generator->__toString());
	}

	public function testWhitelist(): void {
		$generator = new UtilityCssGenerator(
			[
				new Rule('bold', 'font-weight: bold'),
				new Rule('red', 'color: red'),
			],
			[
				Variant::ancestor('dark', '.dark'),
				Variant::media('md', '(max-width: 768px)'),
				Variant::pseudo('hover', 'hover'),
			],
			'util',
			[
				'util-red',
				'hover\:util-red',
				'dark\:util-bold',
				'md\:hover\:bold',
			],
		);
		$this->assertMatchesSnapshot('testWhitelist', $generator->__toString());
	}
}
