<?php namespace Gravitel;

use Gravitel\Response\MakeCallResponse;


/**
 * @see \Gravitel\Test\ResponseErrorsTest
 * @see \Gravitel\Test\MakeCallTest
 * @see \Gravitel\Test\SubscribeOnCallsTest
 */
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
     * Инициировть звонок
     *
     * @see \Gravitel\Test\MakeCallTest
     *
     * @throws \Gravitel\Error
     * @param  string $user - Логин оператора
     * @param  string $phone
     * @return \Gravitel\Response\MakeCallResponse
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
     * Включить или выключить прием звонков сотрудником во всех его отделах
     *
     * @see \Gravitel\Test\SubscribeOnCallsTest
     *
     * @param string $user    - Логин оператора
     * @param bool   $enable  - Вкл/выкл
     * @param string $groupId - Изменить состояние только в указанной группе
     * @return bool
     */
    public function subscribeOnCalls($user, $enable, $groupId = null)
    {
        $data = [
            'cmd'    => 'subscribeoncalls',
            'user'   => $user,
            'status' => $enable ? 'on' : 'off',
            'token'  => $this->token,
        ];
        if ($groupId) {
            $data['group_id'] = $groupId;
        }

        $response = $this->transport->send($this->url, $data);
        return $this->_parse_response($response);
    }


    /**
     * Разобрать ответ в массив
     *
     * @param $response
     *
     * @return array|bool
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

            // Если код 200 и тело пустое
            if ($this->transport->getHttpCode() == 200 && !$response) {
                return true;
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
