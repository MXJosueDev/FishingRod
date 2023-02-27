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

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
	use SingletonTrait;

	const CONFIG_VERSION = '1.0';

	public static string $configPath;

	public function onLoad(): void
	{
		self::setInstance($this);
		self::$configPath = $this->getDataFolder() . 'settings.yml';
	}

	public function onEnable(): void 
	{
		(new FishingRodManager());
	}
}