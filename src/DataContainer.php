<?php namespace Gravitel;


/**
 * @see \Gravitel\Test\DataContainerTest
 */
class DataContainer
{
    /**
     * Конструктор
     *
     * @param array $props
     */
    public function __construct(array $props = null)
    {
        if ($props) {
            foreach ($props as $key => $value) {
                if (strpos($key, '-') !== false) {
                    $key = str_replace('-', '_', $key);
                }
                if (!property_exists($this, $key)) {
                    throw new \InvalidArgumentException(get_class($this).'::'.__FUNCTION__.": Unexpected attribute ({$key})");
                }
                $this->$key = $value;
            }
        }
    }


    /**
     * To Array
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this;
    }

}
