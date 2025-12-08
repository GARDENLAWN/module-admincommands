<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class PopulateAssetLinksButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:mediagallery:populate-all';
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
            [
                'name' => 'with-prune',
                'label' => 'Enable pruning of orphaned galleries',
                'type' => 'checkbox',
                'checked' => false,
            ],
        ];
    }
}
