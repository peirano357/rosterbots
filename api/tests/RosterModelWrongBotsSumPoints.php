<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';
require '../model/Team.class.php';

class RosterTests extends PHPUnit_Framework_TestCase {

    private $dummyArrRosterWrongBotsSumPoints = array();
    
    protected function setUp() {
        $this->dummyArrRosterWrongBotsSumPoints = json_decode(file_get_contents('dummyJSON/rosterWrongBotsSumPoints.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateRosterWrongBotsSumPoints() {
        $result = Team::validate($this->dummyArrRosterWrongBotsSumPoints, true);
        $this->assertEquals(false, $result['status']);
        $this->assertContains("Bots exceed maximum allowed points", $result['message']);
    }
}
