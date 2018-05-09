<?php

/**
 * MageCheck
 * CategoriesMappingTool Sync Extension
 *
 * @author Chiriac Victor && Cristian Gribincea
 * @since 03.2018
 * @category   MageCheck
 * @package    MageCheck_CategoriesMappingTool
 * @copyright  Copyright (c) 2017 Mage Check (http://www.magecheck.com/)
 */

namespace MageCheck\CategoriesMappingTool\Controller\Adminhtml\Product;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /** Constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }
    
    /**
     * Check admin permissions for this controller
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageCheck_CategoriesMappingTool::mapping');
    }

    /** View Form Action
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute() {
        return  $resultPage = $this->_resultPageFactory->create();
    }

}
