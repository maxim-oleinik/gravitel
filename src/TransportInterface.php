<?php namespace Gravitel;

interface TransportInterface
{
    /**
     * Отправить HTTP-запрос
     *
     * @param  string       $url
     * @param  array|string $postData
     * @param  array        $options
     * @return array
     */
    public function send($url, $postData = null, $options = []);

    /**
     * HTTP-код ответа
     *
     * @return int
     */
    public function getHttpCode();

    /**
     * Массив отладочной информации (url, post data, код ответа, текст ответа)
     *
     * @return array
     */
    public function getDebugInfo();
}
