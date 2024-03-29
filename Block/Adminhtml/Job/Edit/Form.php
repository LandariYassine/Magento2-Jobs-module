<?php

namespace Training\Jobs\Block\Adminhtml\Job\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Store\Model\System\Store;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Training\Jobs\Model\Job;
use Training\Jobs\Model\Source\Job\Status;
use Training\Jobs\Model\Source\Department; 

class Form extends Generic
{

    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var Store
     */
    protected $_status;

    /**
     * @var Store
     */
    protected $_department;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Status $status
     * @param Department $department
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Status $status,
        Department $department,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        $this->_department = $department;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('job_form');
        $this->setTitle(__('Job Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var Job $model */
        $model = $this->_coreRegistry->registry('jobs_job');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('job_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        // Title - type Text
        $fieldset->addField(
            'title',
            'text',
            ['name' => 'title', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        // Type - Type Text
        $fieldset->addField(
            'type',
            'text',
            ['name' => 'type', 'label' => __('Type'), 'title' => __('Type'), 'required' => true]
        );

        // Location - Type text
        $fieldset->addField(
            'location',
            'text',
            ['name' => 'location', 'label' => __('Location'), 'title' => __('Location'), 'required' => true]
        );

     
        $fieldset->addField(
            'date',
            'date',
            ['name' => 'date', 'label' => __('Date'), 'title' => __('Date'), 'required' => false, 'date_format' => 'Y-MM-dd']
        );

        // Status - Dropdown
        if (!$model->getId()) {
            $model->setStatus('1'); // Enable status when adding a Job
        }
        $statuses = $this->_status->toOptionArray();
        $fieldset->addField(
            'status',
            'select',
            ['name' => 'status', 'label' => __('Status'), 'title' => __('Status'), 'required' => true, 'values' => $statuses]
        );

        // Description - Type textarea
        $fieldset->addField(
            'description',
            'textarea',
            ['name' => 'description', 'label' => __('Description'), 'title' => __('Description'), 'required' => true]
        );

        // Department - Dropdown
        $departments = $this->_department->toOptionArray();
        $fieldset->addField(
            'department_id',
            'select',
            ['name' => 'department_id', 'label' => __('Department'), 'title' => __('Department'), 'required' => true, 'values' => $departments]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
