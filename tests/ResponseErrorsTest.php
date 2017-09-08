<?php namespace Gravitel\Test;

use Gravitel\Gravitel;


/**
 * @see \Gravitel\Gravitel
 */
class ResponseErrorsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Invalid parameters
     */
    public function testInvalidParameters()
    {
        $transport = $this->_make_transport(400, '{"error":"Invalid parameters"}');
        $g = new Gravitel($transport, 'url', 'token');

        $this->expectException(\Gravitel\Error::class);
        $this->expectExceptionMessage('Invalid parameters');
        $this->expectExceptionCode(400);
        $g->makeCall('user', 'phone');
    }


    /**
     * Invalid token
     */
    public function testInvalidToken()
    {
        $transport = $this->_make_transport(401, '{"error":"Invalid token"}');
        $g = new Gravitel($transport, 'url', 'token');

        $this->expectException(\Gravitel\Error::class);
        $this->expectExceptionMessage('Invalid token');
        $this->expectExceptionCode(401);
        $g->makeCall('user', 'phone');
    }


    /**
     * Not Json
     */
    public function testNotJsonResponse()
    {
        $transport = $this->_make_transport(200, $str = 'not a json');
        $g = new Gravitel($transport, 'url', 'token');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Gravitel unexpected response, got ({$str})");
        $g->makeCall('user', 'phone');
    }


    /**
     * Server Error
     */
    public function testServerError()
    {
        $transport = $this->_make_transport(502, '502 Gateway Timeout');
        $g = new Gravitel($transport, 'url', 'token');

        $this->expectException(\Gravitel\Error::class);
        $this->expectExceptionMessage("Ошибка сервера");
        $g->makeCall('user', 'phone');
    }


    /**
     * Transport
     *
     * @param $statusCode
     * @param $responseText
     * @return \Gravitel\TransportInterface
     */
    private function _make_transport($statusCode, $responseText)
    {
        $transport = $this->createMock(\Gravitel\TransportInterface::class);
        $transport
            ->expects($this->once())
            ->method('send')
            ->willReturn($responseText);

        $transport
            ->method('getHttpCode')
            ->willReturn($statusCode);

        return $transport;
    }

}
