<?php

namespace lubro0\DiscordPM;

use Discord\Discord;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase {

    private $discord;

    public function onEnable(): void {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $discordToken = $config->get("DISCORD_BOT_TOKEN");

        $this->installDiscordPHP();

        $this->discord = new Discord([
            'token' => $discordToken,
        ]);

        $this->discord->on('READY', function ($discord) {
            $discord->getGateway()->on('MESSAGE_CREATE', function ($message) {
                if ($message->content === "!ping") {
                    $message->channel->sendMessage("Pong! 🏓");
                }
            });
        });

        $this->discord->run();
    }

    public function onDisable(): void {
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "discordpm") {
            $this->executeDiscordCommand();
            return true;
        }
        return false;
    }

    public function executeDiscordCommand(): void {
        $channel = $this->discord->getChannel('DISCORD_CHANNEL_ID');
        $channel->sendMessage("Eine Nachricht von Minecraft!");
    }

    public function runComposerCommand(string $command): void {
        $output = shell_exec("composer $command");
        if ($output) {
            $this->getLogger()->info("Composer Output: " . $output);
        } else {
            $this->getLogger()->warning("Composer-Befehl fehlgeschlagen.");
        }
    }

    public function installDiscordPHP(): void {
        $this->runComposerCommand('require team-reflex/discord-php');
    }
}
