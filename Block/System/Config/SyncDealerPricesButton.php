<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class SyncDealerPricesButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:dealer:sync-prices';
    }

    public function getCommandOptions(): array
    {
        return [];
    }
}
