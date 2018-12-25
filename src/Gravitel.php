<?php namespace Gravitel;

use Gravitel\Response\Group;
use Gravitel\Response\MakeCallResponse;

/**
 * @see \Gravitel\Test\ResponseErrorsTest
 * @see \Gravitel\Test\MakeCallTest
 * @see \Gravitel\Test\SubscribeOnCallsTest
 * @see \Gravitel\Test\CmdGroupsTest
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
     * @param string             $url
     * @param string             $token
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
     *
     * @return \Gravitel\Response\MakeCallResponse
     */
    public function makeCall($user, $phone)
    {
        $data = [
            'user'  => $user,
            'phone' => $phone,
        ];

        $response = $this->_cmd('makeCall', $data);
        return new MakeCallResponse($response);
    }


    /**
     * Включить или выключить прием звонков сотрудником во всех его отделах
     *
     * @see \Gravitel\Test\SubscribeOnCallsTest
     *
     * @param  string $user    - Логин оператора
     * @param  bool   $enable  - Вкл/выкл
     * @param  string $groupId - Изменить состояние только в указанной группе
     *
     * @return bool
     * @throws \Gravitel\Error
     */
    public function subscribeOnCalls($user, $enable, $groupId = null)
    {
        $data = [
            'user'   => $user,
            'status' => $enable ? 'on' : 'off',
        ];
        if ($groupId) {
            $data['group_id'] = $groupId;
        }

        return $this->_cmd('subscribeoncalls', $data);
    }


    /**
     * Получить список групп
     *
     * @see \Gravitel\Test\CmdGroupsTest
     *
     * @return Group[]
     * @throws \Gravitel\Error
     */
    public function groups()
    {
        $response = $this->_cmd('groups');
        $groups = [];
        foreach ($response as $groupData) {
            $groups[] = new Group($groupData);
        }
        return $groups;
    }


    /**
     * Run API command
     *
     * @param string $cmdName
     * @param array  $data
     *
     * @return array|true
     * @throws \Gravitel\Error
     */
    private function _cmd($cmdName, array $data = [])
    {
        $data['cmd']   = $cmdName;
        $data['token'] = $this->token;

        $response = $this->transport->send($this->url, $data);
        return $this->_parseResponse($response);
    }


    /**
     * Разобрать ответ в массив
     *
     * @param string $response
     *
     * @return array|true
     * @throws \Gravitel\Error
     */
    private function _parseResponse($response)
    {
        do {
            // Если код ответа не 200 или 400
            if (!\in_array(\floor($this->transport->getHttpCode()/100), [2, 4])) {
                $errorMess = 'Ошибка сервера';
                break;
            }

            // Если код 200 и тело пустое
            if ($this->transport->getHttpCode() == 200 && (!$response || 'OK' == $response)) {
                return true;
            }

            $data = \json_decode($response, true);
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
