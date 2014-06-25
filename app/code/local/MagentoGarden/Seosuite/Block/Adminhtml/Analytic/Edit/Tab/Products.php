<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Analytic_Edit_Tab_Products 
extends Mage_Adminhtml_BLock_Template 
implements Mage_Adminhtml_Block_Widget_Tab_Interface {
	
	public function getTabLabel() {
		return Mage::helper('seosuite')->__('Products');
	}
	
	public function getTabTitle() {
		return Mage::helper('seosuite')->__('Products');
	}
	
	public function canShowTab() {
		return true;
	}
	
	public function isHidden() {
		return false;
	}
	
	/**
	 * getFormKey
	 * @return string form key
	 */
    public function getFormKey() {
    	$_form_key = Mage::getSingleton('core/session')->getFormKey();
    	return $_form_key;
    }
	
	public function getCurlPageUrl() {
    	$_route = '*/mganalytic/curlpage/';
    	return Mage::helper('adminhtml')->getUrl($_route);
	}
	
	private function _getStore() {
		$_params = $this->getRequest()->getParams();
		return (isset($_params['store'])) ? $_params['store'] : 0; 
	}
	
	public function getProducts() {
		$_store = $this->_getStore();
		$_products = Mage::getModel('catalog/product')
						->getCollection()
						->addStoreFilter($_store)
						->addAttributeToFilter('visibility', 4);
		if ($_store != 0)
			$_baseUrl = Mage::app()->getStore($_store)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		else {
			$_store_data = Mage::app()->getDefaultStoreView()->getData();
			$_baseUrl = Mage::app()->getStore($_store_data['store_id'])->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		}
		$_result = array();
		foreach ($_products->getItems() as $_product) {
			$_product = Mage::getModel('catalog/product')->load($_product->getId());
			//$_url = $_product->getProductUrl();
			//if ($_product->getStatus() != '1') continue;
			//var_dump($_product->getData());
			//break;
			$_result[] = array(
				'name' => $_product->getName(),
				'url' => $_baseUrl . $_product->getUrlPath(),
			//	'url' => $_url,
			);
		}
		return $_result;
	}
}