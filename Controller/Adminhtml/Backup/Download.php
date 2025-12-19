<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Controller\Adminhtml\Backup;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Filesystem;

class Download extends Action
{
    protected FileFactory $fileFactory;
    protected Filesystem $filesystem;
    protected RawFactory $resultRawFactory;

    public function __construct(
        Context $context,
        FileFactory $fileFactory,
        Filesystem $filesystem,
        RawFactory $resultRawFactory
    ) {
        $this->fileFactory = $fileFactory;
        $this->filesystem = $filesystem;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $fileName = $this->getRequest()->getParam('file');
        // Security check: prevent directory traversal
        if (!$fileName || preg_match('/\.\./', $fileName) || strpos($fileName, '/') !== false) {
             $this->messageManager->addErrorMessage(__('Invalid file name.'));
             return $this->_redirect('adminhtml/system_config/edit', ['section' => 'gardenlawn_commands']);
        }

        $directory = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $filePath = 'backups/' . $fileName;

        if (!$directory->isFile($filePath)) {
            $this->messageManager->addErrorMessage(__('File not found.'));
            return $this->_redirect('adminhtml/system_config/edit', ['section' => 'gardenlawn_commands']);
        }

        // Return file
        return $this->fileFactory->create(
            $fileName,
            [
                'type' => 'filename',
                'value' => $filePath,
                'rm' => false,
            ],
            DirectoryList::VAR_DIR
        );
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('GardenLawn_AdminCommands::config_gardenlawn_commands');
    }
}
