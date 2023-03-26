<?php

namespace Tests\Unit;

use App\Library\LocalStorageTrait;
use PHPUnit\Framework\TestCase;

class LocalStorageTest extends TestCase
{
    use LocalStorageTrait {
        // publish private method
        LocalStorageTrait::appendText as public;
    }

    /**
     * @test
     */
    public function ローカルストレージにテキストファイルが作成できるか()
    {
        $path = './storage/api/' . 'test-append.text';
        $this->deleteFile($path);
        $this->appendText($path, '');
        $this->assertFileExists($path);
        $this->deleteFile($path);
    }

    private function deleteFile(string $path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
