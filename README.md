## Custom Page Cache
Add excluding parameter to page cache in order to have the same cache key

## Installation
- composer config repositories.khnaim-custom-page-cache git "https://github.com/khnaim/module-custom-page-cache.git"
- composer require khnaim/custom-page-cache
- php bin/magento module:enable Khnaim_CustomPageCache
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy

## Configuration
To use this feature, you have to activate it in the backend Stores > Configuration > Khnaim > Custom Page Cache Settings > Custom Page Cache Whitelist

## Dependencies
Consider the following resources for external dependencies:
- Magento_PageCache
