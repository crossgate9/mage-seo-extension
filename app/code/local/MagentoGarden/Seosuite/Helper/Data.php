<?php

class MagentoGarden_Seosuite_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function renderOriginColumn($row, $index) {
		$_sku = $row->getData($index);
		$_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $_sku);
		return $_product->getName() .' ('.$_sku.')';
	}

	public function getRoute($_module) {
		$_route = Mage::getStoreConfig('magentogarden_seosuite/friendly_url/'.$_module.'_router');
		if (! $_route) {
			$_route = $_module;
		}
		return $_route;
	}
	
	public function getContentDivClass() {
		return Mage::getStoreConfig('magentogarden_seosuite/analytic/content_class');
	}
	
	public function isProductOpenGraphEnabled() {
		return Mage::getStoreConfig('magentogarden_seosuite/open_graph/open_graph_product') == '1';
	}
	
	public function getUserSitemapStyle() {
		return Mage::getStoreConfig('magentogarden_seosuite/user_sitemap/seosuite_user_sitemap_style');
	}
	
	public function getUserSitemapTitle() {
		return Mage::getStoreConfig('magentogarden_seosuite/user_sitemap/seosuite_user_sitemap_title');
	}
	
	public function isPaginationEnabled() {
		return Mage::getStoreConfig('magentogarden_seosuite/general/enabled_pagination') == '1';
	}
	
	public function isImageSitemapEnabled() {
		return Mage::getStoreConfig('magentogarden_seosuite/general/enabled_image_sitemap') == '1';
	}
	
	public function isBlogSitemapEnabled() {
		return Mage::getStoreConfig('magentogarden_seosuite/general/enabled_blog_sitemap') == '1';
	}
	
	public function isIncludeAllGallary() {
		return Mage::getStoreConfig('magentogarden_seosuite/general/include_all_gallery') == '1';
	}
	
	public function isIncludeCustomurl() {
		return Mage::getStoreConfig('magentogarden_seosuite/general/include_customurl') == '1';
	}
	
	public function isCanonicalEnabled() {
		return Mage::getStoreConfig('magentogarden_seosuite/canonical/enable') == '1';
	}
	
	public function getProductCanonicalStyle() {
		return Mage::getStoreConfig('magentogarden_seosuite/canonical/style');
	}
	
	public function isBlogEnabled() {
		return Mage::getStoreConfig('blog/blog/enabled') == '1';
	}
	
	public function getImageGalleryList() {
		$_products = Mage::getResourceModel('catalog/product_collection') -> getItems();
		$_imagesitemaps = Mage::getModel('seosuite/imagesitemap')->getCollection() -> getItems();
		$_imagesitemap_hash = array();
		foreach ($_imagesitemaps as $_imagesitemap) {
			$_imagesitemap_hash[$_imagesitemap->getData('value_id')] = 1;
		}
		
		$_result = array();
		foreach ($_products as $_product) {
			$_product = Mage::getModel('catalog/product')->load($_product->getId());
			$_gallery = $_product -> getMediaGalleryImages();
			foreach ($_gallery as $_image) {
				if (isset($_imagesitemap_hash[$_image['value_id']])) 
					continue;
				$_label = $_product->getName() . '#' . $_image['url'];
				$_result[] = array(
					'label' => $_label,
					'value' => $_image['value_id'],
				); 
			}
		}
		return $_result;
	}
	
	public function getSkuArray() {
		$_products = Mage::getResourceModel('catalog/product_collection') -> getItems();
		$_sku_mappings = Mage::getModel('orderexporter/mgsku')->getCollection()->getItems();
		/*$_sku_hash = array();
		foreach ($_sku_mappings as $_sku_map) {
			$_sku_hash[$_sku_map->getData('sku')] = 1;
		}*/
		
		$_result = array();
		foreach ($_products as $_product) {
			$_product = Mage::getModel('catalog/product')->load($_product->getId());
			//if (isset($_sku_hash[$_product->getData('sku')]))
			//	continue;
			
			$_result[] = array(
				'label' => $_product->getData('name').' ('.$_product->getData('sku').')',
				'value' => $_product->getData('sku'),
			);
		}
		return $_result;
	}
	
	public function getChangeFreqArray() {
		return array(
			array('label' => 'Always', 'value' => 'Always'),
			array('label' => 'Hourly', 'value' => 'Hourly'),
			array('label' => 'Daily', 'value' => 'Daily'),
			array('label' => 'Weekly', 'value' => 'Weekly'),
			array('label' => 'Monthly', 'value' => 'Monthly'),
			array('label' => 'Yealy', 'value' => 'Yealy'),
			array('label' => 'Never', 'value' => 'Never'),
		);
	}
}