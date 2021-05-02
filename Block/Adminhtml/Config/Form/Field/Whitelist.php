<?php
/**
 * Copyright (c) 2021  All Rights Reserved.
 * https://opensource.org/licenses/OSL-3.0  Open Software License (OSL 3.0)
 * <khaitchrif@gmail.com>
 */
declare(strict_types=1);

namespace Khnaim\CustomPageCache\Block\Adminhtml\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Whitelist extends AbstractFieldArray
{
    protected function _prepareToRender()
    {
        $this->addColumn('param', ['label' => __('Param'), 'class' => 'required-entry']);
        $this->addColumn('comment', ['label' => __('Comment')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
