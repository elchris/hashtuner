<?php

namespace ChrisHolland\HashTuner;

use BrandEmbassy\Memory\MemoryConfiguration;
use BrandEmbassy\Memory\MemoryLimitNotSetException;
use BrandEmbassy\Memory\MemoryLimitProvider;

class SystemInfo
{
    /**
     * @var int
     */
    private $limitInKiloBytes;

    /**
     * SystemInfo constructor.
     * @throws MemoryLimitNotSetException
     */
    public function __construct()
    {
        $configuration = new MemoryConfiguration();
        $limitProvider = new MemoryLimitProvider($configuration);
        $limitInBytes = $limitProvider->getLimitInBytes();
        $this->limitInKiloBytes = intval($limitInBytes / 1024);
    }

    public function getMemoryLimitInKiloBytes()
    {
        return $this->limitInKiloBytes;
    }
}
