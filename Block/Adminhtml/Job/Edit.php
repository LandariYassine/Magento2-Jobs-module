<?php

namespace Training\Jobs\Block\Adminhtml\Job;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Phrase;

class  Edit extends Container
{
    /** 
     * Core registry
     * 
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Job edit block
     * 
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Training_Jobs';
        $this->_controller = 'adminhtml_job';

        parent::_construct();

        if ($this->_isAllowedAction('Training_Jobs::job_save')) {
            $this->buttonList->update('save', 'label', __('Save Job'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage_init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#edit_form'
                            ],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
    }

    /**
     * Get header with Job name
     * 
     * @return Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('jobs_job')->getId()) {
            return __("Edit Job '%1'", $this->escapeHtml($this->_coreRegistry->registry('jobs_Job')->getTitle()));
        } else {
            return __('New Job');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('jobs/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
