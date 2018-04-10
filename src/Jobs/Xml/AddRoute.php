<?php

namespace HotRodCli\Jobs\Xml;

use Symfony\Component\Filesystem\Filesystem;

class AddRoute
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
            $content = file_get_contents($xmlFile);
            $xmlArray = new \SimpleXMLElement($content);

            if ($this->isRouteExists($xmlArray, $addition['frontName'])) {
                throw new \Exception('route already exists');
            }

            $newRoute = $xmlArray->router->addChild('route');
            $newRoute->addAttribute('frontName', $addition['frontName']);
            $newRoute->addAttribute('id', $addition['id']);
            $routeModule = $newRoute->addChild('module');
            $routeModule->addAttribute('name', $addition['moduleName']);

            $xmlArray->asXML($xmlFile);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function isRouteExists($xml, $frontName)
    {
        $routes = $xml->router->children();

        foreach ($routes as $route) {
            if ($route->attributes()['frontName'] == $frontName) {
                return true;
            }
        }

        return false;
    }
}
