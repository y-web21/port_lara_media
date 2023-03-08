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
        $ret_string = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $trueUrl = $this->resolveRedirectUrl($url);

        $this->curlErrno = $errno;

        return array($trueUrl, $ret_string, $info, $errno !== CURLE_OK);
    }

    private function resolveRedirectUrl(string $curlInfo)
    {
        if (gettype($curlInfo['redirect_url']) === 'string' && strlen($curlInfo['redirect_url']) > 0) {
            return $curlInfo['redirect_url'];
        }
    }
}
