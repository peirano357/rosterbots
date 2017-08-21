<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
$db = new mysql_db($db_host, $db_user, $db_pwd, $db_name, false);
add_database($db, $db_name);

include_once 'Bot.class.php';
include_once 'Settings_test.class.php';
include_once 'User.class.php';

class Team extends BusinessObject {

    function Team() {
        $this->table_name = "team";
        $this->field_metadata = array(
            "id" => array("int", true, false, false, false, false),
            "idUser" => array("int", false, true, false, false, true),
            "rosterSent" => array("int", false, true, false, false, true),
            "dateCreated" => array("datetime", false, true, false, false, true),
            "dateUpdated" => array("datetime", false, true, false, false, true)
        );
        parent::BusinessObject();
    }

    function fill_ids() {
        global $data_objects;
        $this->data["id"] = $data_objects[$this->db_key]->sql_nextid();
    }

    /**
     * The function below validates an array containing the information of
     * a new roster or an existing one, with an array of associated bots 
     * @param array $arrData
     * @param boolean $is_update
     * @return array
     */
    public static function validate($arrData, $is_update = false) {

        $response['status'] = true;
        $response['message'] = 'OK';

        // bots must be 15
        if (!isset($arrData['bots']) or count($arrData['bots']) != 15) {
            $response['status'] = false;
            $response['message'] = 'Expecting 15 bots for roster. Found ' . count($arrData['bots']);
            return $response;
        }

        // VALIDATE BOT UNIQUE POINTS
        $botsUnique = self::validateBotsUnique($arrData['bots']);
        if ($botsUnique['status'] === false) {
            $response = $botsUnique;
            return $response;
        }

        if ($is_update === true) {
            if (!isset($arrData['id']) or self::getRosterById($arrData['id']) === false) {
                $response['status'] = false;
                $response['message'] = 'Missing roster ID for updating, or roster ID not valid.';
                return $response;
            }
        }

        if ($is_update === false) {
            $objClass = new Team();
            if ($objClass->getRosterByUserHash($arrData['token']) !== false) {
                $response['status'] = false;
                $response['message'] = 'This user has already created a roster, please update instead.';
                return $response;
            }
        }

        // USER MUST EXIST
        $exists = User::getUserByToken($arrData['token']);
        if ($exists === false) {
            $response['status'] = false;
            $response['message'] = 'User token not valid for roster.';
            return $response;
        }


        // VALIDATE EACH BOT POINTS QUANTITY
        $pointsEachBot = self::validateEachBotPoints($arrData['bots']);
        if ($pointsEachBot['status'] === false) {
            $response = $pointsEachBot;
            return $response;
        }


        // VALIDATE BOT POINTS QUANTITY (SUM)
        $pointsTotal = self::validateBotsPoints($arrData['bots']);
        if ($pointsTotal['status'] === false) {
            $response = $pointsTotal;
            return $response;
        }


        // VALIDATE IF GIVEN BOTS EXIST IN DATABASE
        if ($is_update === true) {
            $botsValid = Bot::validateBotsExist($arrData['bots']);
            if ($botsValid['status'] === false) {
                $response = $botsValid;
                return $response;
            }
        }

        // VALIDATE UNIQUE BOTS NAME IN ARRAY (FOR UPDATING)
        if ($is_update === true) {
            for ($i = 0; $i < count($arrData['bots']); $i++) {
                $botNameValid = Bot::getBotIdByName($arrData['bots'][$i]['name']);
                if ($botNameValid !== false) {
                    if ($botNameValid != $arrData['bots'][$i]['id']) {
                        $response['status'] = false;
                        $response['message'] = 'Bot name "' . $arrData['bots'][$i]['name'] . '" is being used by another bot .'
                                . 'Check Bot number ' . ($i + 1) . ' in array.';
                        return $response;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Validates if a given array of bots do not exceed the maximum allowed
     * team points set in the configuration table
     * @param array $bots
     * @return array
     */
    protected function validateBotsPoints($bots) {

        $response['status'] = true;
        $response['message'] = 'OK';

        $pointsQ = 0;

        for ($i = 0; $i < count($bots); $i++) {
            $pointsQ = $pointsQ + (int) $bots[$i]['strength'] + (int) $bots[$i]['speed'] + (int) $bots[$i]['agility'];
        }

        $settingsArr = Settings::getSettings();
        if (is_array($settingsArr)) {
            $maxTeamPoints = $settingsArr['maxTeamPoints'];
            if ($pointsQ > $maxTeamPoints) {
                $response['status'] = false;
                $response['message'] = 'Bots exceed maximum allowed points (' . $settingsArr['maxTeamPoints'] . '). Your current team points is ' . $pointsQ;
                return $response;
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Could not found settings table';
            return $response;
        }
        return $response;
    }

    /**
     * Validates if in a given array of bots, each one is unique in points sum
     * set in the configuration table
     * @param array $bots
     * @return array
     */
    protected function validateBotsUnique($bots) {

        $response['status'] = true;
        $response['message'] = 'OK';

        for ($i = 0; $i < count($bots); $i++) {
            $pointsBot[$i] = (int) $bots[$i]['strength'] + (int) $bots[$i]['speed'] + (int) $bots[$i]['agility'];
        }
        $cnt_array = array_count_values($pointsBot);
        foreach ($cnt_array as $key => $val) {
            if ($val == 1) {
                // 'unique';
            } else {
                // 'duplicate';
                $response['status'] = false;
                $response['message'] = 'Bots has duplicate total attribute score: ' . $key;
                return $response;
            }
        }
        return $response;
    }

    /**
     * Validates if in a given array of bots, any of them has less 
     * than the maximum points per bot
     * set in the configuration table
     * @param array $bots
     * @return array
     */
    protected function validateEachBotPoints($bots) {

        $response['status'] = true;
        $response['message'] = 'OK';

        $settingsArr = Settings::getSettings();
        if (is_array($settingsArr)) {
            $maxBotPoints = $settingsArr['maxBotPoints'];

            for ($i = 0; $i < count($bots); $i++) {
                $pointsBot[$i] = (int) $bots[$i]['strength'] + (int) $bots[$i]['speed'] + (int) $bots[$i]['agility'];

                if ($pointsBot[$i] > $maxBotPoints) {
                    $response['status'] = false;
                    $response['message'] = 'Each bot can not have more than ' . $maxBotPoints . ' points. '
                            . 'Bot number ' . ($i + 1) . ' has ' . $pointsBot[$i];
                    return $response;
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Could not found settings table';
            return $response;
        }
        return $response;
    }

    public function getRosterByUserHash($hash) {
        $data = new Team();
        $data->add_filter('idUser', 'IN', ' (SELECT id FROM user where token LIKE "' . $hash . '")');

        if ($data->load()) {

            $arrData['id'] = $data->get_data('id');
            $arrData['name'] = $data->get_data('name');
            $arrData['dateCreated'] = $data->get_data('dateCreated');
            $arrData['dateUpdated'] = $data->get_data('dateUpdated');

            //LOAD BOTS
            $bots = new BotCollection();
            $bots->add_filter('idTeam', '=', (int) $arrData['id']);
            $bots->load();
            $count = $bots->get_count();
            if ($count != 0) {
                for ($i = 0; $i < $count; $i++) {
                    $bot = &$bots->items[$i];
                    $botsArr[$i]['id'] = $bot->get_data("id");
                    $botsArr[$i]['name'] = $bot->get_data("name");
                    $botsArr[$i]['strength'] = $bot->get_data("strength");
                    $botsArr[$i]['speed'] = $bot->get_data("speed");
                    $botsArr[$i]['agility'] = $bot->get_data("agility");
                    $botsArr[$i]['idTeam'] = $bot->get_data("idTeam");
                }
            }

            $arrData['bots'] = $botsArr;
            return $arrData;
        } else {

            return false;
        }
    }

    public static function getRosterById($id) {
        $result = false;
        $data = new Team();
        $data->add_filter("id", "=", (int) $id);

        if ($data->load()) {
            $result['id'] = $data->get_data('id');
            $result['name'] = $data->get_data('name');
            $result['dateCreated'] = $data->get_data('dateCreated');
            $result['dateUpdated'] = $data->get_data('dateUpdated');
        }
        return $result;
    }

    public static function create($arrParams) {
        $result = false;

        $data = new Team();
        $data->set_data('idUser', User::getUserIdByToken($arrParams['token']));
        $data->set_data('dateCreated', date('Y-m-d H:i:s'));
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            // update bots
            $idTeam = $data->get_data('id');
            for ($i = 0; $i < count($arrParams['bots']); $i++) {
                $arrParams['bots'][$i]['idTeam'] = $idTeam;
                $iBot = Bot::create($arrParams['bots'][$i]);

                if ($iBot === false) {
                    return false;
                }
            }
            return $idTeam;
        }
        return false;
    }

    public static function update($arrParams) {
        $result = false;

        $data = new Team();
        $data->add_filter('id', '=', $arrParams['id']);
        $data->load();
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            // update bots
            for ($i = 0; $i < count($arrParams['bots']); $i++) {
                $arrParams['bots'][$i]['idTeam'] = $arrParams['id'];
                $iBot = Bot::update($arrParams['bots'][$i]);

                if ($iBot === false) {
                    return false;
                }
            }
            return $arrParams['id'];
        }
        return false;
    }

    /**
     * Call the function below for deleting an entire roster 
     * with its associated bots from database
     * @param string $token
     */
    protected static function clearRosterByUserToken($token) {
        $roster = new Team();
        $roster->add_filter('idUser', 'IN', '(SELECT id FROM user WHERE token LIKE "' . $token . '") ');
        if ($roster->load()) {
            $idTeam = $roster->get_data('id');
            $roster->mark_deleted();
            $roster->save();
            BotCollection::clearBotsForRoster($idTeam);
        }
    }

}

class TeamCollection extends BusinessObjectCollection {

    function TeamCollection() {
        parent::BusinessObjectCollection();
    }

    function create_singular($row) {
        $obj = new Team();
        $obj->load_from_list($row);
        return $obj;
    }

}
