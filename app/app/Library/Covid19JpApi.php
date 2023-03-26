<?php

namespace App\Library;

class Covid19JpApi extends Api
{
    private $url = [
        'prefectures' => "https://covid19-japan-web-api.now.sh/api/v1/prefectures",
        'total' => "https://covid19-japan-web-api.now.sh/api/v1/total",
        'positives' => "https://covid19-japan-web-api.now.sh/api/v1/positives?prefecture=",
    ];

    private $filename = [
        'prefectures' => "covid19_prefectures.json",
        'total' => "covid19_total.json",
        'positives' => "covid19_positives.json",
    ];

    private $apiInfo = [
        'poweredBy' => 'COVID-19 Japan Web API',
        'poweredByUrl' => 'https://documenter.getpostman.com/view/9215231/SzYaWe6h',
        'updatedAt' => '',
    ];

    const MODE_PREFECTURE = 'prefectures';
    const MODE_TOTAL = 'total';

    // tokio
    const DEFAULT_PREF = '13';

    // method chain current data
    private string $currentMode = 'prefectures';

    public function __construct(
        string $localStorageDir = './storage/api/',
        private string $prefectureId = self::DEFAULT_PREF,
        string $logPath = './storage/api/logs/convid19api.log',
    ) {
        parent::__construct($localStorageDir, $logPath);
    }

    /**
     * set prefecture for pefectures(), positives()
     *
     * @param string $code 1 - 47
     * @return $this
     */
    public function setPrefecture(string $code): self
    {
        if ((int)$code < 1 or (int)$code > 47){
            $code = self::DEFAULT_PREF;
        }
        $this->prefectureId = $code;
        return $this;
    }

    public function resetPrefecture(): self
    {
        $this->prefectureId = self::DEFAULT_PREF;
        return $this;
    }

    /**
     * get statistical data by prefectures
     *
     * @return $this
     */
    public function prefectures(): self
    {
        $this->currentMode = 'prefectures';
        return $this;
    }

    /**
     * get national statistical data
     *
     * @return $this
     */
    public function total(): self
    {
        $this->currentMode = 'total';
        return $this;
    }

    public function getApiInfo()
    {
        return $this->apiInfo;
    }

    public function get()
    {
        $func = fn (array $x) => $x;

        if ($this->currentMode === self::MODE_PREFECTURE) {
            $func = function (array $x) {
                return $this->getPrefectureData($this->prefectureId, $x);
            };
        }

        return $func($this->getByUrl(
            $this->url[$this->currentMode],
            $this->filename[$this->currentMode]
        ));
    }

    /**
     * 全都道府県データから、指定の当道府県のみのデータを取得する
     *
     * @param string|integer $prefCode
     * @param array $allPrefData
     * @return array
     */
    private function getPrefectureData(string|int $prefCode, array $allPrefData): array
    {
        $prefHash = array_filter($allPrefData, function ($prefData) use ($prefCode) {
            return $prefData['id'] === (int)$prefCode;
        });

        // filter でインデックスが歯抜けになるので array_values で採番し直す
        return count($prefHash) > 0 ? array_values($prefHash)[0] : [];
    }

}
