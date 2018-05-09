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

namespace MageCheck\CategoriesMappingTool\Block\Adminhtml\Form;

class Product extends \Magento\Backend\Block\Widget\Form\Container
{
    /** Core Registry
     * @var \Magento\Framework\Registry $registry
     */
    protected $_coreRegistry;
    
    /**  EAV Attribute Model
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
     */
    protected $_attributeFactory;
    
    /**  Category Collection Factory
     * @var \MageCheck\Centric\Block\Adminhtml\Form\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    protected $_categoryCollectionFactory;
    
    /**  Magento Form Key
     * @var \Magento\Framework\Data\Form\FormKey $formKey
     */
    protected $_formKey;

    /** Constructor
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory
     * @param \MageCheck\Centric\Block\Adminhtml\Form\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\Data\Form\FormKey $formKey,
        $data = array()
    )
    {
        parent::__construct($context, $data);
        
        $this->_objectId = 'mapping_product';
        $this->_blockGroup = 'MageCheck_CategoriesMappingTool';
        $this->_controller = 'adminhtml_form_product';
        
        $this->_coreRegistry = $registry;
        $this->_attributeFactory = $attributeFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_formKey = $formKey;
    }

    /**
     * Prepare layout.
     * Adding save_and_continue button
     *
     * @return $this
     */
    protected function _preparelayout()
    {
        $this->removeButton('back');
        $this->removeButton('reset');
        $this->removeButton('save');
        $this->buttonList->add(
            'update',
            [
                'label' => __('Update'),
                'class' => 'disabled save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#form_centric']],
                    'form-role' => 'save',
                ],
            ],
            1
        );
        return parent::_prepareLayout();
    }

    /**
     * Return translated header text depending on creating/editing action
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Category Product Association Tool');
    }
    
    /** Get Custom Form Key
     * @return \Magento\Framework\Data\Form\FormKey $formKey
     */
    public function getFormKey(){
        return $this->formKey->getFormKey();
    }

    /** Get Category List
     * @return object
     */
    public function getCategoryList()
    {
        return $this->_categoryCollectionFactory->create()
                                                                        ->addAttributeToSelect('*')
                                                                        ->addAttributeToFilter('is_active', 1)
                                                                        ->addAttributeToFilter('level', array('gt' => 1))
                                                                        ->setOrder('position', 'ASC');
    }

    /** Get Product Attributes
     * @return object
     */
    public function getProductAttributes()
    {
        return $this->_attributeFactory->getCollection()
                                                         ->addFieldToFilter(\Magento\Eav\Model\Entity\Attribute\Set::KEY_ENTITY_TYPE_ID, 4)
                                                         ->addFieldToFilter('attribute_code', array('nin' => $this->getIgnoredAttributes()));
    }
    
    /** Get Ignored Attributes
     * @return array
     */
    public function getIgnoredAttributes()
    {
        return array(
            "backcover", "category_ids", "cost", "created_at", "custom_design", 
            "custom_design_from", "custom_design_to", "custom_layout", "custom_layout_update",
            "description", "gallery", "gift_message_available", "has_options", 
            "image", "image_label", "links_exist", "links_purchased_separately", 
            "links_title", "media_gallery", "meta_description", "meta_keyword",
            "meta_title", "minimal_price", "msrp", "msrp_display_actual_price_type",
            "news_from_date", "news_to_date", "old_id", "options_container", 
            "page_layout", "price", "price_type", "price_view", "quantity_and_stock_status",
            "required_options", "samples_title", "shipment_type", "short_description",
            "sku_type", "small_image", "small_image_label","special_from_date", 
            "special_price", "special_to_date", "status", "swatch_image", "tax_class_id",
            "thumbnail", "thumbnail_label", "tier_price", "updated_at", "url_key",
            "url_path", "weight_type", "visibility", "order"
        );
    }
}