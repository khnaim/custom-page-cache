<?php
/**
 * Copyright (c) 2021  All Rights Reserved.
 * https://opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 * <khaitchrif@gmail.com>
 */
declare(strict_types=1);

namespace Khnaim\CustomPageCache\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Config
{
    const PATH_CUSTOMER_PAGE_CACHE_WHITELIST = 'custom_page_cache/settings/whitelist';

    /**
     * @var array
     */
    private $excludedParams = [];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface $serializer
     */
    public function __construct(ScopeConfigInterface $scopeConfig, SerializerInterface $serializer)
    {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    /**
     * @return array
     */
    public function getPageCacheWhitelist(): array
    {
        if (!$this->excludedParams) {
            if ($data = $this->scopeConfig->getValue(self::PATH_CUSTOMER_PAGE_CACHE_WHITELIST)) {
                $data = $this->serializer->unserialize($data);
                array_walk(
                    $data,
                    function ($value) {
                        $this->excludedParams[] = $value['param'];
                    }
                );
            }
        }
        return $this->excludedParams;
    }
}
