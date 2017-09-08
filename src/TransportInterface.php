<?php namespace Gravitel;


interface TransportInterface
{
    /**
     * Отправить HTTP-запрос
     *
     * @param  string       $url
     * @param  array|string $postData
     * @param  int          $timeout
     * @param  array        $options
     * @return array
     */
    public function send($url, $postData = null, $timeout = 60, $options = []);

    public function getHttpCode();
}
