<?php

namespace Shary\EnderpearlCD;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\EnderPearl;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {

    private $pearlcd;
    private $config;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
    }


    public function onEnderPearl(PlayerInteractEvent $event)
    {
        $item = $event->getItem();
        if($item instanceof EnderPearl) {
            $cooldown = $this->config->get("cooldown");
            $player = $event->getPlayer();
            if (isset($this->pearlcd[$player->getName()]) and time() - $this->pearlcd[$player->getName()] < $cooldown) {
                $event->setCancelled(true);
                $time = time() - $this->pearlcd[$player->getName()];
                $message = $this->config->get("message");
                $message = str_replace("{cooldown}", ($cooldown - $time), $message);
                $player->sendMessage($message);
            } else {
                $this->pearlcd[$player->getName()] = time();
            }
        }
    }

}
