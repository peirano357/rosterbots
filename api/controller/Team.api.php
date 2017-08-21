<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */

namespace api\Controller;

include_once(BASEPATH . "/settings.php");
include_once '../lib/mygen_framework.php';
include_once '../lib/mygen_mysql.php';
include_once '../lib/DBConn.php';
include_once 'model/Team.class.php';
include_once 'model/User.class.php';
include_once 'model/Bot.class.php';

class Team extends ApiController {

    protected $basePath = '/roster';

    public function __construct($app) {
        parent::__construct();
    }

    public function index() {

        $this->app->post($this->basePath, array(
            $this, 'generateRoster'));

        $this->app->put($this->basePath, array(
            $this, 'updateRoster'));

        $this->app->get($this->basePath . "/detail/:hash", array(
            $this, 'getRosterDetail'));
    }

    /*
     * Use the function below for updating an existing roster
     * with its 15 bots for a given USER
     */

    public function updateRoster() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $valid = \Team::validate($body, true);
            if ($valid['status'] === true) {
                $result = \Team::update($body);
                if ($result !== false) {
                    $this->app->view()->setResponse($result, '200', "OK");
                } else {
                    $this->app->view()->setResponse(false, '500', 'Internal error when generating roster.');
                }
            } else {
                $this->app->view()->setResponse(false, '409', $valid['message']);
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    /*
     * Use the function below function for auto-generating a roster 
     * with its 15 bots for a given USER
     */

    public function generateRoster() {
        $body = json_decode($this->app->request->getBody(), true);
        try {
            $arrBots = \BotCollection::autogenerateBots(15);
            $body['bots'] = $arrBots['data'];
            $valid = \Team::validate($body);
            if ($valid['status'] === true) {
                $result = \Team::create($body);
                if ($result !== false) {
                    $this->app->view()->setResponse($result, '201', "OK");
                } else {
                    $this->app->view()->setResponse(false, '500', 'Internal error when generating roster.');
                }
            } else {
                $this->app->view()->setResponse(false, '409', $valid['message']);
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    public function getRosterDetail($hash) {

        try {
            $result = \Team::getRosterByUserHash($hash);
            if ($result !== false) {
                $this->app->view()->setResponse($result, '200', "OK");
            } else {
                $this->app->view()->setResponse(false, '404', 'Roster not found for this user.');
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

    public function getTeamList($id) {
        try {
            $result = \TeamCollection::getTeamListByUser($id);
            if ($result !== false) {
                $this->app->view()->setResponse($result, '200', "OK");
            } else {
                $this->app->view()->setResponse(false, '404', 'There are no available teams for this user, or user does not exists.');
            }
        } catch (\Exception $e) {
            $this->app->view()->setResponse(false, '409', $e->getMessage());
        }
        $this->app->render('json');
    }

}
