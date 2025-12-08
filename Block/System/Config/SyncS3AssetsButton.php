<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class SyncS3AssetsButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:mediagallery:sync-s3';
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
                'name' => 'with-delete',
                'label' => 'Enable deletion of database assets',
                'type' => 'checkbox',
                'checked' => false,
            ],
            [
                'name' => 'force-update',
                'label' => 'Force update of existing assets',
                'type' => 'checkbox',
                'checked' => false,
            ],
        ];
    }
}
