<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrRepeatedEmail = array();

    protected function setUp() {
        $this->dummyArrRepeatedEmail = json_decode(file_get_contents('dummyJSON/userRepeatedEmail.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateRepeatedEmail() {

        $result = User::validate($this->dummyArrRepeatedEmail);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("Given email address exists for another user.", $result['message']);
    }

}
