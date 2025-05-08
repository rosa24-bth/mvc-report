<?php

namespace App\Service;

class FileLoader
{
    /**
     * Open a file for reading.
     *
     * @param string $path The path to the file.
     * @return resource|false
     */
    public function open(string $path)
    {
        return fopen($path, 'r');
    }
}
