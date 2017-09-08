<?php namespace Gravitel;

use Gravitel\Response\MakeCallResponse;


class Gravitel
{
    /**
     * @var \Gravitel\TransportInterface
     */
    private $transport;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $token;


    /**
     * Конструктор
     *
     * @param TransportInterface $transport
     * @param  string            $url
     * @param  string            $token
     */
    public function __construct(TransportInterface $transport, $url, $token)
    {
        $this->transport = $transport;
        $this->url = $url;
        $this->token = $token;
    }


    /**
     * @throws \Gravitel\Error
     * @param $user
     * @param $phone
     * @return \Gravitel\Response\MakeCallResponse
     *
     */
    public function makeCall($user, $phone)
    {
        $data = [
            'cmd'   => 'makeCall',
            'user'  => $user,
            'token' => $this->token,
            'phone' => $phone,
        ];
        $responce = $this->transport->send($this->url, $data);

        return new MakeCallResponse($this->_parse_response($responce));
    }


    private function _parse_response($response)
    {
        if ($this->transport->getHttpCode() >= 500) {
            throw new Error('Ошибка сервера');
        }

        $data = json_decode($response, true);
        if (null === $data) {
            throw new \RuntimeException("Request: {$this->url}\nGravitel unexpected response, got ({$response})");
        }
        if (isset($data['error'])) {
            throw new Error($data['error'], $this->transport->getHttpCode());
        }
        return $data;
    }

}
