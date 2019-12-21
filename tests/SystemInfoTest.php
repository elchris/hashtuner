<?php

namespace ChrisHolland\HashTuner\Test;

use BrandEmbassy\Memory\MemoryLimitNotSetException;
use ChrisHolland\HashTuner\SystemInfo;
use PHPUnit\Framework\TestCase;

class SystemInfoTest extends TestCase
{
    /**
     * @throws MemoryLimitNotSetException
     */
    public function testOsProcessorsCommand()
    {
        $this->assertOsProcessorsCommand(
            SystemInfo::DARWIN,
            SystemInfo::DARWIN_PROCESSORS_COMMAND
        );

        $this->assertOsProcessorsCommand(
            SystemInfo::FREEBSD,
            SystemInfo::FREEBSD_PROCESSORS_COMMAND
        );

        $this->assertOsProcessorsCommand(
            SystemInfo::LINUX,
            SystemInfo::LINUX_PROCESSORS_COMMAND
        );
        $this->assertOsProcessorsCommand(
            'unsupported',
            null
        );
    }

    /**
     * @param string $os
     * @param string $osCommand
     * @throws MemoryLimitNotSetException
     */
    private function assertOsProcessorsCommand(string $os, ?string $osCommand): void
    {
        $info = new SystemInfo($os);
        self::assertSame(
            $osCommand,
            $info->getCoresCommand()
        );
    }
}
