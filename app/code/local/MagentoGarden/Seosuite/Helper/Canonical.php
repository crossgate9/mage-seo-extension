<?php

class MagentoGarden_Seosuite_Helper_Canonical extends Mage_Core_Helper_Abstract {
	private function _get_default_url($_product) {
		$params = array('_ignore_category'=>true);
		return $_product->getUrlModel()->getUrl($_product, $params);
	}
	
	private function _get_root_id($_store_view = null) {
		if (isset($_store_view)) {
			return Mage::app()->getStore($_store_view)->getRootCategoryId();
		} else {
			return Mage::app()->getStore()->getRootCategoryId();
		}
	}
	
	private function _get_top_category($_category, $_store_view) {
		$_root_id = $this->_get_root_id($_store_view);
		while ($_category->getParentId() != $_root_id) {
			$_id = $_category->getParentId();
			$_category = Mage::getModel('catalog/category')->load($_id);
		}
		return $_category;
	}
	
	private function _find_category($_product, $_store_view) {
		$_root_id = $this->_get_root_id($_store_view);
		$_category_ids = $_product->getCategoryIds();
		$_target_category = NULL;

		$_max_depth = -1;
		foreach ($_category_ids as $_category_id) {
			if ($_category_id == $_root_id) continue;
			
			$_category = Mage::getModel('catalog/category')->load($_category_id);
			$_depth = 0;
			
			while ($_category->getParentId() != $_root_id) {
				$_id = $_category->getParentId();
				$_category = Mage::getModel('catalog/category')->load($_id);
				$_depth ++;
			}
			
			if ($_depth > $_max_depth) {
				$_target_category = Mage::getModel('catalog/category')->load($_category_id);
				$_max_depth = $_depth;
			}
		}
		
		return $_target_category;
	}
	
	public function getUrl($_product, $_type, $_store_view = null) {
		if ($_type == 'default') {
			$_url = $this->_get_default_url($_product);
		}
		
		$_base_url = Mage::app()->getStore($_store_view)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		
		if ($_type == 'one-level') {
			$_category = $this->_find_category($_product, $_store_view);
			if (isset($_category)) {
				$_category = $this->_get_top_category($_category, $_store_view);
				$_path = $_category -> getUrlPath();
				$_path = str_replace('.html', '', $_path);
				if (strlen($_path) > 0)
					$_url = $_base_url . $_path . '/' . $_product->getUrlPath();
				else 
					$_url = $_base_url . $_product->getUrlPath();
			} else {
				$_url = $this->_get_default_url($_product);
			}
		}
		
		if ($_type == 'all-level') {
			$_category = $this->_find_category($_product, $_store_view);
			if (isset($_category)) {
				$_path = $_category -> getUrlPath() . '/';
				$_path = str_replace('.html', '', $_path);
			} else {
				$_path = "";
			}
			$_url = $_base_url . $_path . $_product->getUrlPath();
		}
		
		return $_url;
	}
}
