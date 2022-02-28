# FishingRod

FishingRod is an open source plugin and is made for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 4.x.x.

Note: The fishing rod is designed for PvP and not for fishing.

Currently in development.

## Installation

Install the file named `FishingRod.phar` in the`/home/plugins/` folder, you can download this file from [Poggit](https://poggit.pmmp.io/FishingRod).

## Developers

Please see [CONTRIBUTING](https://github.com/MXJosueDev/FishingRod/blob/main/CONTRIBUTING.md).

### API

- **Get the fishing rod**
- Plugin API method
```php 
<?php

use fishingrod\FishingRodManager; /* Class in which the API methods are. */

$damage = 0; /* Damage with which the item will be returned. */
$count = 1; /* Number of fishing rods you want. */
$tags = null; /* Extra tags. */

/* Note: All parameters are optional and already have a default value. */
$fishingRod = FishingRodManager::get($damage, $count, $tags); /* Final item that you can add to any inventory. */
```
- Pocketmine API method
```php 
<?php

use pocketmine\item\ItemFactory; /* Class in which the API methods are.*/
use pocketmine\item\ItemIds; /* Class where the item ID is located. */

$damage = 0; /* Damage with which the item will be returned. */
$count = 1; /* Number of fishing rods you want. */
$tags = null; /* Extra tags. */

/* Note: All parameters are optional except for the item ID and already have a default value. */
$fishingRod = ItemFactory::getInstance()->get(ItemIds::FISHING_ROD, $damage, $count, $tags); /* Final item that you can add to any inventory. */
```

- Know if a player is fishing
```php
<?php

use fishingrod\FishingRodManager; /* Class in which the API methods are. */
use pocketmine\Server;

$player = Server::getInstance()->getPlayerExact("MXJosuepro033"); /* Player. */

$isFishing = FishingRodManager::isFishing($player); /* Boolean indicating whether or not it is fishing. */
```

## License

[MIT](https://choosealicense.com/licenses/mit/)
