<?php
namespace OverDocs;

class SheetCache
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function read($filename)
    {
        $filename = $this->directory . '/' . $filename . '.html';

        if (!file_exists($filename)) {
            throw new \Exception("Requested cache file doesn't exists: $filename");
        }

        return file_get_contents($filename);
    }

    public function write($filename, $content)
    {
        $directory = explode('/', $filename)[0];

        if (!is_dir($path = $this->directory . '/' . $directory)) {
            mkdir($path);
        }

        return file_put_contents($this->directory . '/' . $filename . '.html', $content);
    }

    public function clear()
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            // Preserve .gitignore
            if ($fileinfo->getFilename() !== '.gitignore') {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
        }
    }
}
