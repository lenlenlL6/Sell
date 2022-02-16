<?php

namespace lenlenlL6\sell;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use lenlenlL6\sell\SellCommands;

class Sell extends PluginBase{
  
  public $sell;
  public $lang;
  
  public function onEnable() : void{
  $this->getServer()->getCommandMap()->register("Sell", new SellCommands($this));
  $this->saveResource("sell.yml");
  $this->saveResource("lang.yml");
  $this->sell = new Config($this->getDataFolder() . "sell.yml", Config::YAML);
  $this->lang = new Config($this->getDataFolder() . "lang.yml", Config::YAML);
  }
}
