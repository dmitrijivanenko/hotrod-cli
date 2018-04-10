<?php

namespace HotRodCli\Jobs\Filesystem;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class CopyFile
{
    protected $finder;

    protected $filesystem;

    public function __construct(Finder $finder, Filesystem $filesystem)
    {
        $this->finder = $finder;
        $this->filesystem = $filesystem;
    }

    public function handle(string $src, string $dist)
    {
        if ($this->filesystem->exists($dist)) {
            throw new \Exception('Such file already exists');
        }

        try {
            $this->filesystem->copy($src, $dist);
        } catch (IOExceptionInterface $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
