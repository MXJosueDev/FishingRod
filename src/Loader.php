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

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {

	use SingletonTrait;

	const CONFIG_VERSION = '1.2';

	public function onLoad(): void
	{
		self::setInstance($this);
		$this->getConfig();
	}

	public function onEnable(): void
	{
		(new FishingRodManager());
	}
}