<?php namespace Gravitel\Test;

use Gravitel\Gravitel;

/**
 * @see \Gravitel\Gravitel
 */
class SubscribeOnCallsTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $transport = $this->createMock(\Gravitel\TransportInterface::class);
        $transport->method('getHttpCode')->willReturn(200);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with('url', [
                'cmd'    => 'subscribeoncalls',
                'user'   => 'user',
                'token'  => 'token',
                'status' => 'on',
            ])
            ->willReturn('OK');

        $g = new Gravitel($transport, 'url', 'token');
        $result = $g->subscribeOnCalls('user', true);
        $this->assertTrue($result);
    }


    /**
     * Указать группу
     */
    public function testWithGroupId()
    {
        $transport = $this->createMock(\Gravitel\TransportInterface::class);
        $transport->method('getHttpCode')->willReturn(200);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with('url', [
                'cmd'      => 'subscribeoncalls',
                'user'     => 'user',
                'token'    => 'token',
                'group_id' => $group = 'gravitel-group-id',
                'status'   => 'off',
            ])
            ->willReturn('OK');

        $g = new Gravitel($transport, 'url', 'token');
        $result = $g->subscribeOnCalls('user', false, $group);
        $this->assertTrue($result);
    }
}
