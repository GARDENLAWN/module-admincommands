<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

class ConvertImagesToWebpButton extends AbstractCommandButton
{
    public function getCommandName(): string
    {
        return 'gardenlawn:gallery:convert-to-webp';
    }

    public function getCommandOptions(): array
    {
        return [
            [
                'name' => 'force',
                'label' => 'Force regeneration',
                'type' => 'checkbox',
                'checked' => false,
            ],
        ];
    }
}
