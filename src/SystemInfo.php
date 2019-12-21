<?php

namespace ChrisHolland\HashTuner;

use BrandEmbassy\Memory\MemoryConfiguration;
use BrandEmbassy\Memory\MemoryLimitNotSetException;
use BrandEmbassy\Memory\MemoryLimitProvider;

class SystemInfo
{
    const LINUX = 'linux';
    const FREEBSD = 'freebsd';
    const DARWIN = 'darwin';
    const LINUX_PROCESSORS_COMMAND = 'cat /proc/cpuinfo | grep processor | wc -l';
    const FREEBSD_PROCESSORS_COMMAND = 'sysctl -a | grep \'hw.ncpu\' | cut -d \':\' -f2';
    const DARWIN_PROCESSORS_COMMAND = 'sysctl -n hw.ncpu';
    /**
     * @var int
     */
    private $limitInKiloBytes;
    /**
     * @var string
     */
    private $OS;

    /**
     * SystemInfo constructor.
     * @param null $OsOverride
     * @throws MemoryLimitNotSetException
     */
    public function __construct($OsOverride = null)
    {
        $configuration = new MemoryConfiguration();
        $limitProvider = new MemoryLimitProvider($configuration);
        $limitInBytes = $limitProvider->getLimitInBytes();
        $this->limitInKiloBytes = intval($limitInBytes / 1024);
        if ($OsOverride === null) {
            $this->OS = strtolower(trim(shell_exec('uname')));
        } else {
            $this->OS = $OsOverride;
        }
    }

    public function getMemoryLimitInKiloBytes()
    {
        return $this->limitInKiloBytes;
    }

    public function getCores() : int
    {
        //cribbed from https://wp-mix.com/php-get-server-information/
        $cmd = $this->getCoresCommand();
        return ($cmd === null) ? 1 : intval(trim(shell_exec($cmd)));
    }

    /**
     * @return string|null
     */
    public function getCoresCommand()
    {
        $cmd = null;
        switch ($this->OS) {
            case (self::LINUX):
                $cmd = self::LINUX_PROCESSORS_COMMAND;
                break;
            case (self::FREEBSD):
                $cmd = self::FREEBSD_PROCESSORS_COMMAND;
                break;
            case (self::DARWIN):
                $cmd = self::DARWIN_PROCESSORS_COMMAND;
                break;
            default:
                $cmd = null;
        }
        return $cmd;
    }
}
