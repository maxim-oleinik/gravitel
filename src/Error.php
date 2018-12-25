<?php namespace Gravitel;

class Error extends \Exception
{
    private $debugInfo;

    /**
     * Конструктор
     *
     * @param string     $message
     * @param int        $code
     * @param array|null $debugInfo
     */
    public function __construct($message, $code = 0, array $debugInfo = null)
    {
        parent::__construct($message, $code);
        $this->debugInfo = $debugInfo;
    }


    /**
     * @return array - Массив отладочной информации
     */
    public function getDebugInfo()
    {
        return $this->debugInfo;
    }
}
