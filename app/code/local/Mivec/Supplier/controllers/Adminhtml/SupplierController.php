<?php
class Mivec_Supplier_Admihtml_SupplierController extends Mage_Adminhtml_Controller_Action
{
    protected function _init()
    {
        $this->loadLayout()
            ->_setActiveMenu("mivec/supplier")
            ->_addBreadcrumb("Manage Supplier" , "");

        return $this;
    }

    public function indexAction()
    {
        $this->_init()
            ->renderLayout();
    }

    public function editAction()
    {

    }

    public function newAction()
    {
        $this->_forward("edit");
    }

    public function saveAction()
    {

    }

    public function deleteAction()
    {

    }
}