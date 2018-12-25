<?php namespace Gravitel\Test;

use Gravitel\Gravitel;
use Gravitel\Response\MakeCallResponse;

/**
 * @see \Gravitel\Gravitel
 */
class MakeCallTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $uid = '28187487-edcf-4d23-9f95-c8591fbd9936';

        $transport = $this->createMock(\Gravitel\TransportInterface::class);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with('url', [
                'cmd'   => 'makeCall',
                'user'  => 'user',
                'token' => 'token',
                'phone' => 'phone',
                'clid'  => 'phoneExt',
            ])
            ->willReturn('{"uuid":"' . $uid . '"}');

        $transport->method('getHttpCode')->willReturn(200);

        $g = new Gravitel($transport, 'url', 'token');
        $result = $g->makeCall('user', 'phone', 'phoneExt');
        $this->assertInstanceOf(MakeCallResponse::class, $result);
        $this->assertEquals($uid, $result->uuid);
    }
}
