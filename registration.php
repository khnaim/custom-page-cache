<?php
/**
 * Copyright (c) 2021  All Rights Reserved.
 * https://opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 * <khaitchrif@gmail.com>
 */
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Khnaim_CustomPageCache',
    __DIR__
);
