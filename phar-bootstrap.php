<?php

class PHPUnit_Framework_TestCase extends PHPUnit\Framework\TestCase
{
    public function getMock($class)
    {
        return $this->createMock($class);
    }
}
