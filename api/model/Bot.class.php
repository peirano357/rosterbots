<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
$db = new mysql_db($db_host, $db_user, $db_pwd, $db_name, false);
add_database($db, $db_name);

include_once 'Settings.class.php';

class Bot extends BusinessObject {

    function Bot() {
        $this->table_name = "bot";
        $this->field_metadata = array(
            "id" => array("int", true, false, false, false, false),
            "name" => array("text", false, true, false, false, true),
            "idTeam" => array("int", false, true, false, false, true),
            "dateCreated" => array("datetime", false, true, false, false, true),
            "dateUpdated" => array("datetime", false, true, false, false, true),
            "sequence" => array("string", false, true, false, false, true),
            "speed" => array("int", false, true, false, false, true),
            "strength" => array("int", false, true, false, false, true),
            "agility" => array("int", false, true, false, false, true)
        );
        parent::BusinessObject();
    }

    function fill_ids() {
        global $data_objects;
        $this->data["id"] = $data_objects[$this->db_key]->sql_nextid();
    }

    /**
     * Creates a Bot from array params
     * @param array $arrParams
     * @return int or false
     */
    public static function create($arrParams) {
        $result = false;
        $data = new Bot();
        // use this line if you want to assign a random UNIQUE alphanumeric NAME
        $data->set_data('name', self::generateBotName());

        $data->set_data('sequence', "'" . self::calculateNextSequence() . "'");
        $data->set_data('idTeam', (int) $arrParams['idTeam']);
        $data->set_data('strength', $arrParams['strength']);
        $data->set_data('speed', $arrParams['speed']);
        $data->set_data('agility', $arrParams['agility']);
        $data->set_data('dateCreated', date('Y-m-d H:i:s'));
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            return $data->get_data('id');
        }
        // not found
        return false;
    }

    /**
     * Updates a Bot by array params
     * @param array $arrParams
     * @return int or false
     */
    public static function update($arrParams) {

        $result = false;
        $data = new Bot();
        $data->add_filter('id', '=', $arrParams['id']);
        $data->load();

        if (isset($arrParams['name']) && trim($arrParams['name']) != '') {
            $data->set_data('name', $arrParams['name']);
        }
        if ((int) $arrParams['strength'] < 0) {
            $arrParams['strength'] = 0;
        }
        if ((int) $arrParams['speed'] < 0) {
            $arrParams['speed'] = 0;
        }
        if ((int) $arrParams['agility'] < 0) {
            $arrParams['agility'] = 0;
        }
        
        $data->set_data('idTeam', (int) $arrParams['idTeam']);
        $data->set_data('strength', (int) $arrParams['strength']);
        $data->set_data('speed', (int) $arrParams['speed']);
        $data->set_data('agility', (int) $arrParams['agility']);
        $data->set_data('dateUpdated', date('Y-m-d H:i:s'));

        if ($data->save()) {
            return $data->get_data('id');
        }
        // not found
        return false;
    }

    /**
     * Returns an array with the information of a Bot, by given Id
     * @param int $id
     * @return array or false
     */
    public static function getBotById($id) {
        $data = new Bot();
        $data->add_filter('id', '=', (int) $id);

        if ($data->load()) {
            $arrData['id'] = $data->get_data('id');
            $arrData['name'] = $data->get_data('name');
            $arrData['speed'] = $data->get_data('speed');
            $arrData['strength'] = $data->get_data('strength');
            $arrData['agility'] = $data->get_data('agility');
            $arrData['dateCreated'] = $data->get_data('dateCreated');
            $arrData['dateUpdated'] = $data->get_data('dateUpdated');
            $arrData['idTeam'] = $data->get_data('idTeam');

            return $arrData;
        } else {
            return false;
        }
    }

    /**
     * Returns the Id of a Bot, by given Name (if exists)
     * @param string $name
     * @return int or false
     */
    public static function getBotIdByName($name) {
        $data = new Bot();
        $data->add_filter('name', 'LIKE', $name);

        if ($data->load()) {
            $id = $data->get_data('id');

            return $id;
        } else {
            return false;
        }
    }

    /**
     * Generates a random name for creating a bot
     * @return string
     */
    public static function generateBotName() {
        $name = self::generateRandomString() . self::generateRandomNumber();
        while (self::checkBotNameExists($name) === true) {
            self::generateBotName($name);
        }
        return $name;
    }

    /**
     * This function checks if a given string is an existing bot name in database
     * @param string $name
     * @return boolean
     */
    protected static function checkBotNameExists($name) {
        $bot = new Bot();
        $bot->add_filter('name', 'LIKE', $name);
        if ($bot->load()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Calculates the next sequence autoincrement for the bots name
     * checking the last created element in database
     * @return string
     */
    public static function calculateNextSequence($sequence = '') {

        if ($sequence == '') {
            $str = self::getLastSequence();
        } else {
            $str = $sequence;
        }

        $letters = substr($str, 0, 3);
        $numbers = substr($str, 3, 4);
        //echo $letters . ' ' . $numbers . ' - ';

        if ((int) $numbers === 9999) {
            $numbers = '0000';
            $letters++;

            if (strlen($numbers) === 1) {
                $numbers = '000' . $numbers;
            }
            if (strlen($numbers) === 2) {
                $numbers = '00' . $numbers;
            }
            if (strlen($numbers) === 3) {
                $numbers = '0' . $numbers;
            }


            $newElement = $letters . $numbers;
        } else {
            $numbers ++;

            if (strlen($numbers) === 1) {
                $numbers = '000' . $numbers;
            }
            if (strlen($numbers) === 2) {
                $numbers = '00' . $numbers;
            }
            if (strlen($numbers) === 3) {
                $numbers = '0' . $numbers;
            }

            $newElement = $letters . $numbers;
        }

        return $newElement;
    }

    /**
     * Finds the last existent sequence for a bot in database
     * @return string
     */
    protected static function getLastSequence() {
        $botsCollection = new BotCollection();
        $botsCollection->add_sort('sequence', 'DESC');
        $botsCollection->load();

        $count = $botsCollection->get_count();
        if ($count != 0) {
            $data = &$botsCollection->items[0];
            return $data->get_data("sequence");
        } else {
            return 'AAA0000';
        }
    }

    /**
     * Validates if a given array of bots are already created in database
     * this is called before updating an entire roster
     * @param array $arrBots
     * @return array or false
     */
    public static function validateBotsExist($arrBots) {

        $response['status'] = true;
        $response['message'] = 'OK';

        for ($i = 0; $i < count($arrBots); $i++) {
            $exists = self::getBotById($arrBots[$i]['id']);
            if ($exists === false) {
                $response['status'] = false;
                $response['message'] = 'Bot not found in database. Missing or not valid id. Check bot number ' . ($i + 1) . ' in array .';
                return $response;
            }
        }

        return $response;
    }

    /**
     * @TO-DO move to helper class
     * Function generates random string for bot name
     * @param int $length
     * @return string
     */
    protected static function generateRandomString($length = 3) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @TO-DO move to helper class
     * Function generates random number for bot name
     * @param int $length
     * @return string
     */
    protected static function generateRandomNumber($length = 3) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

class BotCollection extends BusinessObjectCollection {

    function BotCollection() {
        parent::BusinessObjectCollection();
    }

    function create_singular($row) {
        $obj = new Bot();
        $obj->load_from_list($row);
        return $obj;
    }

    /**
     * Removes all bots from a given Rister, by roster Id
     * @param int $idTeam
     * @return boolean
     */
    public static function clearBotsForRoster($idTeam) {

        $dataCollection = new BotCollection();
        $dataCollection->add_filter('idTeam', '=', (int) $idTeam);
        $dataCollection->load();

        $count = $dataCollection->get_count();
        if ($count != 0) {
            for ($i = 0; $i < $count; $i++) {
                $data = &$dataCollection->items[$i];
                $idBot = $data->get_data("id");
                $data->mark_deleted();
                $data->save();
            }
        } else {
            // EMPTY LIST
            $result = false;
        }
        return $result;
    }

    /**
     * Autogenerate roster´s bot by configuration values (quantity)
     * @param int $quantity
     * @return array
     */
    public static function autogenerateBots($quantity) {

        $response['status'] = true;
        $response['message'] = 'OK';

        $settingsArr = Settings::getSettings();
        if (is_array($settingsArr)) {
            $maxTeamPoints = $settingsArr['maxTeamPoints'];

            if ($quantity % 2 == 0) {
                //EVEN
                $bots = self::autogenerateBotsForEvenRoster($quantity, $maxTeamPoints);
            } else {
                //ODD
                $bots = self::autogenerateBotsForOddRoster($quantity, $maxTeamPoints);
            }

            // ASIGN POINTS TO SPPED; STRENGTH AND AGILITY RANDOMLY
            $attrBotsArr = self::generateBotsAttributes($bots);
            $response['data'] = $attrBotsArr;
        } else {
            $response['status'] = false;
            $response['message'] = 'Could not found settings table';
            return $response;
        }
        return $response;
    }

    protected static function autogenerateBotsForEvenRoster($quantity, $maxTeamPoints) {
        self::autogenerateBotsForOddRoster($quantity, $maxTeamPoints);
    }

    /**
     * Autoassigns points to a given bots array, using the maxTeamPoints 
     * variable in database, this is used for automatically creating an initial roster
     * @param int $quantity
     * @param int $maxTeamPoints
     * @return array
     */
    protected static function autogenerateBotsForOddRoster($quantity, $maxTeamPoints) {

        $halfList = $quantity / 2;
        $halfList = round($halfList);

        $averagePoints = $maxTeamPoints / $quantity;
        $averagePoints = round($averagePoints);
        $pointsToGive = $averagePoints;
        $totalPointsGiven = 0;

        // assigning total attribute score to left side bots (descending)
        for ($i = 0; $i <= $halfList; $i++) {
            $bots[$i] = $pointsToGive;
            $totalPointsGiven = $totalPointsGiven + $pointsToGive;
            $pointsToGive--;
        }

        $pointsToGive = $averagePoints + 1;
        // assigning total attribute score to right side bots (ascending)
        for ($i = ($halfList + 1 ); $i < $quantity; $i++) {
            $bots[$i] = $pointsToGive;
            $totalPointsGiven = $totalPointsGiven + $pointsToGive;
            $pointsToGive++;
        }

        // adding the remaining points to the high score bot (this will create 
        // a "superbot" but it is OK, every team has a Messi). Assuming that we
        // dont want to have remaining points when creating the bots, instead we 
        // want to use ALL AVAILABLE POINTS 

        $remainingPoints = $maxTeamPoints - $totalPointsGiven;
        $bots[$quantity - 1] = $bots[$quantity - 1] + $remainingPoints;

        return $bots;
    }

    /**
     * Generates the new bots attributed randomly assigning each bot´s points 
     * to Speed, Strength and Agility elements
     * @param array $bots
     * @return array
     */
    protected static function generateBotsAttributes($bots) {

        for ($i = 0; $i < count($bots); $i++) {
            $points = $bots[$i];
            $attrBots[$i] = array();
            $attrBots[$i]['strength'] = 0;
            $attrBots[$i]['speed'] = 0;
            $attrBots[$i]['agility'] = 0;

            for ($j = 0; $j < $points; $j++) {
                $value = rand(1, 3);
                if ($value == 1) {
                    $attrBots[$i]['strength'] ++;
                }
                if ($value == 2) {
                    $attrBots[$i]['speed'] ++;
                }

                if ($value == 3) {
                    $attrBots[$i]['agility'] ++;
                }
            }
        }

        return $attrBots;
    }
}