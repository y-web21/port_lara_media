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
    ];

    protected int $curlErrno;

    public function execCurl(string $url, array $opts = [])
    {
        $curlOptions = empty($this->options) ? $this->defaultOpts : $opts;

        $ch = curl_init($url);
        curl_setopt_array($ch, $curlOptions);
        $info = curl_getinfo($ch);
        $redirectUrl = $this->resolveRedirectUrl($info);

        // #HACK もう一度APIに問い合わせを行う。冗長かつ1回のリダイレクトのみ対応
        if (strlen($redirectUrl) > 0){
            $ch = curl_init($redirectUrl);
            curl_setopt_array($ch, $curlOptions);
        }

        $info = curl_getinfo($ch);
        $retString = curl_exec($ch);
        $errno = curl_errno($ch);
        $this->curlErrno = $errno;

        return array($retString, $info, $errno === CURLE_OK, $redirectUrl);
    }

    private function resolveRedirectUrl(mixed $curlInfo): string
    {
        if (gettype($curlInfo['redirect_url']) === 'string' && strlen($curlInfo['redirect_url']) > 0) {
            return $curlInfo['redirect_url'];
        }
        return '';
    }
}
