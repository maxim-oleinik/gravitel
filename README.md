Gravitel API wrapper
====================

**doc version:** 2017-08

Install
-------
1. Add to composer.json
```
    {
      "require": {
        "gravitel": "dev-master",
      },
      "repositories": {
        "gravitel": {
          "type": "git",
          "url": "https://github.com/maxim-oleinik/gravitel.git"
        },
      },
    }
```

2. Implement \Gravitel\TransportInterface
```
    class MyHttpTransport implements \Gravitel\TransportInterface
```


Send Command
------------
**Список исходящих команд**
* `\Gravitel\Gravitel::makeCall($user, $phone): \Gravitel\Response\MakeCallResponse`  
    Инициировать звонок
* `\Gravitel\Gravitel::groups(): \Gravitel\Response\Group[]`  
    Получить список групп
* `\Gravitel\Gravitel::subscribeOnCalls($user, $enable, $groupId = null)`  
    Включить или выключить прием звонков сотрудником во всех его отделах


**Вызов и обработка ошибок**
```
    $gravitel = new Gravitel(new MyHttpTransport, $gravitelApiUrl, $gravitelApiToken);
    try {
        $response = $gravitel->makeCall($login, $phone);
    } catch (\Gravitel\Error $e) {
        $e->getCode(); // 200, 400, 401, 500
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
