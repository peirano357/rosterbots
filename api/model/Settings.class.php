<?php

/**
 * @author José María Peirano <peirano357@gmail.com>
 */
$db = new mysql_db($db_host, $db_user, $db_pwd, $db_name, false);
add_database($db, $db_name);

class Settings extends BusinessObject {

    function Settings() {
        $this->table_name = "settings";
        $this->field_metadata = array(
            "id" => array("int", true, false, false, false, false),
            "maxBotPoints" => array("int", false, true, false, false, true),
            "maxTeamPoints" => array("int", false, true, false, false, true),
            "dateCreated" => array("datetime", false, true, false, false, true),
            "dateUpdated" => array("datetime", false, true, false, false, true)
        );
        parent::BusinessObject();
    }

    function fill_ids() {
        global $data_objects;
        $this->data["id"] = $data_objects[$this->db_key]->sql_nextid();
    }

    public static function getSettings() {
        $data = new Settings();
        $data->add_filter('id', '=', 1);

        if ($data->load()) {
            $arrData['id'] = $data->get_data('id');
            $arrData['maxBotPoints'] = $data->get_data('maxBotPoints');
            $arrData['maxTeamPoints'] = $data->get_data('maxTeamPoints');
            $arrData['dateCreated'] = $data->get_data('dateCreated');
            $arrData['dateUpdated'] = $data->get_data('dateUpdated');

            return $arrData;
        } else {
            return false;
        }
    }

}
