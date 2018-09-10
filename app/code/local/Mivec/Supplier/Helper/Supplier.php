<?php
class Mivec_Supplier_Helper_Supplier extends Mage_Core_Helper_Abstract
{
    public function getSupplierCollection($fields="" , $value ="")
    {
        $collection = Mage::getModel("supplier/supplier")
            ->getCollection();

        if (!empty($fields)) {
            $i = 0;
            foreach ($fields as $field) {
                $collection->addAttributeToFilter($field , array("eq"   => $value[0]));
                $i++;
            }
        }
        return $collection;
    }

    public function getSuppliers($fields="" , $value ="")
    {
        $suppliers = $this->getSupplierCollection($fields , $value);
        if ($suppliers) {
            $data = array();
            foreach ($suppliers->getItems() as $item) {
                $data[] = $item->getData();
            }
            return $data;
        }
    }

    public function toOptions($fields="" , $value ="")
    {
        if ($suppliers = $this->getSuppliers($fields="" , $value ="")) {
            $data = array();
            foreach ($suppliers as $item) {
                $data[$item["id"]] = $item["company_name"];
            }
            return $data;
        }
    }

}