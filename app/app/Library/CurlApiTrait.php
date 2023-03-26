<?php

namespace App\Library;

/**
 * @link https://www.php.net/manual/ja/ref.curl.php
 */
trait CurlApiTrait
{
    private $defaultOpts = [
        CURLOPT_RETURNTRANSFER => true, // get as a string
        CURLOPT_TIMEOUT => 2,
        CURLOPT_MAXREDIRS => 5, // num of follow redirect
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_AUTOREFERER => true,
    ];

    protected int $curlErrno;

    /**
     * curl(php) の実行
     *
     * @link https://www.php.net/manual/ja/ref.curl.php
     * @param string $url
     * @param array $opts // curl options array
     * @return array<string,mixed,bool> <return text, curl_info, isSuccess, isRedirected>
     */
    public function execCurl(string $url, array $opts = [])
    {
        $curlOptions = empty($this->options) ? $this->defaultOpts : $opts;

        $ch = curl_init($url);
        curl_setopt_array($ch, $curlOptions);
        $retString = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errno = curl_errno($ch);

        $this->curlErrno = $errno;

        // CURLOPT_MAXREDIRS で curl_exec でリダイレクトを行う設定のため、'redirect_url'ではなく'url'を取得する
        $redirecteUrl = $info['redirect_count'] === 0 ? '' : $info['url'];

        return array($retString, $info, $errno === CURLE_OK, $redirecteUrl);
    }

    private function resolveRedirectUrl(mixed $curlInfo): string
    {
        if (gettype($curlInfo['redirect_url']) === 'string' && strlen($curlInfo['redirect_url']) > 0) {
            return $curlInfo['redirect_url'];
        }
        return '';
    }
}
