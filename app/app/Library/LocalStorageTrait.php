<?php

namespace App\Library;

/**
 * @link https://www.php.net/manual/ja/ref.filesystem.php
 */
trait LocalStorageTrait
{
    private function appendText(string $path, string $text, int $perm = 0775)
    {
        $this->writeText($path, $text, $perm, LOCK_EX | FILE_APPEND);
    }

    private function overwriteText(string $path, string $text, int $perm = 0775)
    {
        $this->writeText($path, $text, $perm, LOCK_EX);
    }

    private function writeText(string $path, string $text, int $perm = 0775, int $opts = 0)
    {
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($perm, true);
        }
        // open with exclusive lock
        file_put_contents($path, $text, $opts);
    }

    /**
     * @param string $path local filepath
     * @return string|boolean return false if read fails
     */
    private function readText(string $path): string|bool
    {
        try {
            $data = file_get_contents($path);
        } catch (\Throwable $th) {
            // failure
            return false;
        }
        return $data;
    }

    private function deleteFile(string $path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function getLastModified(string $path, string $format = 'Y/m/d H:i')
    {
        return date($format, filemtime($path));
    }
}
