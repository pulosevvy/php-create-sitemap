<?php

namespace SitemapGeneration\Types;

class Page
{
    private string $loc;
    private string $lastMode;
    private string $priority;
    private string $changeFreq;

    function __construct($loc, $lastMode, $priority, $changeFreq)
    {
        $this->loc = $loc;
        $this->lastMode = $lastMode;
        $this->priority = $priority;
        $this->changeFreq = $changeFreq;
    }

    /**
     * @return string
     */
    public function getLoc(): string
    {
        return $this->loc;
    }

    /**
     * @param string $loc
     * @return void
     */
    public function setLoc(string $loc): void
    {
        $this->loc = $loc;
    }

    /**
     * @return string
     */
    public function getLastMode(): string
    {
        return $this->lastMode;
    }

    /**
     * @param string $lastMode
     * @return void
     */
    public function setLastMode(string $lastMode): void
    {
        $this->lastMode = $lastMode;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     * @return void
     */
    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getChangeFreq(): string
    {
        return $this->changeFreq;
    }

    /**
     * @param string $changeFreq
     * @return void
     */
    public function setChangeFreq(string $changeFreq): void
    {
        $this->changeFreq = $changeFreq;
    }
}