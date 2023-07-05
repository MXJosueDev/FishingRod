<?php
/*
 *   _____ _     _     _             ____           _
 *  |  ___(_)___| |__ (_)_ __   __ _|  _ \ ___   __| |
 *  | |_  | / __| '_ \| | '_ \ / _` | |_) / _ \ / _` |
 *  |  _| | \__ \ | | | | | | | (_| |  _ < (_) | (_| |
 *  |_|   |_|___/_| |_|_|_| |_|\__, |_| \_\___/ \__,_|
 *                             |___/
 */

declare(strict_types=1);

namespace MXJosueDev\fishingrod\entity;

use MXJosueDev\fishingrod\FishingRodManager;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\RayTraceResult;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\utils\Random;

class FishingHook extends Projectile {

	public static function getNetworkTypeId(): string
	{
		return EntityIds::FISHING_HOOK;
	}

	// I don't know much about whether this affects the way this worked before, but if I notice anything strange I'll fix it.
	protected function getInitialDragMultiplier(): float
	{
		return 0;
	}

	protected function getInitialGravity(): float
	{
		return 0;
	}

	protected function getInitialSizeInfo(): EntitySizeInfo
	{
		return new EntitySizeInfo(0.25, 0.25);
	}

	public function __construct(Location $location, ?Entity $shootingEntity, ?CompoundTag $nbt = null)
	{
		parent::__construct($location, $shootingEntity, $nbt);
		$this->setGravity(FishingRodManager::getEntityGravity());

		if($shootingEntity instanceof Player) {
			$this->setMotion($shootingEntity->getDirectionVector()->multiply(FishingRodManager::getEntityMotionMultiplier()));
			FishingRodManager::getInstance()->setFishing($shootingEntity, $this);

			// Note: This is recycled from a plugin that is not mine. Rights to whom it corresponds.
			$this->handleHookCasting($this->getMotion()->getX(), $this->getMotion()->getY(), $this->getMotion()->getZ());
		} else {
			$this->flagForDespawn();
		}
	}

	public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void
	{
		$damage = $this->getResultDamage();

		if($this->getOwningEntity() !== null) {
			$event = new EntityDamageByChildEntityEvent($this->getOwningEntity(), $this, $entityHit, EntityDamageEvent::CAUSE_PROJECTILE, $damage);

			if(!$event->isCancelled()) {
				$entityHit->attack($event);
			}
		}

		$this->isCollided = true;
		$this->flagForDespawn();
	}

	protected function entityBaseTick(int $tickDiff = 1): bool
	{
		$hasUpdate = parent::entityBaseTick($tickDiff);
		$player = $this->getOwningEntity();
		$despawn = false;

		// Checks for automatic despawn
		if($player instanceof Player) {
			if(
				$player->getInventory()->getItemInHand()->getTypeId() !== ItemTypeIds::FISHING_ROD ||
				!$player->isAlive() ||
				$player->isClosed() ||
				$player->getLocation()->getWorld()->getFolderName() !== $this->getLocation()->getWorld()->getFolderName()
			) {
				$despawn = true;
			}
		} else {
			$despawn = true;
		}

		if($despawn) {
			$this->flagForDespawn();
			$hasUpdate = true;
		}

		return $hasUpdate;
	}

	public function flagForDespawn(): void
	{
		$owningEntity = $this->getOwningEntity();

		if($owningEntity instanceof Player) {
			FishingRodManager::getInstance()->unsetFishing($owningEntity);
		}

		parent::flagForDespawn();
	}

	private function handleHookCasting(float $x, float $y, float $z): void
	{ // Note: This is recycled from a plugin that is not mine. Rights to whom it corresponds.
		$f2 = 1.0;
		$f1 = 1.5;
		// Calculations
		$rand = new Random();
		$f = sqrt($x * $x + $y * $y + $z * $z);
		$x = $x / $f;
		$y = $y / $f;
		$z = $z / $f;
		$x = $x + $rand->nextSignedFloat() * 0.007499999832361937;
		$y = $y + $rand->nextSignedFloat() * 0.007499999832361937 * $f2;
		$z = $z + $rand->nextSignedFloat() * 0.007499999832361937 * $f2;
		$x = $x * 1.5;
		$y = $y * $f1;
		$z = $z * $f1;

		$this->motion->x += $x;
		$this->motion->y += $y;
		$this->motion->z += $z;
	}
}