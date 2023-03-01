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
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\item\Durable;
use pocketmine\item\ItemUseResult;
use pocketmine\entity\animation\ArmSwingAnimation;

class FishingRod extends Durable {

	public function getMaxStackSize(): int
	{
		return 1;
	}

	public function getCooldownTicks(): int
	{
		return FishingRodManager::getItemTicks();
	}

	public function getMaxDurability(): int
	{
		return FishingRodManager::getMaxDurability();
	}

	public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult
	{
		// Check the cooldown to prevent spam abuse
		if($player->hasItemCooldown($this)) return ItemUseResult::FAIL();
		$player->resetItemCooldown($this);

		// Can the item be spent?
		if(!FishingRodManager::getItemSpend()) $this->setUnbreakable();

		if(!FishingRodManager::isFishing($player)) {
			$hook = new FishingHook($player->getLocation(), $player);
			$hook->spawnToAll();
			// FishingRodManager::setFishing($player, $hook);
			$player->sendMessage("Hook spawned"); // Debug

			$this->applyDamage(1);
		} else {
			FishingRodManager::getFishingHook($player)->flagForDespawn();
			// FishingRodManager::unsetFishing($player);

			$player->sendMessage("Hook despawned"); // Debug
		}
		$player->sendMessage("END OF EVENT"); // Debug

		$player->broadcastAnimation(new ArmSwingAnimation($player));

		return ItemUseResult::SUCCESS();
	}

	public function getThrowForce(): float
	{
		return 0.9;
	}
}