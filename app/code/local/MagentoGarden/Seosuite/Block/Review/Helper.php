<?php 

class MagentoGarden_Seosuite_Block_Review_Helper extends Mage_Review_Block_Helper {
	public function getReviewsUrl() {
		$_product_id = $this->getProduct()->getId();
		$_url_path = Mage::getModel('catalog/product')->load($_product_id)->getUrlPath();
		$_url = Mage::getUrl('review/product/list');
		$_url = str_replace('product/list/', '', $_url);
		$_url .= $_url_path;
		return $_url;
	}
}
