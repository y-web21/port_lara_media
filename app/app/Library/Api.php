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
    private array $json = [];
    /**
     * APIのリダイレクトがあった場合ログに出力するため
     *
     * @var string
     */
    protected string $redirectUrl = '';

    public function __construct(
        protected string $localStorageDir,
        protected string $logPath = '',
    ) {
        $this->localStorageDir = realpath($localStorageDir);
        $this->logPath = realpath($logPath);
    }

    public function setStorageDir(string $path)
    {
        $this->localStorageDir = realpath($path);
    }

    /**
     * $url から取得したデータか、$filename に保存されたデータを返します。
     * どちらを返すかは、$this->updateCycle によって決まります。
     *
     * @param string $url
     * @param string $filename
     * @return string
     */
    public function getByUrl(string $url, string $filename): array
    {
        $this->redirectUrl = '';
        $localStoragePath = "{$this->localStorageDir}/{$filename}";
        if (!$this->shouldUpdateLocalFile($localStoragePath)) {
            $this->json = $this->jsonStringToArray($this->loadFromLocalText($localStoragePath));
            return $this->json;
        }

        /** @var string $json */
        $json = $this->fetchApi($url);
        if ($json) {
            // ローカルにjsonを保存
            $this->saveToLocalText($localStoragePath, $json);
            $this->json = $this->jsonStringToArray($json);
            return $this->json;
        }

        // APIからデータ取得失敗した場合ローカルファイルを読み込む
        $this->json = $this->jsonStringToArray($this->loadFromLocalText($localStoragePath));
        return $this->json;
    }

    /**
     * ローカルに保存されたテキストを返します
     *
     * @param string $localStoragePath
     * @return string
     */
    private function loadFromLocalText(string $localStoragePath): string
    {
        if (file_exists($localStoragePath)) {
            return $this->readText($localStoragePath);
        }
        return '';
    }

    private function saveToLocalText(string $localStoragePath, string $text)
    {
        if ($localStoragePath !== '') {
            $this->overwriteText($localStoragePath, $text);
        }
    }

    private function appendToLocalText(string $localStoragePath, string $text)
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
                $logMessage = "[Warning] {$url} is missing. {$code} responded.\n";
                break;
            case '400':
            case '404':
            case '410':
                $logMessage = "[Warning] {$url} is {$code} responded. Page not fuond.\n";
                break;
            default:
                if ($redirectUrl !== '') {
                    $logMessage = "[Caution] {$url} is {$code} responded. Redirected to {$redirectUrl}\n";
                }
        }
        if (isset($logMessage)) {
            $logMessage = "[" . date(DateTimeInterface::W3C) . "] " . $logMessage;
            $this->appendToLocalText($this->logPath, $logMessage);
        }
    }

    /**
     * api への問い合わせを行う
     *
     * @return array<string> [isSuccess, text, url]
     */
    public function fetchApi(string $url): string
    {
        list($ret, $curlInfo, $isSuccess, $redirecteUrl) = $this->execCurl($url);

        $this->log($curlInfo['http_code'], $url, $redirecteUrl);

        if (!$isSuccess) {
            return '';
        }

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

        if (!$this->isJson($this->loadFromLocalText($path))) {
            return true;
        }

        $elapsedSinceLastUpdate = strtotime("now") - strtotime($this->getLastModified($path));
        if ($elapsedSinceLastUpdate > $this->updateCycle) {
            return true;
        }

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

    private static function jsonStringToArray(string $jsonString): array
    {
        /** @var string|bool|null $decoded */
        $decoded;
        $decoded = json_decode($jsonString, true);
        return is_array($decoded) ? $decoded : [];
    }

    public static function isJson($str): bool
    {
        json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
