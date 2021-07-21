<?php

namespace App\Libraries;

class CurlLib
{
    protected static $default_header = [
        'Accept: application/json,text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'Keep-Alive: 300',
        'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'Accept-Language: zh-TW,zh;q=0.8,en-US;q=0.6,en;q=0.4',
        'Pragma: ',
    ];

    protected static $default_curl_options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => 0,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
    ];

    public static function initHandler($options = [])
    {
        $ch = curl_init();
        $curl_options = self::$default_curl_options;

        if (isset($options['curl_options']) and is_array($options['curl_options'])) {
            foreach ($options['curl_options'] as $key => $value) {
                $curl_options[$key] = $value;
            }
        }

        foreach ($curl_options as $key => $value) {
            curl_setopt($ch, $key, $value);
        }

        return $ch;
    }

    public static function setDefaultCurlOption($option, $value)
    {
        self::$default_curl_options[$option] = $value;
    }

    public static function get($url, $options = [])
    {
        $header = self::$default_header;
        if (isset($options['headers']) and is_array($options['headers'])) {
            $header = $options['headers'];
        }

        $ch = self::initHandler($options);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/14.0.1');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if (isset($options['USERAGENT'])) {
            curl_setopt($ch, CURLOPT_USERAGENT, $options['USERAGENT']);
        }
        $output = curl_exec($ch);
        $error_message = '';
        if (false === $output) {
            $error_message = curl_errno($ch) . ' ' . curl_error($ch);
        }
        $info = curl_getinfo($ch);
        curl_close($ch);

        return [$info['http_code'], $output, $info['url'], $error_message];
    }
}
