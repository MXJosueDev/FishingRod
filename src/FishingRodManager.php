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
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;
use pocketmine\utils\SingletonTrait;
use pocketmine\item\VanillaItems;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\EntityDataHelper;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\utils\Config;
use pocketmine\nbt\tag\CompoundTag;

class FishingRodManager { 
	use SingletonTrait;

	private static array $fishing = [];
	private static Config $config;

	public function __construct() {
		self::setInstance($this);
		self::initConfig();

		Server::getInstance()->getPluginManager()->registerEvent('pocketmine\event\player\PlayerQuitEvent', function (PlayerQuitEvent $event) {
			$player = $event->getPlayer();

			if(self::isFishing($player)) {
				self::unsetFishing($player);
			}
		}, 0, Loader::getInstance());

		ItemFactory::getInstance()->register(new FishingRod(new ItemIdentifier(ItemIds::FISHING_ROD, 0), 'Fishing Rod'), true);

		EntityFactory::getInstance()->register(FishingHook::class, function(World $world, CompoundTag $nbt): FishingHook {
			return new FishingHook(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
		}, ['FishingHook'], EntityLegacyIds::FISHING_HOOK);
	}

	/**
	 * Get the fishing rod
	 *
	 * @param  int              $damage Damage with which the item will be returned
	 * @return FishingRod
	 */
	public static function get(int $damage = 0): FishingRod {
		return VanillaItems::FISHING_ROD()->setDamage($damage);
	}
	
	public static function initConfig(): void {
		Loader::getInstance()->saveResource('settings.yml');
		self::$config = new Config(Loader::$configPath, Config::YAML);

		$config = self::getConfig();

		// Rename the file to .old.yml so that it is updated according to the version of the plugin.
		if($config->get('config-version') != Loader::CONFIG_VERSION) {
			$filePath = Loader::$configPath;
			$newFilePath = $filePath . '.old';
			$newFileName = basename($newFilePath);

			rename($filePath, $newFilePath);
			Loader::getInstance()->getLogger()->notice("The configuration was out of date and was renamed to '$newFileName', please check and update the configurations.");
			Loader::getInstance()->getLogger()->alert("Restarting the server...");

			// Shutdown
			Loader::getInstance()->getServer()->shutdown();
		}
	}

	public static function getConfig(): Config {
		return self::$config;
	}

	public static function getItemTicks(): int {
		return self::getConfig()->get('fishingrod-item')['cooldown-ticks'] ?? 8;
	}

	public static function getItemDurability(): int {
		return self::getConfig()->get('fishingrod-item')['max-durability'] ?? 384;
	}
	
	public static function getItemSpend(): bool {
		return self::getConfig()->get('fishingrod-item')['spend-with-use'] ?? false;
	}

	public static function getGravity(): float {
		return self::getConfig()->get('fishingrod-hook')['gravity'] ?? 0.1;
	}

	public static function getMotionMultiplier(): float {
		return self::getConfig()->get('fishingrod-hook')['motion-multiplier'] ?? 0.4; 
	}

	public static function setFishing(Player $player, FishingHook $hook): void {
		if(!self::isFishing($player)) self::$fishing[$player->getPlayerInfo()->getUsername()] = $hook;
	}

	public static function unsetFishing(Player $player) {
		if(self::isFishing($player)) unset(self::$fishing[$player->getPlayerInfo()->getUsername()]);
	}

	/**
	 * Know if a player is fishing
	 *
	 * @param  Player $player
	 * @return bool
	 */
	public static function isFishing(Player $player): bool {
		return isset(self::$fishing[$player->getPlayerInfo()->getUsername()]);
	}

	public static function getFishingHook(Player $player): ?FishingHook {
		return self::$fishing[$player->getPlayerInfo()->getUsername()];
	}
}