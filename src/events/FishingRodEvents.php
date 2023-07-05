<?php
/*
 *   _____ _     _     _             ____           _
 *  |  ___(_)___| |__ (_)_ __   __ _|  _ \ ___   __| |
 *  | |_  | / __| '_ \| | '_ \ / _` | |_) / _ \ / _` |
 *  |  _| | \__ \ | | | | | | | (_| |  _ < (_) | (_| |
 *  |_|   |_|___/_| |_|_|_| |_|\__, |_| \_\___/ \__,_|
 *                             |___/
 */

namespace MXJosueDev\fishingrod\events;

use MXJosueDev\fishingrod\entity\FishingHook;
use MXJosueDev\fishingrod\FishingRodManager;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\entity\Location;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Durable;
use pocketmine\item\ItemTypeIds;

class FishingRodEvents implements Listener {

	/**
	 * @noinspection PhpUnused
	 */
	public function onPlayerItemUse(PlayerItemUseEvent $event): void
	{
		$item = $event->getItem();
		$player = $event->getPlayer();

		if($item->getTypeId() !== ItemTypeIds::FISHING_ROD || !($item instanceof Durable)) {
			return;
		}

		// Check the cool down to prevent spam abuse
		if($player->hasItemCooldown($item)) {
			$event->cancel();

			return;
		}
		$player->resetItemCooldown($item, FishingRodManager::getItemCooldownTicks());

		// Can the item be spent?
		if(!FishingRodManager::getItemSpend()) {
			$item->setUnbreakable();
		}

		if(!FishingRodManager::getInstance()->isFishing($player)) {
			$location = $player->getLocation();

			$hook = new FishingHook(Location::fromObject($player->getEyePos(), $player->getWorld(), $location->yaw, $location->pitch), $player);
			$hook->spawnToAll();

			$item->applyDamage(1);
		} else {
			if(!FishingRodManager::getInstance()->getFishingHook($player)->isFlaggedForDespawn()) {
				FishingRodManager::getInstance()->getFishingHook($player)->flagForDespawn();
			}
		}

		$player->broadcastAnimation(new ArmSwingAnimation($player));
	}
}