<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class BackupList extends Field
{
    protected $_template = 'GardenLawn_AdminCommands::system/config/backup_list.phtml';
    protected Filesystem $filesystem;

    public function __construct(
        Context $context,
        Filesystem $filesystem,
        array $data = []
    ) {
        $this->filesystem = $filesystem;
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(AbstractElement $element): string
    {
        return $this->_toHtml();
    }

    public function getBackups(): array
    {
        try {
            $directory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
            $path = 'backups';
            if (!$directory->isExist($path)) {
                return [];
            }

            $files = [];
            foreach ($directory->read($path) as $filePath) {
                if ($directory->isFile($filePath)) {
                    $fileName = basename($filePath);
                    $files[] = [
                        'name' => $fileName,
                        'size' => $this->formatSize($directory->stat($filePath)['size']),
                        'time' => date('Y-m-d H:i:s', $directory->stat($filePath)['mtime']),
                        'url' => $this->getUrl('gardenlawn_admincommands/backup/download', ['file' => $fileName])
                    ];
                }
            }

            usort($files, function ($a, $b) {
                return $b['time'] <=> $a['time'];
            });

            return $files;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function formatSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
