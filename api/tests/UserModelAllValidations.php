<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrOK = array();
    private $dummyArrNoUsername = array();
    private $dummyArrNoPassword = array();
    private $dummyArrNoEmail = array();
    private $dummyArrNoFirstName = array();
    private $dummyArrNoLastName = array();

    protected function setUp() {
        $this->dummyArrOK = json_decode(file_get_contents('dummyJSON/userOK.json'), true);
        $this->dummyArrNoUsername = json_decode(file_get_contents('dummyJSON/userNoUsername.json'), true);
        $this->dummyArrNoEmail = json_decode(file_get_contents('dummyJSON/userNoEmail.json'), true);
        $this->dummyArrNoPassword = json_decode(file_get_contents('dummyJSON/userNoPassword.json'), true);
        $this->dummyArrNoFirstName = json_decode(file_get_contents('dummyJSON/userNoFirstName.json'), true);
        $this->dummyArrNoLastName = json_decode(file_get_contents('dummyJSON/userNoLastName.json'), true);
    }

    protected function tearDown() {
        
    }

    public function testValidateOK() {
        $result = User::validate($this->dummyArrOK);
        $this->assertEquals(true, $result['status']);
        $this->assertEquals("OK", $result['message']);
    }

    public function testValidateNoUsername() {
        $result = User::validate($this->dummyArrNoUsername);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("You must input a Username for your account.", $result['message']);
    }

    public function testValidateNoEmail() {
        $result = User::validate($this->dummyArrNoEmail);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("You must input your Email Address.", $result['message']);
    }

    public function testValidateNoPassword() {
        $result = User::validate($this->dummyArrNoPassword);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("You must input a Password for your account.", $result['message']);
    }

    public function testValidateNoFirstName() {
        $result = User::validate($this->dummyArrNoFirstName);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("You must input your First Name.", $result['message']);
    }

    public function testValidateNoLastName() {
        $result = User::validate($this->dummyArrNoLastName);
        $this->assertEquals(false, $result['status']);
        $this->assertEquals("You must input your Last Name.", $result['message']);
    }
}