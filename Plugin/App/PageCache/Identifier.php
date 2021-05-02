<?php
/**
 * Copyright (c) 2021  All Rights Reserved.
 * https://opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 * <khaitchrif@gmail.com>
 */
declare(strict_types=1);

namespace Khnaim\CustomPageCache\Plugin\App\PageCache;

use Khnaim\CustomPageCache\Model\Config as CustomPageCacheConfig;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\PageCache\Identifier as BaseIdentifier;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\PageCache\Model\Config as PageCacheConfig;

class Identifier
{
    /**
     * @var Http
     */
    private $request;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var PageCacheConfig
     */
    private $pageCacheConfig;

    /**
     * @var CustomPageCacheConfig
     */
    private $customPageCacheConfig;

    /**
     * Identifier constructor.
     * @param Http $request
     * @param Context $context
     * @param Json $serializer
     * @param PageCacheConfig $pageCacheConfig
     * @param CustomPageCacheConfig $customPageCacheConfig
     */
    public function __construct(
        Http $request,
        Context $context,
        Json $serializer,
        PageCacheConfig $pageCacheConfig,
        CustomPageCacheConfig $customPageCacheConfig
    ) {
        $this->request = $request;
        $this->context = $context;
        $this->serializer = $serializer;
        $this->pageCacheConfig = $pageCacheConfig;
        $this->customPageCacheConfig = $customPageCacheConfig;
    }

    /**
     * @param BaseIdentifier $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetValue(BaseIdentifier $subject, callable $proceed): string
    {
        if ($this->pageCacheConfig->getType() == PageCacheConfig::BUILT_IN && $this->pageCacheConfig->isEnabled()) {
            $excludedParams = $this->customPageCacheConfig->getPageCacheWhitelist();
            if ($excludedParams) {
                $url = $this->request->getUriString();
                // @codingStandardsIgnoreLine
                $parsed = parse_url($url);
                if (isset($parsed['query'])) {
                    $rebuildUrl = false;
                    $queryParams = [];
                    // @codingStandardsIgnoreLine
                    parse_str($parsed['query'], $queryParams);
                    foreach ($excludedParams as $param) {
                        if (isset($queryParams[$param])) {
                            unset($queryParams[$param]);
                            $rebuildUrl = true;
                        }
                    }
                    if ($rebuildUrl) {
                        $parsed['query'] = http_build_query($queryParams);
                        if (count($queryParams) == 0) {
                            unset($parsed['query']);
                        }
                        $url = $this->buildUrl($parsed);
                        $data = [
                            $this->request->isSecure(),
                            $url,
                            $this->request->get(ResponseHttp::COOKIE_VARY_STRING) ?: $this->context->getVaryString()
                        ];
                        return sha1($this->serializer->serialize($data));
                    }
                }
            }
        }
        return $proceed();
    }

    /**
     * Build url from parts from parse_url.
     *
     * @param array $parts
     * @return string
     */
    private function buildUrl(array $parts): string
    {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }
}
