<?php

namespace MC;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;
use pocketmine\Player;

class MuteTask extends PluginTask {
  
  public function __construct(Plugin $owner, Player $player){
    parent::__construct($owner);
    $this->player = $player;
  }

  public function onRun($currentTick){
   if($this->player instanceof Player){
     $this->getOwner()->UnMutePlayer($this->player);
     if($this->player instanceof Player){
       $this->getOwner()->Message($this->player, "§6§l»§r §7Tu peux desormais parlé.");
     }
   }
  }
}
