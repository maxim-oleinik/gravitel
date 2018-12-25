<?php namespace Gravitel\Callback;

/**
 * @see \Gravitel\Test\CallbackFactoryTest
 */
class CallbackFactory
{
    /**
     * @param  array $input
     * @return mixed
     */
    public static function make(array $input)
    {
        if (!isset($input['cmd'])) {
            $input['cmd'] = '';
        }

        switch ($input['cmd']) {
            case 'event':
                return new EventCmd($input);

            case 'history':
                return new HistoryCmd($input);

            case 'contact':
                return new ContactCmd($input);

            default:
                throw new \InvalidArgumentException(__METHOD__.": Unknown CMD value `{$input['cmd']}`");
        }
    }
}
