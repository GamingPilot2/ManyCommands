<?php

namespace MC;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\level\Level;

class Main extends PluginBase implements Listener{
    public function onEnable(){
      $this->getLogger()->info("ManyCommands is now enabled.");
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->mute = [];
    }

    public function onPlayerJoin(PlayerJoinEvent $event){
      $player = $event->getPlayer();
      if(!isset($this->mute[$player->getName()])){
        $this->mute[$player->getName()] = 0;
      }
    }

    public function Message($player, $msg){
     $player->sendMessage($msg);
   }

    public function MutePlayer($player){
      $this->mute[$player->getName()] = $this->mute[$player->getName()] + 1;
    }
    
    public function UnMutePlayer($player){
      $this->mute[$player->getName()] = 0;
    }

    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
      switch($command->getName()){

       case "heal":
         if($sender->hasPermission("lc.heal")){
           if(isset($args[0])){
             $player = $sender->getServer()->getPlayer($args[0]);
             $sender->sendMessage("§6§l»§r §7La vie du joueur §f".$player->getName()."§7 a été restaurée...");
             $player->setHealth(20);
             $player->sendMessage("§6§l»§r §7Ta vie a été restaurée.");
           }else{
             $sender->sendMessage("§cUtilise : /heal [player]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "feed":
         if($sender->hasPermission("lc.feed")){
           if(isset($args[0])){
             $player = $sender->getServer()->getPlayer($args[0]);
             $sender->sendMessage("§6§l»§r §7La barre de faim du joueur §f".$player->getName()."§7 a été restaurée...");
             $player->setFood(20);
             $player->sendMessage("§6§l»§r §7Ta barre de faim a été restaurée.");
           }else{
             $sender->sendMessage("§cUtilise : /feed [player]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "clear":
         if($sender->hasPermission("lc.clear")){
           if(isset($args[0])){
             $player = $sender->getServer()->getPlayer($args[0]);
             $sender->sendMessage("§6§l»§r §7L'inventaire du joueur §f".$player->getName()."§7 a été réinitialisé...");
             $player->getInventory()->clearAll();
             $player->sendMessage("§6§l»§r §7Ton inventaire a été réinitialisé.");
           }else{
             $sender->sendMessage("§cUtilise : /clear [player]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "spawn":
         if($sender->hasPermission("lc.spawn")){
           $spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
           $this->getServer()->loadLevel($spawn);
           $sender->teleport($spawn);
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "broadcast":
         if($sender->hasPermission("lc.broadcast")){
           if(count($args) >= 0){
             foreach($this->getServer()->getOnlinePlayers() as $p){
               $p->sendMessage("§6§lManyCmds §8»§r §7".implode(" ", $args));
             }
           }else{
             $sender->sendMessage("§cUtilise : /broadcast [message]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "tpall":
         if($sender->hasPermission("lc.tpall")){
           foreach($this->getServer()->getOnlinePlayers() as $p){
             $p->teleport(new Position($sender->x, $sender->y, $sender->z, $sender->getLevel()));
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "giveall":
         if($sender->hasPermission("lc.giveall")){
           if(isset($args[0]) && isset($args[1])){
             foreach($this->getServer()->getOnlinePlayers() as $p){
               $id = $args[0];
               $nbr = $args[1];
               $p->getInventory()->addItem(Item::get($id, 0, $nbr));
               $p->sendMessage("§6§l»§r §7Tu as recu un cadeau...");
               $p->sendMessage("§6§l»§r §7Regarde dans ton inventaire...");
             }
           }else{
             $sender->sendMessage("§cUtilise : /giveall [id] [nombre]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "kickall":
         if($sender->hasPermission("lc.kickall")){
           foreach($this->getServer()->getOnlinePlayers() as $p){
             $p->kick();
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "world":
         if($sender->hasPermission("lc.world")){
           if(Server::getInstance()->loadLevel($args[0]) != false){
             $sender->teleport($sender->getServer()->getLevelByName($args[0])->getSafeSpawn());
           }else{
             $sender->sendMessage("§6§l»§r §7Le monde §f".$args[0]."§7 est introuvable.");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "coords":
         if($sender->hasPermission("lc.coords")){
           $x = $sender->getX();
           $y = $sender->getY();
           $z = $sender->getZ();
           $sender->sendMessage("§8--- §l§6Coordonnées §r§8--");
           $sender->sendMessage("§8§l»§r §7X : ".$x);
           $sender->sendMessage("§8§l»§r §7Y : ".$y);
           $sender->sendMessage("§8§l»§r §7Z : ".$z);
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "fly":
         if($sender->hasPermission("lc.fly")){
           if(isset($args[0])){
             $player = $sender->getServer()->getPlayer($args[0]);
             $player->sendMessage("§6§l»§r §7Tu as été autorisé a voler par §f".$sender->getName()."§7.");
             $player->setAllowFlight(true);
           }else{
             $sender->sendMessage("§cUtilise : /fly [player]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;

       case "mute":
         if($sender->hasPermission("lc.mute")){
           if(isset($args[0]) && isset($args[1])){
             $player = $sender->getServer()->getPlayer($args[0]);
             $time = $args[1];
             $task = new MuteTask($this, $player);
             $this->MutePlayer($player);
             $this->getServer()->broadcastMessage("§6§lManyCmds §8»§r §7Le joueur §f".$player->getName()."§7 a été mute pendant §f".$time."§7 minute(s).");
             $this->getServer()->getScheduler()->scheduleDelayedTask($task, $time * 20 * 60);
             $player->sendMessage("§6§l»§r §7Tu as été mute par §f".$sender->getName()."§7 pendant §f".$time."§7 minute(s).");
           }else{
             $sender->sendMessage("§cUtilise : /mute [player] [temps]");
           }
         }else{
           $sender->sendMessage("§cYou don't have permission to do this.");
         }
         return true;
       }
    }
    
    public function onChat(PlayerChatEvent $event) {
      $player = $event->getPlayer();
      if($this->mute[$player->getName()] > 0){
        $event->setCancelled(true);
        $player->sendMessage("§cTu as été mute...");
      }
    }
}