<?php

require "../../settings_test.php";
require '../../lib/mygen_framework.php';
require '../../lib/mygen_mysql.php';
require '../model/Bot.class.php';

class BotTests extends PHPUnit_Framework_TestCase {

    private $botQuantity = 15;
    private $sumRosterPoints = 0;
    private $sumBotPoints = 0;

    public function testValidateAutomaticBotsGeneration() {
        $result = BotCollection::autogenerateBots($this->botQuantity);

        // BOTS MUST BE 15
        $this->assertCount(15, $result['data']);

        // SUM OF ALL TEAM BOTS POINTS DO NOT EXCEED 175
        for ($i = 0; $i < count($result['data']); $i++) {
            $this->sumRosterPoints = $this->sumRosterPoints + $result['data'][$i]['strength'] + $result['data'][$i]['speed'] + $result['data'][$i]['agility'];
        }
        $this->assertLessThanOrEqual(175, $this->sumRosterPoints);

        // Should not have a Bot wich his attributes sum, is greater than 100
        for ($i = 0; $i < count($result['data']); $i++) {
            $this->sumBotPoints = $this->sumBotPoints + $result['data'][$i]['strength'] + $result['data'][$i]['speed'] + $result['data'][$i]['agility'];
            $this->assertLessThanOrEqual(100, $this->sumBotPoints);
            $this->sumBotPoints = 0;
        }
    }

}
