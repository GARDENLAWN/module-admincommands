<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Console\Command;

use Magento\Framework\Backup\Factory;
use Magento\Backup\Helper\Data;
use Magento\Framework\App\MaintenanceMode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Symfony\Component\Console\Input\InputOption;

class BackupDbCommand extends Command
{
    protected Factory $backupFactory;
    protected Data $helper;
    protected MaintenanceMode $maintenanceMode;
    protected State $appState;

    public function __construct(
        Factory $backupFactory,
        Data $helper,
        MaintenanceMode $maintenanceMode,
        State $appState
    ) {
        $this->backupFactory = $backupFactory;
        $this->helper = $helper;
        $this->maintenanceMode = $maintenanceMode;
        $this->appState = $appState;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('gardenlawn:backup:db')
            ->setDescription('Create Database Backup')
            ->addOption(
                'db',
                null,
                InputOption::VALUE_NONE,
                'Database Backup'
            )
            ->addOption(
                'media',
                null,
                InputOption::VALUE_NONE,
                'Media Backup'
            )
            ->addOption(
                'code',
                null,
                InputOption::VALUE_NONE,
                'Code Backup'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        } catch (\Exception $e) {
            // Area code already set
        }

        $output->writeln('Starting Database Backup...');

        try {
            $type = Factory::TYPE_DB;

            // If we wanted to support other types, we could check input options here
            // if ($input->getOption('media')) { $type = Factory::TYPE_MEDIA; }

            $backupManager = $this->backupFactory->create($type)
                ->setBackupExtension($this->helper->getExtensionByType($type))
                ->setTime(time())
                ->setBackupsDir($this->helper->getBackupsDir());

            $backupManager->create();

            $output->writeln('Backup created successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
