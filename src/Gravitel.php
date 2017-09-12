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
        $response = $this->transport->send($this->url, $data);

        return new MakeCallResponse($this->_parse_response($response));
    }


    /**
     * Разобрать ответ в массив
     *
     * @param $response
     * @return array
     *
     * @throws \Gravitel\Error
     */
    private function _parse_response($response)
    {
        do {
            // Если код ответа не 200 или 400
            if (!in_array(floor($this->transport->getHttpCode()/100), [2, 4])) {
                $errorMess = 'Ошибка сервера';
                break;
            }

            $data = json_decode($response, true);
            if (null === $data) {
                $errorMess = 'Unexpected response';
                break;
            }

            if (isset($data['error'])) {
                $errorMess = $data['error'];
                break;
            }

            return $data;

        } while (false);

        throw new Error('Gravitel: '.$errorMess, $this->transport->getHttpCode(), $this->transport->getDebugInfo());
    }

}
