<?php namespace Sebwite\Sidebar\Model\Config\Source;

/**
 * Class:Categories
 * Sebwite\Sidebar\Model\Config\Source
 *
 * @author      Sebwite
 * @package     Sebwite\Sidebar
 * @copyright   Copyright (c) 2015, Sebwite. All rights reserved
 */
class Categories implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Store categories cache
     *
     * @var array
     */
    protected $_storeCategories = [];
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    private $_categoryFactory;

    /**
     * Categories constructor.
     *
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    public function __construct(\Magento\Catalog\Model\CategoryFactory $categoryFactory)
    {
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $cacheKey = sprintf('%d-%d-%d-%d', 1, false, false, true);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         */
        $category = $this->_categoryFactory->create();
        $storeCategories = $category->getCategories(1, $recursionLevel = 1, false, false, true);

        $this->_storeCategories[$cacheKey] = $storeCategories;

        $resultArray = [];
        foreach ($storeCategories as $category) {
            $resultArray[$category->getId()] = $category->getName();
        }

		$resultArray['current_category_children'] = __('Current Category Children');
		$resultArray['current_category_parent_children'] = __('Current Category Parent Children');
		$resultArray['current_category_parent_siblings_and_children'] = __('Current Category Parent, Siblings and Children');
        $resultArray['current_category_children_or_siblings'] = __('Current Category Children or Siblings if the category has no children ');

        return $resultArray;
    }
}
