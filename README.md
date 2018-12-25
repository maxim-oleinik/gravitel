Gravitel API wrapper
====================

**doc version:** 2017-08


Install
-------
1. Add to `composer`

        composer require maxim-oleinik/gravitel

2. Implement \Gravitel\TransportInterface

        class MyHttpTransport implements \Gravitel\TransportInterface


Send Command
------------

#### Список исходящих команд

* Инициировать звонок

        /**
         * @param  string $user     - Логин оператора
         * @param  string $phone    - Номер телефона куда звоним
         * @param  string $phoneExt - Номер исходящего телефона для АОН
         */
        \Gravitel\Gravitel::makeCall($user, $phone, $phoneExt = null): \Gravitel\Response\MakeCallResponse`

* Получить список групп

        \Gravitel\Gravitel::groups(): \Gravitel\Response\Group[]

* Включить или выключить прием звонков сотрудником во всех его отделах

        \Gravitel\Gravitel::subscribeOnCalls($user, $enable, $groupId = null)


#### Вызов и обработка ошибок
```
    $gravitel = new Gravitel(new MyHttpTransport, $gravitelApiUrl, $gravitelApiToken);
    try {
        $response = $gravitel->makeCall($login, $phone);
    } catch (\Gravitel\Error $e) {
        $e->getCode(); // 400, 401, 500
        $e->getMessage();
        $e->getDebugInfo();
    }
```


Callback
--------
Обработка входящих запросов от Гравител

```
    $cmd = \Gravitel\Callback\CallbackFactory::make($_REQUEST);

    // Проверка токена (Авторизация)
    if ($cmd->crm_token != $myPrivateToken) {
        throw new \InvalidArgumentException(__METHOD__.": Token invalid `{$cmd->crm_token}`");
    }

    switch (get_class($cmd)) {
        case \Gravitel\Callback\HistoryCmd::class:
            // ...
            break;
        case \Gravitel\Callback\EventCmd::class:
            // ...
            break;
        case \Gravitel\Callback\ContactCmd::class:
            // ...
            break;
        default:
            break;
    }
```
