<?php 

class MagentoGarden_Seosuite_Block_Opengraph extends Mage_Page_Block_Html_Head {
	
	private function _getBlog() {
		$_identifier = $this->getRequest()->getParam('identifier', $this->getRequest()->getParam('id', false));
		return Mage::getModel('blog/post')->loadByIdentifier($_identifier);
	}
	
	private function _getBlogValue($_key) {
		$_blog = $this->_getBlog();
		$_blog_data = $_blog->getData();
		return $_blog_data[$_key];
	}
	
	public function getBlogTitle() {
		$_blog = $this->_getBlog();
		return $_blog->getTitle();
	}
	
	public function getBlogAuthor() {
		$_blog = $this->_getBlog();
		return $_blog->getUser();
	}
	
	public function getBlogPublishedTime() {
		return $this->_getBlogValue('created_time');
	}
	
	public function getBlogUpdateTime() {
		return $this->_getBlogValue('update_time');
	}
	
	public function getBlogTags() {
		return $this->_getBlogValue('tags');
	}
	
	private function _getProduct() {
		return Mage::registry('product');
	}
	
	private function _getProductUrl($_product) {
		return Mage::getBaseUrl().$_product->getUrlKey().Mage::helper('catalog/product')->getProductUrlSuffix();
	}
	
	public function getProductTitle() {
		return $this->_getProduct()->getName();
	}
	
	public function getProductUrl() {
		$_product = $this->_getProduct();
		return $this->_getProductUrl($_product);
	}
	
	public function getProductImage() {
		$_product = $this->_getProduct();
		$_image = $_product->getData('image');
		if ($_image == 'no_selection' || strlen($_image) < 0) 
			return false;
		$_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$_image;
		return $_url;
	}
	
	private function _getCurrentStoreId() {
		return Mage::app() -> getStore() -> getId();
	}
	
	public function getNextProductUrl() {
		$_product = $this->_getProduct();
		$_product_id = $_product -> getId();
		$_product_collection = Mage::getModel('catalog/product')
								->getCollection()
								->addStoreFilter($this->_getCurrentStoreId())
								->addAttributeToFilter('visibility', 4)
								->addAttributeToFilter('entity_id', array('gt' => $_product_id)); 
		$_next_product_id = NULL;
		foreach ($_product_collection -> getItems() as $_next_product) {
			$_next_product_id = $_next_product -> getId();
			break;
		}
		if (empty($_next_product_id)) {
			return '#';
		} else {
			$_next_product = Mage::getModel('catalog/product') -> load($_next_product_id);
			return $this->_getProductUrl($_next_product);
		}
	}
	
	public function getPrevProductUrl() {
		$_product = $this->_getProduct();
		$_product_id = $_product -> getId();
		$_product_collection = Mage::getModel('catalog/product')
								->getCollection()
								->addStoreFilter($this->_getCurrentStoreId())
								->addAttributeToFilter('visibility', 4)
								->addAttributeToFilter('entity_id', array('lt' => $_product_id))
								->addAttributeToSort('entity_id', 'DESC');
		$_prev_product_id = NULL;
		foreach ($_product_collection -> getItems() as $_prev_product) {
			$_prev_product_id = $_prev_product -> getId();
			break;
		} 
		
		if (empty($_prev_product_id)) {
			return '#';
		} else {
			$_prev_product = Mage::getModel('catalog/product') -> load($_prev_product_id);
			return $this->_getProductUrl($_prev_product);
		}
	}
}