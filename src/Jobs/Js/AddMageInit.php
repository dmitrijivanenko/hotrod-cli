<?php

namespace HotRodCli\Jobs\Js;

use HotRodCli\AppContainer;
use Symfony\Component\Filesystem\Filesystem;

class AddMageInit
{
    protected $scriptTags = [
        'open' => '<script type="text/x-magento-init">',
        'close' => '</script>'
    ];

    protected $container;

    /** @var Filesystem  */
    protected $filesystem;

    public function __construct(AppContainer $appContainer)
    {
        $this->container = $appContainer;
        $this->filesystem = $this->container->resolve(Filesystem::class);
    }

    public function handle(string $file, array $data)
    {
        if (!$this->filesystem->exists($file)) {
            throw new \Exception('no such file');
        }

        $this->addInit($file, [
            'name' => $data['name'],
            'bind' => $data['bind'] ?? '*'
        ]);
    }

    protected function addInit($file, array $data)
    {
        $content = file_get_contents($file);
        $array = [];
        $array[$data['bind']][$data['name']]['text'] = 'HELLO WORLD';
        $content .= PHP_EOL . PHP_EOL. $this->scriptTags['open'] . PHP_EOL .
            json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL . $this->scriptTags['close'] . PHP_EOL;
        file_put_contents($file, $content);
    }
}
