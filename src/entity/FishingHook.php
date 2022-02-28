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

namespace fishingrod\entity;

use fishingrod\FishingRodManager;
use pocketmine\player\Player;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Projectile;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class FishingHook extends Projectile {
    public static function getNetworkTypeId(): string { return EntityIds::FISHING_HOOK; }

    const MOTION_MULTIPLIER = 0.4;

    public function __construct(Location $location, ?Entity $shootingEntity, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $shootingEntity, $nbt);

        if($shootingEntity instanceof Player) {
            $this->setMotion($shootingEntity->getDirectionVector()->multiply(self::MOTION_MULTIPLIER));
            FishingRodManager::setFishing($shootingEntity, $this);

            // Note: This is recycled from a plugin that is not mine. Rights to whom it corresponds.
            $this->handleHookCasting($this->getMotion()->x, $this->getMotion()->y, $this->getMotion()->z, 1.5, 1.0);
        }
    }

    public function flagForDespawn(): void
    {
        parent::flagForDespawn();

        FishingRodManager::unsetFishing($this->getOwningEntity());
    }

    protected function getInitialSizeInfo(): EntitySizeInfo { return new EntitySizeInfo(0.25, 0.25); }

    // Note: This is recycled from a plugin that is not mine. Rights to whom it corresponds.
    private function handleHookCasting(float $x, float $y, float $z, float $f1, float $f2): void
    {
        // Calculations
		$rand = new Random();
		$f = sqrt($x * $x + $y * $y + $z * $z);
		$x = $x / (float) $f;
		$y = $y / (float) $f;
		$z = $z / (float) $f;
		$x = $x + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$y = $y + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$z = $z + $rand->nextSignedFloat() * 0.007499999832361937 * (float) $f2;
		$x = $x * (float) $f1;
		$y = $y * (float) $f1;
		$z = $z * (float) $f1;

		$this->motion->x += $x;
		$this->motion->y += $y;
		$this->motion->z += $z;
	}
}