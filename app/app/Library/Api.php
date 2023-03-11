<?php

namespace App\Library;

use DateTimeInterface;

abstract class Api
{
    use CurlApiTrait;
    use LocalStorageTrait;

    /**
     * APIへのアクセス間隔を設定
     *
     * @var integer
     */
    protected int $updateCycle = 60 * 60 * 2;
    private string $json = '';
    private string $localStoragePath = '';
    /**
     * APIのリダイレクトがあった場合ログに出力するため
     *
     * @var string
     */
    protected string $redirectUrl = '';

    // /**
    //  * @param string|array<stirng> $url
    //  */
    // public function __construct(
    // protected string|array $url,
    // protected string $localStoragePath = '',
    // protected string $logPath = '',
    public function __construct(
        protected string $localStorageDir,
        protected string $logPath = '',
    ) {
        $this->localStorageDir = realpath($localStorageDir);
        $this->logPath = realpath($logPath);
    }

    public function setStorageDir(string $path){
        $this->localStorageDir = realpath($path);
    }

    public function get(string $url, string $filename)
    {
        $this->redirectUrl = '';
        $localStoragePath = "{$this->localStorageDir}/{$filename}";
        // ローカルにjsonを保存
        if (!$this->shouldUpdateLocalFile($localStoragePath)) {
            $this->json = $this->getLocalData($localStoragePath);
            return $this->json;
        }

        $json = $this->fetchApi($url);
        if ($json) {
            $this->saveLocalData($localStoragePath, $json);
            return $this->json;
        }

        // APIからデータ取得失敗した場合
        $this->json = $this->getLocalData($localStoragePath);
        return $this->json;
    }

    private function getLocalData(string $localStoragePath): string
    {
        if (file_exists($localStoragePath)) {
            return $this->readText($localStoragePath);
        }
        return '';
    }

    private function saveLocalData(string $localStoragePath, string $text)
    {
        if ($localStoragePath !== '') {
            $this->appendText($localStoragePath, $text);
        }
    }

    private function log(string $code, $url = '', $redirectUrl = ''): void
    {
        if ($this->logPath === '') {
            return;
        }

        switch ($code) {
            case '301':
            case '302':
            case '308':
                $logMessage = "[Caution] {$url} is {$code} responded. Redirected to {$redirectUrl}\n";
                break;
            case '400':
            case '404':
            case '410':
                $logMessage = "[Warning] {$url} is {$code} responded. Page not fuond.\n";
                break;
            default:
        }
        if (isset($logMessage)) {
            $logMessage = "[" . date(DateTimeInterface::W3C) . "] " . $logMessage;
            $this->appendText($this->logPath, $logMessage);
        }
    }

    /**
     * api への問い合わせを行う
     *
     * @return array<string> [isSuccess, text, url]
     */
    public function fetchApi(string $url): string
    {
        list($ret, $curlInfo, $isSuccess, $redirectUrl) = $this->execCurl($url);

        if (!$isSuccess) {
            return '';
        }

        $this->log($curlInfo['http_code'], $url, $redirectUrl);

        if ($this->isJson($ret)) {
            return $ret;
        }

        // failure
        return '';
    }

    /**
     * judge to use the local json file or not
     *
     * @return boolean
     */
    private function shouldUpdateLocalFile(string $path): bool
    {
        if (!$this->isExistsLocal($path)) {
            return true;
        }
        if ($this->getLastModified($path) > $this->updateCycle) {
            return true;
        }

        // $lastUpdate = filemtime($path);
        // if ($_SERVER['REQUEST_TIME'] - $lastUpdate < $this->updateCycle) {
        // return true;
        // }
        return false;
    }

    private function elapsedTimeLastFetch(string $localStoragePath): int
    {
        return $this->getLastModified($localStoragePath) > $this->updateCycle;
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
