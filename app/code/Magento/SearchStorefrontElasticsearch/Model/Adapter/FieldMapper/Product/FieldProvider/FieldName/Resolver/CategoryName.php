<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\Resolver;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface;
use Magento\Framework\Registry;
use Magento\SearchStorefrontStore\Model\StoreManagerInterface as StoreManager;

/**
 * Resolver field name for Category name attribute.
 * Copy of Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\Resolver\CategoryName
 */
class CategoryName implements ResolverInterface
{
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param StoreManager $storeManager
     * @param Registry $coreRegistry
     */
    public function __construct(
        StoreManager $storeManager,
        Registry $coreRegistry
    ) {
        $this->storeManager = $storeManager;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Get field name.
     *
     * @param AttributeAdapter $attribute
     * @param array $context
     * @return string
     */
    public function getFieldName(AttributeAdapter $attribute, $context = []): ?string
    {
        if ($attribute->getAttributeCode() === 'category_name') {
            return 'name_category_' . $this->resolveCategoryId($context);
        }

        return null;
    }

    /**
     * @param $context
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function resolveCategoryId($context): int
    {
        if (isset($context['categoryId'])) {
            $id = $context['categoryId'];
        } else {
            $id = $this->coreRegistry->registry('current_category')
                ? $this->coreRegistry->registry('current_category')->getId()
                : $this->storeManager->getStore()->getRootCategoryId();
        }

        return (int)$id;
    }
}
