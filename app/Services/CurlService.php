<?php

namespace App\Services;


class CurlService
{
    /**
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function get($url)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        $this->setDefaultOpts($ch);

        return curl_exec($ch);
    }

    public function get_multi($urls)
    {
        $chs = [];

        foreach ($urls as $url) {
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_URL, $url);
            $this->setDefaultOpts($ch);

            $chs [] = $ch;
        }

        $mh = curl_multi_init();

        foreach ($chs as $ch) {
            curl_multi_add_handle($mh, $ch);
        }

        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        foreach ($chs as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        $results = [];

        foreach ($chs as $ch) {
            $results [] = curl_multi_getcontent($ch);
        }

        return $results;
    }

    private function setDefaultOpts(&$ch)
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Upgrade-Insecure-Requests: 1'));
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
}