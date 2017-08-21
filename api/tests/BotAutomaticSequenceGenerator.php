<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';
require '../model/Bot.class.php';

class BotTests extends PHPUnit_Framework_TestCase {

    private $dummyString1 = 'AAA0000';
    private $dummyString2 = 'AAZ0000';
    private $dummyString3 = 'AAZ9999';
    private $dummyString4 = 'ZZA1234';

    public function testValidateAutomaticSequenceGenerationFromLastDatabaseBot() {
        $result = Bot::calculateNextSequence();
        $this->assertEquals('AAA0016', $result);
    }

    public function testValidateAutomaticSequenceGeneration1() {
        $result = Bot::calculateNextSequence($this->dummyString1);
        $this->assertEquals('AAA0001', $result);
    }

    public function testValidateAutomaticSequenceGeneration2() {
        $result = Bot::calculateNextSequence($this->dummyString2);
        $this->assertEquals('AAZ0001', $result);
    }

    public function testValidateAutomaticSequenceGeneration3() {
        $result = Bot::calculateNextSequence($this->dummyString3);
        $this->assertEquals('ABA0000', $result);
    }

    public function testValidateAutomaticSequenceGeneration4() {
        $result = Bot::calculateNextSequence($this->dummyString4);
        $this->assertEquals('ZZA1235', $result);
    }

}
