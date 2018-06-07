<?php

/**
 * MageCheck
 * Magento 2 Categories Mapping Tool
 *
 * @author Chiriac Victor
 * @since 06.2018
 * @category   MageCheck
 * @package    MageCheck_CategoriesMappingTool
 * @copyright  Copyright (c) 2017 Mage Check (http://www.magecheck.com/)
 */

namespace MageCheck\CategoriesMappingTool\Controller\Adminhtml\Product;

class Filter extends \Magento\Backend\App\Action
{
    /**  EAV Attribute Model
     * @var \Magento\Eav\Model\Attribute $attribute
     */
    protected $_attribute;    

     /** EAV Attribute Factory
     * @var \Magento\Eav\Model\Entity\Attribute\Source\TableFactory
     */
    protected $tableFactory;
    
    /** Constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Eav\Model\Entity\Attribute\Source\TableFactory $tableFactory
     */
    
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Eav\Model\Attribute $attribute,
        \Magento\Eav\Model\Entity\Attribute\Source\TableFactory $tableFactory
    ) 
    {
        parent::__construct($context);
        
        $this->_attribute = $attribute;
        $this->tableFactory = $tableFactory;
    }

    /** Get Filter HTML based on attribute type
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        if(!$this->getRequest()->getParam('isAjax'))
        {
            return false;
        }
        
        $data = $this->getRequest()->getParams();
        if(count($data))
        {
            return $this->generateHtml($this->_attribute->load($this->getRequest()->getParam('attribute')));
            
        }
    }
    
    /** Generate HTML based on attribute
     * @param object $attribute
     * @return string
     */
    public function generateHtml($attribute)
    {
        $output = '<div data-filter="' . $attribute->getId() . '">';
        $output .= '<label for="' . $attribute->getAttributeCode() . '">';
        $output .= '<a href="javascript:void(0);" data-remove-filter>&times;</a>&nbsp;';
        $output .= $attribute->getFrontendLabel();
        $output .= '</label>';
        switch ($attribute->getFrontendInput()) {
            case 'multiselect':
                $options = $this->getAttributeOptions($attribute);
                if (count($options) > 1) {
                    $output .= '<select class="filter admin__control-multiselect" name="' . $attribute->getAttributeCode() . '[]" multiple>';
                    foreach ($options as $option) {
                        if (isset($option['value'])) {
                            $output .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
                        }
                    }
                    $output .= '</select>';
                } else {
                    $output .= '<span class="no-options">' . __('Dropdown does not have any options!') . '</span>';
                }
                break;
            case 'select':
                $options = $this->getAttributeOptions($attribute);
                if (count($options) > 1) {
                    $output .= '<select class="filter admin__control-select" name="' . $attribute->getAttributeCode() . '[select]">';
                    foreach ($options as $option) {
                        if (isset($option['value'])) {
                            $output .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
                        }
                    }
                    $output .= '</select>';
                } else {
                    $output .= '<span class="no-options">' . __('Dropdown does not have any options!') . '</span>';
                }

                break;
            case 'boolean':
                $output .= '<select class="filter admin__control-select" name="' . $attribute->getAttributeCode() . '[select]">';
                $output .=  '<option value="0">No</option>';
                $output .=  '<option value="1">Yes</option>';
                $output .= '</select>';
                break;
            case 'text':
                $output .= '<input class="filter admin__control-text" type="text" name="' . $attribute->getAttributeCode() . '"/>';
                break;
            case 'textarea':
                $output .= '<input class="filter admin__control-text" type="text" name="' . $attribute->getAttributeCode() . '"/>';
                break;
        }
        echo $output . "<br /><br /></div>";
    }
    
    /** Get Attribute Options
     * @param object $attribute
     * @return array
     */
    public function getAttributeOptions($attribute) {
        /** @var \Magento\Eav\Model\Entity\Attribute\Source\Table $sourceModel */
        $sourceModel = $this->tableFactory->create();
        $sourceModel->setAttribute($attribute);
        return $sourceModel->getAllOptions();
    }
}