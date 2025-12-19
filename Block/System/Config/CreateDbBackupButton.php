<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class CreateDbBackupButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:backup:db';
    }

    public function getCommandOptions(): array
    {
        return [
            [
                'name' => 'db',
                'label' => 'Database Backup',
                'type' => 'checkbox',
                'checked' => true,
                'readonly' => true // Not supported by frontend yet but good for intent
            ]
        ];
    }
}
