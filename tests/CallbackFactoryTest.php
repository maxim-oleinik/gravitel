<?php namespace Gravitel\Test;

use Gravitel\Callback\CallbackFactory;
use Gravitel\Callback\ContactCmd;
use Gravitel\Callback\EventCmd;
use Gravitel\Callback\HistoryCmd;

/**
 * @see \Gravitel\Callback\CallbackFactory
 */
class CallbackFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Event Cmd
     */
    public function testEvent()
    {
        $cmd = CallbackFactory::make($input = [
            'callid'    => '28187487-edcf-4d23-9f95-c8591fbd9936',
            'cmd'       => 'event',
            'crm_token' => '1',
            'phone'     => '74951234567',
            'type'      => 'CANCELLED',
            'user'      => 'admin',
            'z-flag'    => '0',
        ]);

        $this->assertInstanceOf(EventCmd::class, $cmd);
        $expected = $input;
        $expected['z_flag'] = $input['z-flag'];
        unset($expected['z-flag']);
        $this->assertEquals($expected, array_intersect_key($cmd->toArray(), $expected));
    }


    /**
     * History Cmd
     */
    public function testHistory()
    {
        $cmd = CallbackFactory::make($input = [
            'callid'    => '28187487-edcf-4d23-9f95-c8591fbd9936',
            'cmd'       => 'history',
            'crm_token' => '1',
            'duration'  => '0',
            'ext'       => '701',
            'phone'     => '79161234567',
            'start'     => '20170905T150852Z',
            'status'    => 'Busy',
            'type'      => 'out',
            'user'      => 'admin',
            'z-flag'    => '0',
        ]);

        $this->assertInstanceOf(HistoryCmd::class, $cmd);
        $expected = $input;
        $expected['z_flag'] = $input['z-flag'];
        unset($expected['z-flag']);
        $this->assertEquals($expected, array_intersect_key($cmd->toArray(), $expected));
    }


    /**
     * Contact Cmd
     */
    public function testContact()
    {
        $cmd = CallbackFactory::make($input = [
            'callid'    => '28187487-edcf-4d23-9f95-c8591fbd9936',
            'cmd'       => 'contact',
            'phone'     => '79161234567',
            'crm_token' => '1',
        ]);

        $this->assertInstanceOf(ContactCmd::class, $cmd);
        $this->assertEquals($input, array_intersect_key($cmd->toArray(), $input));
    }


    /**
     * Unknown cmd error
     */
    public function testUnknownCmd()
    {
        $this->expectException(\InvalidArgumentException::class);
        CallbackFactory::make(['cmd' => 'unknown_cmd']);
    }


    /**
     * Cmd not set error
     */
    public function testCmdNotSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        CallbackFactory::make([]);
    }
}
