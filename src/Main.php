<?php

namespace lubro0\DiscordPM;

use Discord\Discord;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private $discord;

    public function onEnable(): void {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $discordToken = $config->get("DISCORD_BOT_TOKEN");

        $this->discord = new Discord([
            'token' => $discordToken,
        ]);

        $this->discord->on('READY', function ($discord) {
            $discord->getGateway()->on('MESSAGE_CREATE', function ($message) {
                if ($message->content === "!ping") {
                    $message->channel->sendMessage("Pong!");
                }
            });
        });

        $this->discord->run();
    }

    public function onDisable(): void {
    }
}
