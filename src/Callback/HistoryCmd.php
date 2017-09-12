<?php namespace Gravitel\Callback;

use Gravitel\DataContainer;


/**
 * После успешного звонка в CRM отправляется запрос с данными о звонке и ссылкой на запись разговора.
 * Команда может быть использована для сохранения в данных ваших клиентов истории и записей входящих и исходящих звонков.
 */
class HistoryCmd extends DataContainer
{
    const STATUS_SUCCESS      = 'Success';      // успешный входящий/исходящий звонок
    const STATUS_missed       = 'missed';       // пропущенный входящий звонок
    const STATUS_BUSY         = 'Busy';         // мы  получили ответ Занято
    const STATUS_NOTAVAILABLE = 'NotAvailable'; // мы получили ответ Абонент недоступен
    const STATUS_NOTALLOWED   = 'NotAllowed';   // мы получили ответ Звонки на это направление  запрещены

    public $cmd;            // тип операции
    public $type;           // это тип события, связанного со звонком
    public $phone;          // номер телефона клиента
    public $diversion;      // ваш  номер  телефона,  через  который  пришел  входящий вызов
    public $user;           // идентификатор  пользователя  облачной  АТС  (необходим для сопоставления на стороне CRM)
    public $groupRealName;  // название  отдела,  если  входящий  звонок  прошел  через отдел
    public $ext;            // внутренний номер пользователя облачной АТС, если есть
    public $telnum;         // прямой  телефонный  номер  пользователя  облачной АТС, если есть
    public $callid;         // уникальный id звонка, совпадает для всех связанных звонков
    public $crm_token;      // ключ (token) от CRM,  установленный в веб-кабинете
    public $start;          // время начала YYYYmmddTHHMMSSZ звонка в формате
    public $duration;       // общая длительность звонка в секундах
    public $link;           // ссылка на запись звонка, если она включена в Облачной АТС
    public $status;         // статус входящего/исходящего звонка
    public $z_flag;
}
