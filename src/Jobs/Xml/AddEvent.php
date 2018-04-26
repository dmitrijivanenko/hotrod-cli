<?php

namespace HotRodCli\Jobs\Xml;

use Symfony\Component\Filesystem\Filesystem;

class AddEvent
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function handle(string $xmlFile, array $addition)
    {
        if (!$this->filesystem->exists($xmlFile)) {
            throw new \Exception('no such file');
        }

        try {
            $this->addEvent($xmlFile, $addition);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function addEvent(string $xmlFile, array $addition)
    {
        $content = file_get_contents($xmlFile);
        $xmlArray = new \SimpleXMLElement($content);

        $newRoute = $xmlArray->addChild('event');
        $newRoute->addAttribute('name', $addition['event-name']);
        $observer = $newRoute->addChild('observer');
        $observer->addAttribute('name', $addition['observer-name']);
        $observer->addAttribute('instance', $addition['instance']);

        $dom = new \DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlArray->asXML());
        $xmlArray = new \SimpleXMLElement($dom->saveXML());
        $xmlArray->asXML($xmlFile);
    }
}
