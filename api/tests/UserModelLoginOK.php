<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrLoginOK = array();

    protected function setUp() {
        $this->dummyArrLoginOK = json_decode(file_get_contents('dummyJSON/loginOK.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateLoginOK() {
        $user = new User();
        
        $result = $user->getUserByCredentials($this->dummyArrLoginOK['userName'], $this->dummyArrLoginOK['password']);
        $this->assertEquals($this->dummyArrLoginOK['userName'], $result['userName']);

        $this->assertEquals('José', $result['firstName']);
        $this->assertEquals('1', $result['id']);
        $this->assertEquals('Peirano', $result['lastName']);
        $this->assertEquals('peirano357@gmail.com', $result['email']);
    }

}
