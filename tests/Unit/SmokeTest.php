<?php

declare(strict_types=1);

namespace Phauthentic\AOP\Tests\Unit;

use Phauthentic\AOP\Version;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SmokeTest extends TestCase
{
    #[Test]
    public function versionIsNonEmptyString(): void
    {
        $this->assertNotSame('', Version::VERSION);
    }
}
