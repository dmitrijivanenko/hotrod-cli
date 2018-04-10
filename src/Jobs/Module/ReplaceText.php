<?php

namespace HotRodCli\Jobs\Module;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class ReplaceText
{
    protected $finder;

    protected $fileSystem;

    public function __construct(Finder $finder, Filesystem $filesystem)
    {
        $this->finder = $finder;
        $this->fileSystem = $filesystem;
    }

    public function handle(string $needle, string $value, string $dir)
    {
        if (!$this->fileSystem->exists($dir)) {
            throw new \Exception('There is no such directory');
        }

        $files = $this->finder
            ->files()
            ->in($dir)
            ->contains($needle);

        foreach ($files as $file) {
            $updated = str_replace($needle, $value, $file->getContents());

            try {
                $this->fileSystem->dumpFile($file->getRealPath(), $updated);
            } catch (IOExceptionInterface $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
    }
}
