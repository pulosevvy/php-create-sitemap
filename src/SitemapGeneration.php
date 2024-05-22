<?php

namespace SitemapGeneration;

use SitemapGeneration\Types\Page;
use SitemapGeneration\Exceptions\FileWriteException;
use SitemapGeneration\Exceptions\InvalidPagesException;
use SitemapGeneration\Exceptions\InvalidSitemapFileTypeException;

class SitemapGeneration
{
    const LOC = "loc";
    const LASTMOD = "lastmod";
    const PRIORITY = "priority";
    const CHANGEFREQ = "changefreq";

    const XML = "xml";
    const JSON = "json";
    const CSV = "csv";

    private array $pages;
    private string $type;
    private string $path;

    /**
     * @throws InvalidPagesException
     */
    public function __construct(array $pages, string $type, string $path)
    {
        $this->type = $type;
        $this->path = $path;
        $this->setPages($pages);
    }

    /**
     * @throws FileWriteException
     * @throws InvalidSitemapFileTypeException
     */
    public function generate() {
        if (!is_dir($this->path) && !mkdir($this->path, 0777, true)) {
            throw new FileWriteException("Unable to create directory: $this->path");
        }

        switch ($this->type) {
            case self::XML:
                $content = $this->generateXml();
                $extension = "." . self::XML;
                break;
            case self::JSON:
                $content = $this->generateJson();
                $extension = "." . self::JSON;
                break;
            case self::CSV:
                $content = $this->generateCsv();
                $extension = "." . self::CSV;
                break;
            default:
                throw new InvalidSitemapFileTypeException("Invalid sitemap type");
        }
        $filePath = $this->path . DIRECTORY_SEPARATOR . "sitemap" . $extension;
        if (!file_put_contents($filePath, $content)) {
            throw new FileWriteException("Unable to write sitemap file: $filePath");
        }
    }

    private function generateXml()
    {
        $xml = new \SimpleXMLElement('<urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        /** @var Page $page */
        foreach ($this->pages as $page) {
            $url = $xml->addChild('url');
            $url->addChild(self::LOC, $page->getLoc());
            $url->addChild(self::LASTMOD, $page->getLastMode());
            $url->addChild(self::PRIORITY, $page->getPriority());
            $url->addChild(self::CHANGEFREQ, $page->getChangeFreq());
        }

        return $xml->asXML();
    }

    private function generateJson()
    {
        $sitemap = array_map(function(Page $page) {
            return [
                self::LOC => $page->getLoc(),
                self::LASTMOD => $page->getLastMode(),
                self::PRIORITY => $page->getPriority(),
                self::CHANGEFREQ => $page->getChangeFreq(),
            ];
        }, $this->pages);

        return json_encode($sitemap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function generateCsv()
    {
        $csv = sprintf("%s;%s;%s;%s" . PHP_EOL, self::LOC, self::LASTMOD, self::PRIORITY, self::CHANGEFREQ);

        /** @var $page Page */
        foreach ($this->pages as $page) {
            $csv .= sprintf(
                "%s;%s;%s;%s\n",
                $page->getLoc(), $page->getLastMode(), $page->getPriority(), $page->getChangeFreq()
            );
        }

        return $csv;
    }


    /**
     * @throws InvalidPagesException
     */
    private function setPages(array $pages): void
    {
        $this->pages = [];
        foreach ($pages as $page) {
            if (!isset($page[self::LOC], $page[self::LASTMOD], $page[self::PRIORITY], $page[self::CHANGEFREQ])) {
                throw new InvalidPagesException("Pages is not valid");
            }
            $this->pages[] = new Page($page[self::LOC], $page[self::LASTMOD], $page[self::PRIORITY], $page[self::CHANGEFREQ]);
        }
    }

}