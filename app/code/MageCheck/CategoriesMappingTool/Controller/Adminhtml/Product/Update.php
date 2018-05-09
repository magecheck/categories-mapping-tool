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

class Update extends \Magento\Backend\App\Action
{
    /** Product Model
     * @var \Magento\Catalog\Model\Product $product
     */
    protected $_product;

    /**  Product Collection
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    protected $_collection;

    /**  Current Product Collection
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $_productCollection
     */
    protected $_productCollection;

    /**  Category Link Management for Assign
     * @var \Magento\Catalog\Model\CategoryLinkRepository $categoryLink
     */
    protected $_categoryLinkManagement;

    /** Constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Catalog\Model\CategoryLinkManagement $categoryLinkManagement
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\Product $product, 
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Catalog\Model\CategoryLinkManagement $categoryLinkManagement
    )
    {
        parent::__construct($context);

        $this->_product = $product;
        $this->_collection = $collection;
        $this->_categoryLinkManagement = $categoryLinkManagement;
    }

    /** Get Filter HTML based on attribute type
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $category = $data['category'];
        $type = (boolean) $data['type'];
        unset($data['key'],$data['attribute'],$data['form_key'],$data['category'],$data['type']);
        
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
            foreach($this->_productCollection as $p)
            {
                if($type)
                {
                    $categoryIds = array($category);
                }else
                {
                    $categoryIds = $p->getCategoryIds();
                    array_push($categoryIds,$category);
                    $categoryIds = array_unique($categoryIds);
                }
                $this->_categoryLinkManagement->assignProductToCategories($p->getSku(),$categoryIds);
            }
            $this->messageManager->addSuccess(__('The products have been assigned to the category!'));
        }
        return $this->_redirect('*/*/index');
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
}
