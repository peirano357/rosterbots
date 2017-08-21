<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';
require '../model/Team.class.php';

class RosterTests extends PHPUnit_Framework_TestCase {

    private $dummyArrRosterOKupdate = array();
    private $dummyArrWrongBotQuantity = array();
    private $dummyArrTwoBotsSamePointsAmmount = array();
    

    protected function setUp() {
        $this->dummyArrRosterOKupdate = json_decode(file_get_contents('dummyJSON/rosterOKupdate.json'), true);
        $this->dummyArrWrongBotQuantity = json_decode(file_get_contents('dummyJSON/rosterWrongBotsQuantity.json'), true);
        $this->dummyArrTwoBotsSamePointsAmmount = json_decode(file_get_contents('dummyJSON/rosterWrongTwoBotsSamePoints.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateOKUpdate() {
        $result = Team::validate($this->dummyArrRosterOKupdate, true);

        $this->assertEquals(true, $result['status']);
        $this->assertEquals("OK", $result['message']);
    }

    public function testValidateWrongBotQuantity() {
        $result = Team::validate($this->dummyArrWrongBotQuantity, true);
        $this->assertEquals(false, $result['status']);
        $this->assertContains("Expecting 15 bots for roster.", $result['message']);
    }

    public function testValidateWrongBotsTwoBotsSamePoints() {
        $result = Team::validate($this->dummyArrTwoBotsSamePointsAmmount, true);
        $this->assertEquals(false, $result['status']);
        $this->assertContains("Bots has duplicate total attribute score:", $result['message']);
    }

}
