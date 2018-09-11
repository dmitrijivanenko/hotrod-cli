<?php

namespace HotRodCli\Jobs\Js;

use HotRodCli\AppContainer;
use Symfony\Component\Filesystem\Filesystem;

class AddJs
{
    protected $container;

    /** @var Filesystem  */
    protected $filesystem;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
        $this->filesystem = $this->container->resolve(Filesystem::class);
    }

    public function handle(string $file, string $name, string $jsFile)
    {
        if (!$this->filesystem->exists($file)) {
            throw new \Exception('no such file');
        }

        $this->addJs($file, [
            'name' => $name,
            'js' => $jsFile
        ]);
    }

    protected function addJs(string $file, array $data)
    {
        $content = file_get_contents($file);
        preg_match('/=\s?((?:\r|\n|.)+(}))/', $content, $matches);
        $array = json_decode($matches[1], true);
        $array['map']['*'][$data['name']] = $data['js'];
        $content = preg_replace('/=\s?((?:\r|\n|.)+(}))/', '= ' . json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), $content);
        file_put_contents($file, $content);
    }
}
