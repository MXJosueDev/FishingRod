[![](https://poggit.pmmp.io/shield.state/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.api/FishingRod)](https://poggit.pmmp.io/p/FishingRod) [![](https://poggit.pmmp.io/shield.downloads/FishingRod)](https://poggit.pmmp.io/p/FishingRod)

# FishingRod

FishingRod is an open source plugin and is made for [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 5.x.x.

Note: The fishing rod is designed for PvP and not for fishing.

## Installation

Install the file named `FishingRod.phar` in the`/home/plugins/` folder, you can download this file
from [Poggit](https://poggit.pmmp.io/p/FishingRod).

## Developers

Please see [CONTRIBUTING](https://github.com/MXJosueDev/FishingRod/blob/main/CONTRIBUTING.md).

### API

#### Get the fishing rod

Due to changes in the PocketMine-MP API from version 4 to version 5, now it is not possible to modify some properties of the items that are obtained through pocketmine\item\VanillaItems, now, to preserve these custom properties it is recommended to use the method of the Plugin API. (The "max-durability" property is the only one that now cannot be customized if it is obtained by VanillaItems)

##### VanillaItems
```php
<?php

use pocketmine\item\VanillaItems; /* Class that contains the methods of the API. */

$fishingRod = VanillaItems::FISHING_ROD(); /* Final item that you can add to any inventory. */
```

##### Plugin API
```php
<?php

use MXJosueDev\fishingrod\FishingRodManager; /* Class that contains the methods of the API. */

$fishingRod = FishingRodManager::getFishingRod(); /* Final item that you can add to any inventory. */
```

#### Know if a player is fishing

```php
<?php

use MXJosueDev\fishingrod\FishingRodManager; /* Class where the API methods are. */
use pocketmine\Server;

$player = Server::getInstance()->getPlayerExact("iMXJosue"); /* Player example with instance of 'pocketmine/player/Player'. */

$isFishing = FishingRodManager::getInstance()->isFishing($player); /* Returns a boolean value indicating if the player is fishing. */
```

## Attributions

Plugin Icon: <a href="https://www.flaticon.com/free-icons/fishing-rod" title="fishing rod icons">Fishing rod icons
created by bqlqn - Flaticon</a>

## License

[MIT](https://choosealicense.com/licenses/mit/)
