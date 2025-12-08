<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class DeduplicateAssetsButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:mediagallery:deduplicate-assets';
    }

    public function getCommandOptions(): array
    {
        return [
            [
                'name' => 'dry-run',
                'label' => 'Dry Run (do not modify database)',
                'type' => 'checkbox',
                'checked' => true, // Default to dry-run
            ],
        ];
    }
}
