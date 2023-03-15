<?php

namespace Training\Jobs\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Training\Jobs\Model\Department;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;

class Edit extends Action
{
    /** 
     * Core registry
     * 
     * @var Registry
     */
    protected $_coreRegistry = null;

    /** 
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var Department
     */
    protected $_model;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param Department $model
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        Department $model
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $model;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Training_Jobs::department_save');
    }

    /** 
     * Init actions
     * 
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Training_Jobs::department')
            ->addBreadcrumb(__('Department'), __('Department'))
            ->addBreadcrumb(__('Manage Departments'), __('Manage Departments'));
        return $resultPage;
    }

    /**
     * Edit Department
     * @return Page|Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;

        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This department not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('jobs_department', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Department') : __('New Department'),
            $id ? __('Edit Department') : __('New Department')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Departments'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getName() : __('New Department'));

        return $resultPage;
    }
}
