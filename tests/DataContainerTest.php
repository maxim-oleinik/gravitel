<?php namespace Gravitel\Test;

use Gravitel\DataContainer;


class DataContainerTestModel extends DataContainer
{
    public $code;
    public $name;
}


/**
 * @see \Gravitel\DataContainer
 */
class DataContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Инициализация объекта из конструктора
     */
    public function testInitFromConstructor()
    {
        $ob = new DataContainerTestModel($input = [
            'code' => 'Some code',
            'name' => 'Some name',
        ]);

        $this->assertSame($input['code'], $ob->code);
        $this->assertSame($input['name'], $ob->name);
        $this->assertEquals($input, $ob->toArray());
    }


    /**
     * Исключение, если получен неизвестный параметр
     */
    public function testExceptionIfUnknownAttribute()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DataContainerTestModel([
            'code' => 'Some code',
            'name' => 'Some name',
            'unknown_attr' => 1,
        ]);
    }

}
