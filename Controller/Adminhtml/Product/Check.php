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

class Check extends \Magento\Backend\App\Action
{
    /** Product Model
     * @var \Magento\Catalog\Model\Product $product
     */
    protected $_product;

    /**  Product Collection
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    protected $_collection;

    /**  Product Attribute Set
     * @var \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet
     */
    protected $_attributeSet;

    /**  Current Product Collection
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $_productCollection
     */
    protected $_productCollection;
    
    /** Result Return Json Factory
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory 
     */
    protected $_returnJsonFactory;

    /** Constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product, 
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        parent::__construct($context);

        $this->_product = $product;
        $this->_collection = $collection;
        $this->_attributeSet = $attributeSet;
        $this->_returnJsonFactory = $resultJsonFactory;
    }

    /** Get Filter HTML based on attribute type
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $result = $this->_returnJsonFactory->create();
        if (!$this->getRequest()->getParam('isAjax'))
        {
            $result->setData(array(
                'success' => false,
                'message' => __('Request is not ajax!')
            ));
        }

        $data = $this->getRequest()->getParams();
        unset($data['key'],$data['isAjax'],$data['category'],$data['type'],$data['form_key'],$data['attribute']);
        if (count($data))
        {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $_collection */
            $this->_productCollection = $this->_collection->addAttributeToSelect('*');
            foreach ($data as $key => $value)
            {
                if(is_array($value))
                {
                    if(array_key_exists('select', $value))
                    {
                        $this->_productCollection->addAttributeToFilter($key,$value['select']);
                    }else
                    {
                        $this->_productCollection->addAttributeToFilter($key,implode(',',$value));
                    }
                }else
                {
                    $this->_productCollection->addAttributeToFilter($key,array('like' => '%'.$value.'%'));
                }
            }
            if($this->_productCollection->count()){
                $result->setData(array(
                    'success' => true,
                    'message' => $this->generateHtml()
                ));
            }else{
                $result->setData(array(
                    'success' => false,
                    'message' => __('There are no products according to the applied filters!')
                ));
            }
        }else
        {
            $result->setData(array(
                'success' => false,
                'message' => __('Please fill in at least one attribute filter!')
            ));
        }
        return $result;
    }

    /** Generate HTML based on attribute
     * @param object $attribute
     * @return string
     */
    public function generateHtml()
    {
        $output = '';
        $count = 1;
        foreach ($this->_productCollection as $p) {
            $product = $this->_product->load($p->getId());
            $output .= '<tr class="data-row '.( $count%2 == 0 ? '_odd-row' : '' ).'">'
                                . '<td>'.$product->getId().'</td>'
                                . '<td>'.$product->getSku().'</td>'
                                . '<td class="data-grid-thumbnail-cell"><img class="admin__control-thumbnail" src="/pub/media/catalog/product'.$product->getThumbnail().'" alt="'.$product->getName().'" /></td>'
                                . '<td>'.$product->getTypeId().'</td>'
                                . '<td>'.$this->_attributeSet->get($product->getAttributeSetId())->getAttributeSetName().'</td>'
                                . '<td>'.$product->getName().'</td>'
                                . '<td>'.($product->getStatus() ? __('Yes') : __('No') ).'</td>'
                            . '<tr>';
            $count++;
        }
        return $output;
    }
}
