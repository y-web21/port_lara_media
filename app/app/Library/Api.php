<?php

namespace App\Library;

use App\Library\CurlApi;

//  implements fetchWebApi
// extends Curdl
abstract class Api
{
    use CurlApiTrait;
    use LocalStorageTrait;

    private string $lastUrl;
    protected int $updateCycle = 60 * 60 * 2;
    private string $json;
    private string $localStoragePath = '';

    // /**
    //  * @param string|array<stirng> $url
    //  */
    // public function __construct(
    // protected string|array $url,
    // protected string $localStoragePath = '',
    // protected string $logPath = '',
    public function __construct(
        protected string $storageDir,
        protected string $logPath = '',
    ) {
        // $this->url = $this->strToArray($url);
        var_dump('a');
    }

    public function get(string $url, string $filename)
    {
        $this->lastUrl = $url;
        $this->localStoragePath = "{$this->storageDir}/{$filename}";
        // ローカルにjsonを保存
        if (!$this->shouldUpdateLocalFile($this->localStoragePath)) {
            $this->json = $this->getLocalData();
            return $this->json;
        }

        if ($this->fetchApi()) {
            $this->saveLocalData();
            return $this->json;
        }

        // APIからデータ取得失敗した場合
        $this->json = $this->getLocalData();
        return $this->json;
    }

    private function getLocalData(): string
    {
        if (file_exists($this->localStoragePath)) {
            return $this->readText($this->localStoragePath);
        }
        return '';
    }

    private function saveLocalData()
    {
        if ($this->localStoragePath !== '') {
            $this->appendText($this->localStoragePath, $this->json);
        }
    }

    private function log(string $code, $url = '')
    {
        if ($this->logPath === '') {
            return;
        }

        switch ($code) {
            case '301':
            case '302':
            case '308':
                $logMessage = "[Caution] {$url} is {$code} responded. Redirected to {$this->lastUrl} \n";
                break;
            case '400':
            case '404':
            case '410':
                $logMessage = "[Warning] {$url} is {$code} responded. Page not fuond.\n";
                break;
            default:
        }
        if (isset($logMessage)) {
            $this->appendText($this->logPath, $logMessage);
        }
    }

    public function fetchApi(): bool
    {
        list($ret, $curlInfo, $isSuccess, $this->lastUrl) = $this->execCurl($this->lastUrl);
        $this->lastUrl = $this->resolveRedirectUrl($curlInfo);

        if (!$isSuccess) {
            return false;
        }

        $this->log($curlInfo['http_code']);

        if ($this->isJson($ret)) {
            $this->json = $ret;
            return true;
        }

        // failure
        return false;
    }

    /**
     * judge to use the local json file or not
     *
     * @return boolean
     */
    private function shouldUpdateLocalFile(): bool
    {
        if (!$this->isExistsLocal($this->localStoragePath)){
            return true;
        }
        if ($this->getLastModified($this->localStoragePath) > $this->updateCycle) {
            return true;
        }

        // $lastUpdate = filemtime($path);
        // if ($_SERVER['REQUEST_TIME'] - $lastUpdate < $this->updateCycle) {
        // return true;
        // }
        return false;
    }

    private function elapsedLastFetch():int
    {
        return $this->getLastModified($this->localStoragePath) > $this->updateCycle;
    }

    private function isExistsLocal(string $path): bool
    {
        return file_exists($path);
    }

    public static function isJson($str): bool
    {
        json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
