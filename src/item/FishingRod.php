<?php
/*
 *   _____ _     _     _             ____           _
 *  |  ___(_)___| |__ (_)_ __   __ _|  _ \ ___   __| |
 *  | |_  | / __| '_ \| | '_ \ / _` | |_) / _ \ / _` |
 *  |  _| | \__ \ | | | | | | | (_| |  _ < (_) | (_| |
 *  |_|   |_|___/_| |_|_|_| |_|\__, |_| \_\___/ \__,_|
 *                             |___/
 */

namespace MXJosueDev\fishingrod\item;

use MXJosueDev\fishingrod\FishingRodManager;
use pocketmine\item\FishingRod as PMFishingRod;

class FishingRod extends PMFishingRod {

	public function getMaxDurability(): int
	{
		return FishingRodManager::getItemMaxDurability();
	}
}