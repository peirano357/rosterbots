<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';
require '../model/Team.class.php';

class RosterTests extends PHPUnit_Framework_TestCase {

    private $dummyArrrosterWrongSingleBotMaxPoints = array();

    protected function setUp() {
        $this->dummyArrrosterWrongSingleBotMaxPoints = json_decode(file_get_contents('dummyJSON/rosterWrongSingleBotMaxPoints.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateRosterWrongSingleBotMaxPoints() {
        $result = Team::validate($this->dummyArrrosterWrongSingleBotMaxPoints, true);
        $this->assertEquals(false, $result['status']);
        $this->assertContains("Each bot can not have more than", $result['message']);
    }

}
