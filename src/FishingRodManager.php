<?php
declare(strict_types=1);

/**
 *  _____ _     _     _             ____           _ 
 * |  ___(_)___| |__ (_)_ __   __ _|  _ \ ___   __| |
 * | |_  | / __| '_ \| | '_ \ / _` | |_) / _ \ / _` |
 * |  _| | \__ \ | | | | | | | (_| |  _ < (_) | (_| |
 * |_|   |_|___/_| |_|_|_| |_|\__, |_| \_\___/ \__,_|
 *                            |___/                  
 */

namespace fishingrod;

use fishingrod\item\FishingRod;
use fishingrod\entity\FishingHook;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\entity\EntityFactory;
use pocketmine\utils\Config;

class FishingRodManager {
    use SingletonTrait;

    private static array $fishing = [];
    private static Config $config;

    public function __construct() 
    {
        self::setInstance($this);
        self::initConfig();

        ItemFactory::getInstance()->register(new FishingRod(new ItemIdentifier(ItemIds::FISHING_ROD), 'Fishing Rod'), true);

        // TODO:
        EntityFactory::getInstance()->register();
    }

    public static function initConfig(): void 
    {
        Loader::getInstance()->saveResource('settings.yml');
        self::$config = new Config(Loader::$configPath, Config::YAML);

        $config = self::getConfig();

        // Rename the file to .old.yml so that it is updated according to the version of the plugin.
        if($config->get('config-version') != Loader::CONFIG_VERSION) {
            $filePath = Loader::$configPath;
            $newFilePath = $filePath . '.old';
            $newFileName = basename($newFilePath);

            rename($filePath, $newFilePath);
            Loader::getInstance()->getLogger()->notice("The configuration was out of date and was renamed to '{$newFileName}', please check and update the configurations.");
        }
    }

    public static function getConfig(): Config
    {
        return self::$config;
    }

    // TODO:
    public static function getItemTicks(): int 
    {
        return self::getConfig()->get('fishingrod-item')['cooldown-ticks'];
    }

    public static function getItemDurability(): int 
    {
        return self::getConfig()->get('fishingrod-item')['max-durability'];
    }
    
    public static function getItemSpend(): bool 
    {
        return self::getConfig()->get('fishingrod-item')['spend-with-use'];
    }

    public static function setFishing(Player $player, Hook $hook): void
    {
        if(!self::isFishing($player)) {
            self::$fishing[$player->getUniqueId()->toString()] = $hook;
        }
    }

    public static function unsetFishing(Player $player)
    {
        if(self::isFishing($player)) {
            unset(self::$fishing[$player->getUniqueId()->toString()]);
        }
    }

    public static function isFishing(Player $player): bool
    {
        return isset(self::$fishing[$player->getUniqueId()->toString()]);
    }

    public static function getFishingHook(Player $player): ?Hook
    {
        return self::$fishing[$player->getUniqueId()->toString()];
    }
}