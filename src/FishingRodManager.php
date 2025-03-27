<?php
/*
 *   _____ _     _     _             ____           _
 *  |  ___(_)___| |__ (_)_ __   __ _|  _ \ ___   __| |
 *  | |_  | / __| '_ \| | '_ \ / _` | |_) / _ \ / _` |
 *  |  _| | \__ \ | | | | | | | (_| |  _ < (_) | (_| |
 *  |_|   |_|___/_| |_|_|_| |_|\__, |_| \_\___/ \__,_|
 *                             |___/
 */

namespace MXJosueDev\fishingrod;

use MXJosueDev\fishingrod\entity\FishingHook;
use MXJosueDev\fishingrod\events\FishingRodEvents;
use MXJosueDev\fishingrod\item\FishingRod;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use ReflectionException;

class FishingRodManager {

	use SingletonTrait;

	private array $fishing = [];

	// Custom item and entity properties
	private int $itemCooldownTicks;
	private int $itemMaxDurability;
	private bool $itemSpend;

	private float $entityGravity;
	private float $entityMotionMultiplier;

	public function __construct()
	{
		self::setInstance($this);
		$this->initConfig();

		try {
			Server::getInstance()->getPluginManager()->registerEvent('pocketmine\event\player\PlayerQuitEvent', function(PlayerQuitEvent $event) {
				$player = $event->getPlayer();

				if($this->isFishing($player)) {
					$this->unsetFishing($player);
				}
			},                                                       0, Loader::getInstance());
		} catch(ReflectionException) {
			Loader::getInstance()->getLogger()->critical("There was an error while registering the players disconnection event (Which is necessary for the plugin to work properly). The plugin will be deactivated, it is recommended to restart the server. If the issue persists please report it at github.com/MXJosueDev/FishingRod/issues.");
			Loader::getInstance()->getServer()->getPluginManager()->disablePlugin(Loader::getInstance());
		}

		Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new FishingRodEvents(), Loader::getInstance());

		EntityFactory::getInstance()->register(FishingHook::class, function(World $world, CompoundTag $nbt): FishingHook {
			return new FishingHook(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
		},                                     ['FishingHook']);
	}

	public function initConfig(): void
	{
		Loader::getInstance()->saveDefaultConfig();
		$config = Loader::getInstance()->getConfig();

		// Rename the file to .old.yml so that it is updated according to the version of the plugin.
		if($config->get('config-version') != Loader::CONFIG_VERSION) {
			$filePath = Loader::getInstance()->getDataFolder() . "config.yml";
			$newFilePath = $filePath . '.old';
			$newFileName = basename($newFilePath);

			rename($filePath, $newFilePath);
			Loader::getInstance()->getLogger()->notice("The configuration was out of date and was renamed to '$newFileName', please check and update the configurations.");
			Loader::getInstance()->getLogger()->alert("Restarting the server...");

			// Shutdown
			Loader::getInstance()->getServer()->shutdown();

			return;
		}

		// Set item and entity properties
		$this->itemCooldownTicks = $config->get('fishingrod-item')['cooldown-ticks'] ?? 8;
		$this->itemMaxDurability = $config->get('fishingrod-item')['max-durability'] ?? 384;
		$this->itemSpend = $config->get('fishingrod-item')['spend-with-use'] ?? true;

		$this->entityGravity = $config->get('fishingrod-hook')['gravity'] ?? 0.1;
		$this->entityMotionMultiplier = $config->get('fishingrod-hook')['motion-multiplier'] ?? 0.4;
	}

	/**
	 * Know if a player is fishing
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function isFishing(Player $player): bool
	{
		return isset($this->fishing[$player->getPlayerInfo()->getUsername()]);
	}

	/**
	 * @param Player $player
	 *
	 * @return void
	 * @internal
	 */
	public function unsetFishing(Player $player): void
	{
		if($this->isFishing($player)) {
			unset($this->fishing[$player->getPlayerInfo()->getUsername()]);
		}
	}

	/**
	 * Get the FishingRod item. (This only works if you need the custom durability set in the config, any other case use {@link VanillaItems})
	 *
	 * @return FishingRod
	 * @noinspection PhpUnused
	 */
	public static function getFishingRod(): FishingRod
	{
		return new FishingRod(new ItemIdentifier(ItemTypeIds::FISHING_ROD));
	}

	public static function getItemCooldownTicks(): int
	{
		return self::getInstance()->itemCooldownTicks;
	}

	public static function getItemMaxDurability(): int
	{
		return self::getInstance()->itemMaxDurability;
	}

	public static function getItemSpend(): bool
	{
		return self::getInstance()->itemSpend;
	}

	public static function getEntityGravity(): float
	{
		return self::getInstance()->entityGravity;
	}

	public static function getEntityMotionMultiplier(): float
	{
		return self::getInstance()->entityMotionMultiplier;
	}

	/**
	 * @param Player      $player
	 * @param FishingHook $hook
	 *
	 * @return void
	 * @internal
	 */
	public function setFishing(Player $player, FishingHook $hook): void
	{
		if(!$this->isFishing($player)) {
			$this->fishing[$player->getPlayerInfo()->getUsername()] = $hook;
		}
	}

	/**
	 * @param Player $player
	 *
	 * @return FishingHook|null
	 * @internal
	 */
	public function getFishingHook(Player $player): ?FishingHook
	{
		return $this->fishing[$player->getPlayerInfo()->getUsername()];
	}
}