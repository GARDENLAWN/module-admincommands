<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class SyncStaticAssetsButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:s3:sync-static';
    }

    public function getCommandOptions(): array
    {
        return [
            [
                'name' => 'theme',
                'label' => 'Theme(s) to synchronize (comma-separated, e.g., Magento/luma,GardenLawn/Hyvatheme)',
                'type' => 'text',
                'value' => 'GardenLawn/Hyvatheme', // Default value
            ],
        ];
    }
}
