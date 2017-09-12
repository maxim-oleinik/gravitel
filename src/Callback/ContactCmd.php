<?php namespace Gravitel\Callback;

use Gravitel\DataContainer;


/**
 * Команда для получения информации о названии клиента и ответственном за него сотруднике по номеру его телефона.
 * Команда вызывается при поступлении нового входящего звонка.
 */
class ContactCmd extends DataContainer
{
    public $cmd;        // тип операции
    public $phone;      // номер телефона клиента
    public $crm_token;  // ключ (token) от CRM,  установленный в веб-кабинете
    public $callid;     // уникальный id звонка, совпадает для всех связанных звонков
    public $z_flag;
}
