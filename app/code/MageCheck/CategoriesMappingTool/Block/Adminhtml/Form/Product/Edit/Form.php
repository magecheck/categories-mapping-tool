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

namespace MageCheck\CategoriesMappingTool\Block\Adminhtml\Form\Product\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
    }
}