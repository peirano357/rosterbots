<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';

require '../model/User.class.php';

class UserTests extends PHPUnit_Framework_TestCase {

    private $dummyArrOK = array();

    protected function setUp() {

        mysql_connect("localhost", "root", "") or die(mysql_error());
        mysql_select_db("rosterbots_testing") or die(mysql_error());
        mysql_query("BEGIN");

        $this->dummyArrOK = json_decode(file_get_contents('dummyJSON/userOK.json'), true);
    }

    protected function tearDown() {
        mysql_query("ROLLBACK");
    }

    public function testCreateUserOK() {
        $result = User::create($this->dummyArrOK);
        // should return ID from previously created user
        // lets check user By Id
        $user = new User();
        $user->add_filter('id', '=', $result);
        $user->load();
        $this->assertEquals($user->get_data('id'), $result);

        return $user->get_data('token');
    }

}
