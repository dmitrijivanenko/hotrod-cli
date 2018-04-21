<?php

namespace HotRodCli\Jobs\Xml;

use Symfony\Component\Filesystem\Filesystem;

class AddPreference
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
            $this->addPreference($xmlFile, $addition);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function addPreference(string $xmlFile, array $addition)
    {
        $content = file_get_contents($xmlFile);
        $xmlArray = new \SimpleXMLElement($content);

        $newRoute = $xmlArray->addChild('preference');
        $newRoute->addAttribute('for', $addition['for']);
        $newRoute->addAttribute('type', $addition['type']);

        $dom = new \DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlArray->asXML());
        $xmlArray = new \SimpleXMLElement($dom->saveXML());
        $xmlArray->asXML($xmlFile);
    }
}
