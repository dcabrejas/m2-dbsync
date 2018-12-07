<?php

declare(strict_types=1);

namespace Dcabrejas\DBSync\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * @author Diego Cabrejas <diego@wearejh.com>
 */
class ConfigValues extends AbstractFieldArray
{
    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('path', ['label' => __('Config Path')]);
        $this->addColumn('value', ['label' => __('Config Value')]);
        $this->_addButtonLabel = __('Add Configuration');
        $this->_addAfter = false;
    }
}
