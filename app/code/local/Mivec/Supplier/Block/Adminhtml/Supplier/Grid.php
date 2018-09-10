<?php
class Mivec_Supplier_Block_Adminhtml_Supplier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId("supplierGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("desc");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("supplier/supplier")
            ->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => 'ID',
            'align'     =>'left',
            'width'     => '20px',
            'index'     => 'id',
        ));

        $this->addColumn('company_name', array(
            'header'    => 'Company Name',
            'align'     =>'left',
            'width'     => '150px',
            'index'     => 'company_name',
        ));

        //markets
        $markets = Mage::helper("supplier/market")->getMarkets();
        $this->addColumn("market" , array(
            "header"    => "Market",
            "align"     => "left",
            "width"     => "50px",
            "type"      => "options",
            "index"     => "market",
            "options"   => $markets
        ));

        $this->addColumn('merchant_no', array(
            'header'    => 'Merchant NO.',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'merchant_no',
        ));

        $this->addColumn('created_at', array(
            'header'    => 'Create Date',
            'align'     =>'left',
            'width'     => '50px',
            'index'     => 'created_at',
        ));

        $isValidate = Mivec_Supplier_Model_Supplier::getIsValidate();
        $this->addColumn('is_validate', array(
            'header'    => 'Validate',
            'align'     =>'left',
            'width'     => '50px',
            "type"      => "options",
            "options"   => $isValidate,
            'index'     => 'is_validate',
        ));
        $this->addColumn('action',
            array(
                'header'    =>  'Action',
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => 'Edit',
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}