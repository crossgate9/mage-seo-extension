<?php

class MagentoGarden_Seosuite_Helper_Rss extends Mage_Core_Helper_Abstract {
	public function getCategoryLink($_category_id) {
		$_url = Mage::getUrl('rss/catalog/category');
		$_url = str_replace('catalog/category/', '', $_url);
		$_category = Mage::getModel('catalog/category')->load($_category_id);
		$_url .= $_category -> getUrlPath();
		return $_url;
	}
}
