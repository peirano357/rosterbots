<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrLoginOK = array();

    protected function setUp() {
        $this->dummyArrLoginWrong = json_decode(file_get_contents('dummyJSON/loginWrong.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateLoginWrong() {
        $user = new User();
        $result = $user->getUserByCredentials($this->dummyArrLoginOK['userName'], $this->dummyArrLoginOK['password']);
        $this->assertEquals($result, false);
    }

}
