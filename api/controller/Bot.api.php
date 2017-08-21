<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */

namespace api\Controller;

include_once(BASEPATH . "/settings.php");
include_once '../lib/mygen_framework.php';
include_once '../lib/mygen_mysql.php';
include_once '../lib/DBConn.php';
include_once 'model/Bot.class.php';

class Bot extends ApiController {

    protected $basePath = '/bot';

    public function __construct($app) {
        parent::__construct();
    }

    public function index() {

        $this->app->get($this->basePath . "/:name/:id", array(
            $this, 'checkNameAvailability'));
    }

    public function checkNameAvailability($name, $id) {

        try {
            $result = \Bot::getBotIdByName($name);
            if (($result !== false && $id == $result) || $result == false) {
                $this->app->view()->setResponse($result, '200', "OK");
            } else {
                $this->app->view()->setResponse(false, '409', 'Bot name being used by another Bot. Please change it.');
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }
}