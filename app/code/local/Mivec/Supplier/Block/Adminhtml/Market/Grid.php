<?php
class Mivec_Supplier_Block_Adminhtml_Market_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('marketGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        //$this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('supplier/market')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => 'ID',
            'align'     =>'left',
            'width'     => '50',
            'index'     => 'id',
        ));

        $this->addColumn('name', array(
            'header'    => 'Name',
            'align'     =>'left',
            'width'     => '500px',
            'type'		=> 'text',
            'index'     => 'name',
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
                ,array(
                        'caption'   => 'Delete',
                        'url'       => array('base'=> '*/*/delete'),
                        'field'     => 'id',
                        'confirm'   => 'Are you sure?'
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