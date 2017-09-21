<?php namespace Gravitel\Test;

use Gravitel\Gravitel;
use Gravitel\Response\Group;


/**
 * @see \Gravitel\Gravitel
 */
class CmdGroupsTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $transport = $this->createMock(\Gravitel\TransportInterface::class);
        $transport->method('getHttpCode')->willReturn(200);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with('url', [
                'cmd'    => 'groups',
                'token'  => 'token',
            ])
            ->willReturn(json_encode([
                $g1 = ["id"=>"sales","realName"=>"Отдел продаж", 'ext'=>701],
                $g2 = ["id"=>"buhgalterija_g1551011445322666863","realName"=>"Бухгалтерия", 'ext'=>705],
                $g3 = ["id"=>"test_g7114301475043373338","realName"=>"Тест", 'ext'=>708],
            ]));

        $g = new Gravitel($transport, 'url', 'token');
        $result = $g->groups();
        $this->assertInternalType('array', $result);
        $this->assertEquals([
            new Group($g1),
            new Group($g2),
            new Group($g3),
        ], $result);
    }

}
