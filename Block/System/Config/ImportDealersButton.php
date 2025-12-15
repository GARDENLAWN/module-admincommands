<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class ImportDealersButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:import:dealers';
    }

    public function getCommandOptions(): array
    {
        return [];
    }
}
