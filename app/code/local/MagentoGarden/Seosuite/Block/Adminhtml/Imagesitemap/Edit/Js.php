<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Edit_Js extends Mage_Core_Block_Template {
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
	
	public function getFormKey() {
		$_form_key = Mage::getSingleton('core/session')->getFormKey();
		return $_form_key;
	}
	
	public function getInfoUrl() {
    	$_route  = '*/imagesitemap/getinfo/';
    	return Mage::helper('adminhtml')->getUrl($_route);
    }
	
	public function getProductInfo() {
		$_imagesitemap = Mage::registry('current_imagesitemap');
		$_imagesitemap_data = $_imagesitemap -> getData();
		if (isset($_imagesitemap['entity_id'])) {
			$_product_id = $_imagesitemap['product_id'];
			$_product = Mage::getModel('catalog/product')->load($_product_id);
			return array(
				'id' => $_product->getId(),
				'description' => $_product->getDescription(),
			);
		} else {
			return NULL;
		}
	}
}