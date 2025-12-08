<?php
declare(strict_types=1);

namespace GardenLawn\AdminCommands\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\UrlInterface;

abstract class AbstractCommandButton extends Field
{
    protected string $_template = 'GardenLawn_AdminCommands::system/config/command_button.phtml';

    protected UrlInterface $urlBuilder;

    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Get the AJAX URL for the command.
     *
     * @return string
     */
    public function getAjaxUrl(): string
    {
        return $this->urlBuilder->getUrl('gardenlawn_admincommands/command/execute');
    }

    /**
     * Get the command name to be executed.
     *
     * @return string
     */
    abstract public function getCommandName(): string;

    /**
     * Get additional options for the command (e.g., dry-run, with-delete).
     *
     * @return array
     */
    public function getCommandOptions(): array
    {
        return [];
    }

    /**
     * Render button and options.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $this->setElement($element);
        return $this->_toHtml();
    }
}
