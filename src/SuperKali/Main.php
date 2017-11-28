<?php

namespace SuperKali;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{

    public $prefix;
    public $config;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDisable()
    {
        $this->getLogger()->info($this->prefix);
    }
    public function tempbanui($player)
    {
        $manager = $this->getServer()->getPluginManager();
        $api = $manager->getPlugin('FormAPI');
        $form = $api->createCustomForm(function (Player $event, array $data) {
            $player = $event->getPlayer();
            $result = $data[0];
            if ($result != null) {
                $this->targetname = $result;
                $this->reason = $data[1];
                $this->time = $data[2];
                $this->getServer()->dispatchCommand(new ConsoleCommandSender, "tempban " . $this->targetName . '' . $this->time . '' . $this->reason);
            }
        });
        foreach ($this->getServer()->getOnlinePlayers() as $value)
        {
            $nametag = $value->getNameTag();
        }
        $form->setTitle(TextFormat::BOLD . "TempbanUI");
        $form->addDropdown('Player Name', [$nametag]);
        $form->addInput("REASON");
        $form->sendToPlayer($player);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = $sender->getPlayer();
        switch ($command->getName()) {
            case 'tempbanui':
                $this->tempbanui($player);
                break;
        }
        return true;
    }
}