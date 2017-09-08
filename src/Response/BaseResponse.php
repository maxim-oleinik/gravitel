<?php namespace Gravitel\Response;


class BaseResponse
{
    public function __construct(array $props = null)
    {
        if ($props) {
            foreach ($props as $key => $value) {
                if (!property_exists($this, $key)) {
                    throw new \InvalidArgumentException(get_class($this).'::'.__FUNCTION__.": Unexpected attribute ({$key})");
                }
                $this->$key = $value;
            }
        }
    }

}
