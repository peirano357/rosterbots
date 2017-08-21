<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrRepeatedUsername = array();

    protected function setUp() {
        $this->dummyArrRepeatedUsername = json_decode(file_get_contents('dummyJSON/userRepeatedUsername.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateRepeatedUsername() {

        $result = User::validate($this->dummyArrRepeatedUsername);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("Given username exists for another user.", $result['message']);
    }

}
