[![](https://poggit.pmmp.io/shield.state/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.api/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.downloads/FishingRod)](https://poggit.pmmp.io/p/FishingRod)

# FishingRod

FishingRod is an open source plugin and is made for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 5.x.x.

Note: The fishing rod of this plugin is designed for PvP and not for fishing.

## Installation

Install the file named `FishingRod.phar` in the`/home/plugins/` folder, you can download this file
from [Poggit](https://poggit.pmmp.io/p/FishingRod).

## Developers

Please see [CONTRIBUTING](https://github.com/MXJosueDev/FishingRod/blob/main/CONTRIBUTING.md).

### API

#### Get the fishing rod

Due to some changes from PM 4 to 5, items obtained using `pocketmine\item\VanillaItems` do not allow modifying the `"max-durability"` property, to keep the value set in the configuration it is recommended to use the Plugin API method to get the item.

##### VanillaItems
```php
<?php

use pocketmine\item\VanillaItems;

$fishingRod = VanillaItems::FISHING_ROD();
```

##### Plugin API
```php
<?php

use MXJosueDev\fishingrod\FishingRodManager; /* API Class */

$fishingRod = FishingRodManager::getFishingRod(); /* The Item with custom durability. */
```

#### Is a player fishing
<sup>Why you should know it? LOL</sup>

```php
<?php

use MXJosueDev\fishingrod\FishingRodManager; /* Class where the API methods are. */
use pocketmine\Server;

$player = Server::getInstance()->getPlayerExact("iMXJosue"); /* Object with instance of 'pocketmine/player/Player'. */

$isFishing = FishingRodManager::getInstance()->isFishing($player); /* Returns true/false */
```

## Attributions

Plugin Icon: <a href="https://www.flaticon.com/free-icons/fishing-rod" title="fishing rod icons">Fishing rod icons
created by bqlqn - Flaticon</a>

## License

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
