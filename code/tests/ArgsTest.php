<?php

require_once '../src/Args.php';

class ArgsTest extends PHPUnit_Framework_TestCase
{
    public function testMatchingAmountofArgumentsGoesCorrectly() {
        $sut = new Args('a,b', "-a true -b false");
    }

    /**
     * @expectedException ParseException
     */
    public function testMismatchingAmountofArgumentsGivesParseException() {
        $sut = new Args('a,b', "-a true");
    }

    public function testParseAndRetrieveBoolean() {
        $sut = new Args('a', "-a true");

        $this->assertEquals(true, $sut->getB('a'));
    }

    public function testParseAndRetrieveTwoBooleans() {
        $sut = new Args('a, b', "-a true -b false");

        $this->assertEquals(true, $sut->getB('a'));
        $this->assertEquals(false, $sut->getB('b'));
    }

    /**
     * @expectedException ParseException
     */
    public function testParseAndRetrieveBooleanInvalidBoolean() {
        $sut = new Args('a', "-a 12");
    }

    public function testParseAndRetrieveString() {
        $sut = new Args('hallo*', "-hallo \"Hello World\"");

        $this->assertEquals('Hello World', $sut->getS('hallo'));
    }

    public function testParseAndRetrieveTwoStrings() {
        $sut = new Args('hallo*, b*', "-hallo \"Hello World\" -b \"Bob\"");

        $this->assertEquals('Hello World', $sut->getS('hallo'));
        $this->assertEquals('Bob', $sut->getS('b'));
    }

    /**
     * @expectedException ParseException
     */
    public function testParseAndRetrieveStringInvalidString() {
        $sut = new Args('hallo*, b*', "-hallo \"Hello World\" -b Bob");
    }
    
    public function testParseAndRetrieveTwoTypes() {
        $sut = new Args('hello, b*', "-hello false -b \"Bob\"");

        $this->assertEquals(false, $sut->getB('hello'));
        $this->assertEquals('Bob', $sut->getS('b'));
    }
}
