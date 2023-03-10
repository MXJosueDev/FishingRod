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

namespace fishingrod\item;

use fishingrod\FishingRodManager;
use fishingrod\entity\FishingHook;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\item\Durable;
use pocketmine\item\ItemUseResult;
use pocketmine\entity\animation\ArmSwingAnimation;

class FishingRod extends Durable {

	public function getMaxStackSize(): int {
		return 1;
	}

	public function getCooldownTicks(): int {
		return FishingRodManager::getItemTicks();
	}

	public function getMaxDurability(): int {
		return FishingRodManager::getItemDurability();
	}

	public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
		// Check the cool down to prevent spam abuse
		if($player->hasItemCooldown($this)) return ItemUseResult::FAIL();
		$player->resetItemCooldown($this);

		// Can the item be spent?
		if(!FishingRodManager::getItemSpend()) $this->setUnbreakable();

		if(!FishingRodManager::isFishing($player)) {
			$location = $player->getLocation();
			
			$hook = new FishingHook(Location::fromObject($player->getEyePos(), $player->getWorld(), $location->yaw, $location->pitch), $player);
			$hook->spawnToAll();

			$this->applyDamage(1);
		} else {
			if(!FishingRodManager::getFishingHook($player)->isFlaggedForDespawn()) FishingRodManager::getFishingHook($player)->flagForDespawn();
		}

		$player->broadcastAnimation(new ArmSwingAnimation($player));

		return ItemUseResult::SUCCESS();
	}
}