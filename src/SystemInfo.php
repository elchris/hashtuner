<?php

namespace ChrisHolland\HashTuner;

use BrandEmbassy\Memory\MemoryConfiguration;
use BrandEmbassy\Memory\MemoryLimitNotSetException;
use BrandEmbassy\Memory\MemoryLimitProvider;

class SystemInfo
{
    public const LINUX = 'linux';
    public const FREEBSD = 'freebsd';
    public const DARWIN = 'darwin';
    public const LINUX_PROCESSORS_COMMAND = 'cat /proc/cpuinfo | grep processor | wc -l';
    public const FREEBSD_PROCESSORS_COMMAND = 'sysctl -a | grep \'hw.ncpu\' | cut -d \':\' -f2';
    public const DARWIN_PROCESSORS_COMMAND = 'sysctl -n hw.ncpu';

    private int $limitInKiloBytes;
    private string $OS;

    /**
     * SystemInfo constructor.
     * @param string|null $OsOverride
     * @throws MemoryLimitNotSetException
     */
    public function __construct(?string $OsOverride = null)
    {
        $configuration = new MemoryConfiguration();
        $limitProvider = new MemoryLimitProvider($configuration);
        $limitInBytes = $limitProvider->getLimitInBytes();
        $this->limitInKiloBytes = (int)($limitInBytes / 1024);
        if ($OsOverride === null) {
            $this->OS = strtolower(trim(
                php_uname('s')
                //shell_exec('uname')
            ));
        } else {
            $this->OS = $OsOverride;
        }
    }

    public function getMemoryLimitInKiloBytes(): int
    {
        return $this->limitInKiloBytes;
    }

    public function getCores() : int
    {
        //cribbed from https://wp-mix.com/php-get-server-information/
        $cmd = $this->getCoresCommand();
        $shellOutput = shell_exec($cmd);
        if (($shellOutput === null) || ($shellOutput === false)) {
            $shellOutput = '1';
        }
        return ($cmd === null) ? 1 : (int)trim($shellOutput);
    }

    /**
     * @return string|null
     */
    public function getCoresCommand(): ?string
    {
        return match ($this->OS) {
            self::LINUX => self::LINUX_PROCESSORS_COMMAND,
            self::FREEBSD => self::FREEBSD_PROCESSORS_COMMAND,
            self::DARWIN => self::DARWIN_PROCESSORS_COMMAND,
            default => null,
        };
    }
}
