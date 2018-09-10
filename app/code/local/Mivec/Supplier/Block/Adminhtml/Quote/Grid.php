<?php
class Mivec_Supplier_Block_Adminhtml_Quote_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId("quoteGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("desc");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $supplier_id = $this->getRequest()->getParam("id");
        $colletion = Mage::getModel("supplier/quote")->getCollection();
        if (!empty($supplier_id)) {
            $colletion->addAttributeToFilter("supplier_id" , $supplier_id);
        }
        $this->setCollection($colletion);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => 'ID',
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn("sku" , array(
            "header"    => "SKU",
            "align"     => "right",
            "width"     => "100px",
            //'filter'=> 'adminhtml/catalog_product_grid',
            //"filter_index"    => "catalog_product_entity.sku",
            "renderer"  => "supplier/adminhtml_quote_renderer_product_sku",
        ));

        $this->addColumn("name" , array(
            "header"    => "Product Name",
            "align"     => "right",
            "width"     => "200px",
            "renderer"  => "supplier/adminhtml_quote_renderer_product_name"
        ));

        $suppliers = Mage::helper("supplier/supplier")->toOptions();
        $this->addColumn("supplier" , array(
            "header"    => "Supplier",
            "align"     => "right",
            "width"     => "100px",
            'type'		=> 'options',
            "options"   => $suppliers,
            "index"     => "supplier_id"
            //"renderer"  => "supplier/adminhtml_quote_renderer_supplier"
        ));

        $this->addColumn("quote" , array(
            "header"    => "Quote",
            "align"     => "right",
            "width"     => "50px",
            "index"     => "quote",
            'currency'  => 'CNY',
            "type"      => "currency",
        ));

        $this->addColumn("created_at" , array(
            "header"    => "Create At",
            "align"     => "right",
            "width"     => "50px",
            "index"     => "created_at",
            "type"      => "date"
        ));

        $this->addColumn("updated_at" , array(
            "header"    => "Update At",
            "align"     => "right",
            "width"     => "50px",
            "index"     => "updated_at",
            "type"      => "date"
        ));

        $this->addColumn('action',
            array(
                'header'    =>  'Action',
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(

                array(
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
        //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}