<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Controller\Adminhtml\Command;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\State;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Magento\Framework\Console\CommandListInterface;
use Psr\Log\LoggerInterface;

class Execute extends Action
{
    protected JsonFactory $resultJsonFactory;
    protected CommandListInterface $commandList;
    protected State $appState;
    protected LoggerInterface $logger;

    public function __construct(
        Context              $context,
        JsonFactory          $resultJsonFactory,
        CommandListInterface $commandList,
        State                $appState,
        LoggerInterface      $logger
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->commandList = $commandList;
        $this->appState = $appState;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute(): Json|ResultInterface|ResponseInterface
    {
        $result = $this->resultJsonFactory->create();
        $commandName = $this->getRequest()->getParam('command');
        $options = $this->getRequest()->getParam('options', []);

        if (!$commandName) {
            return $result->setData([
                'status' => 'error',
                'message' => __('Command name is missing.')
            ]);
        }

        try {
            try {
                // Set area code to global to allow console commands to run
                $this->appState->setAreaCode(Area::AREA_GLOBAL);
            } catch (LocalizedException) {
                // Area code is already set
            }

            $command = $this->commandList->get($commandName);
            if (!$command) {
                throw new Exception(sprintf('Command "%s" not found.', $commandName));
            }

            $inputArgs = [];
            foreach ($options as $key => $value) {
                if ($value === true) { // For checkbox options
                    $inputArgs['--' . $key] = true;
                } elseif ($value !== false && $value !== null && $value !== '') { // For text options
                    $inputArgs['--' . $key] = $value;
                }
            }

            $input = new ArrayInput($inputArgs);
            $output = new BufferedOutput();

            $statusCode = $command->run($input, $output);
            $commandOutput = $output->fetch();

            if ($statusCode === 0) {
                $message = __('Command "%1" executed successfully.', $commandName);
                $this->messageManager->addSuccessMessage($message);
                return $result->setData([
                    'status' => 'success',
                    'message' => $message,
                    'output' => $commandOutput
                ]);
            } else {
                $message = __('Command "%1" failed with status code %2.', $commandName, $statusCode);
                $this->messageManager->addErrorMessage($message);
                $this->logger->error(sprintf('Admin Command Error: %s. Output: %s', $message, $commandOutput));
                return $result->setData([
                    'status' => 'error',
                    'message' => $message,
                    'output' => $commandOutput
                ]);
            }
        } catch (Exception $e) {
            $message = __('An error occurred while executing command "%1": %2', $commandName, $e->getMessage());
            $this->messageManager->addErrorMessage($message);
            $this->logger->critical(sprintf('Admin Command Critical Error: %s', $e->getMessage()), ['exception' => $e]);
            return $result->setData([
                'status' => 'error',
                'message' => $message,
                'output' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check permission for current user.
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('GardenLawn_AdminCommands::config_gardenlawn_commands');
    }
}
