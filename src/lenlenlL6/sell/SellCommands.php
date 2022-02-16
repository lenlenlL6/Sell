<?php

namespace lenlenlL6\sell;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use lenlenlL6\sell\Sell;
use onebone\economyapi\EconomyAPI;

class SellCommands extends Command implements PluginOwned{
  
  private $main;
  
  public function __construct(Sell $main){
  $this->main = $main;
  parent::__construct("sell", "Sell items on hand or the whole inventory", null, ["sell"]);
  $this->setPermission("sell.sell.command");
  }
  
  public function execute(CommandSender $player, String $label, array $args): bool{
    if($player instanceof Player){
    if($this->testPermission($player)){
    if(isset($args[0])){
    switch($args[0]){
    case "hand":
    $hand = $player->getInventory()->getItemInHand();
    if($hand->getId() != 0){
    $count = [];
    foreach($this->main->sell->get("sell") as $sell){
    $id = explode(".", $sell);
    $cost = explode(":", $sell);
    if($hand->getId() === ((int)$id[0]) and $hand->getMeta() === ((int)$id[1])){
    $player->getInventory()->removeItem($hand);
    EconomyAPI::getInstance()->addMoney($player, (((int)$cost[1])*$hand->getCount()));
    $msg = $this->main->lang->get("SUCCESSFUL_SELL");
    $replace = str_replace("{total}", ((int)$cost[1])*$hand->getCount(), $msg);
    $player->sendMessage($replace);
    $count[] = 1;
    }
    }
    if(count($count) === 0){
    $msg = $this->main->lang->get("CAN_NOT_BE_SELL");
    $player->sendMessage($msg);
    }
    }else{
    $player->sendMessage("§cPlease hold something");
    }
    break;
    
    case "all":
    $count = [];
    foreach($player->getInventory()->getContents() as $slot => $item){
    foreach($this->main->sell->get("sell") as $sell){
    $id = explode(".", $sell);
    $cost = explode(":", $sell);
    if($item->getId() === ((int)$id[0]) and $item->getMeta() === ((int)$id[1])){
    $player->getInventory()->removeItem($item);
    EconomyAPI::getInstance()->addMoney($player, ((int)$cost[1])*$item->getCount());
    $count[] = ((int)$cost[1])*$item->getCount();
    }
    }
    }
    if(count($count) != 0){
    $msg = $this->main->lang->get("SUCCESSFUL_SELL");
    $replace = str_replace("{total}", array_sum($count), $msg);
    $player->sendMessage($replace);
    }else{
    $msg = $this->main->lang->get("CAN_NOT_BE_SELL");
    $player->sendMessage($msg);
    }
    break;
    }
    }else{
    $player->sendMessage("§cUsage: /sell [hand | all]");
    }
    }
    }
    return true;
  }
  
  public function getOwningPlugin() : Plugin{
    return $this->main;
  }
}
