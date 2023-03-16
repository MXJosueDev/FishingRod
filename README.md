[![](https://poggit.pmmp.io/shield.state/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.api/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.downloads/FishingRod)](https://poggit.pmmp.io/p/FishingRod)
# FishingRod

FishingRod is an open source plugin and is made for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 4.x.x.

Note: The fishing rod is designed for PvP and not for fishing.

## Installation

Install the file named `FishingRod.phar` in the`/home/plugins/` folder, you can download this file from [Poggit](https://poggit.pmmp.io/p/FishingRod).

## Developers

Please see [CONTRIBUTING](https://github.com/MXJosueDev/FishingRod/blob/main/CONTRIBUTING.md).

### API

#### **Get the fishing rod**
```php
<?php

use pocketmine\item\VanillaItems; /* Class where the API methods are. */

$fishingRod = VanillaItems::FISHING_ROD(); /* Final item that you can add to any inventory. */
```

#### **Know if a player is fishing**
```php
<?php

use MXJosueDev\fishingrod\FishingRodManager; /* Class where the API methods are. */
use pocketmine\Server;

$player = Server::getInstance()->getPlayerExact("iMXJosue"); /* Player example with instance of 'pocketmine/player/Player'. */

$isFishing = FishingRodManager::isFishing($player); /* Returns a boolean value indicating if the player is fishing. */
```

## License

[MIT](https://choosealicense.com/licenses/mit/)
