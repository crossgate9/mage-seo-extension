<?php
/**
 * MagentoGarden
 *
 * @category    block
 * @package     magentogarden_seosuite
 * @copyright   Copyright (c) 2012 MagentoGarden Inc. (http://www.magentogarden.com)
 * @version		1.0
 * @author		MagentoGarden (coder@magentogarden.com)
 */
class MagentoGarden_Seosuite_Block_Sitemap extends Mage_Core_Block_Template {
	
	private function _getCategoryContent($_node) {
		$_category = Mage::getModel('catalog/category') -> load($_node -> getId());
		$_result = array(
			'category_id' => $_node -> getId(),
			'parent_id' => $_node -> getParentId(),
			'name' => $_category -> getName(),
			'is_active' => $_category -> getIsActive(),
			'level' => $_category -> getLevel(),
			'url' => $_category -> getUrl(),
			'products' => $this->_getProductsByCategory($_node->getId()),
		);
		return $_result;
	}
	
	private function _nodeToArray($_node) {
		$_result = $this->_getCategoryContent($_node);
		$_result['children'] = array();
		foreach ($_node -> getChildren() as $_child) {
			$_result['children'][] = $this->_nodeToArray($_child);
		}
		return $_result;
	}
	
	private function _getCategories() {
		$_cat_tree = Mage::getModel('catalog/category') -> getTreeModel() -> load();
		$_root_id = Mage::app() -> getStore() -> getRootCategoryId();
		$_root = $_cat_tree -> getNodeById($_root_id);
		$_categories = $this->_nodeToArray($_root);
		return $_categories;
	}
	
	private function _getProductsByCategory($_cat_id) {
		$_cat = Mage::getModel('catalog/category')->load($_cat_id);
		$_products = $_cat -> getProductCollection() ->addAttributeToFilter('visibility', 4) -> getItems();
						
		$_result = array();
		foreach ($_products as $_product) {
			$_product = Mage::getModel('catalog/product')->load($_product->getId());
			$_result[] = array(
				'id' => $_product->getId(),
				'name' => $_product->getName(),
				'url' => $_product->getUrlPath(),
			);
		}
		return $_result;
	}
	
	private function _getBlog() {
		if (Mage::helper('seosuite')->isBlogEnabled()) {
			$_result = array();
			$storeId = $this->getStoreId();
			$_baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
			$_collection = Mage::getModel('blog/blog')->getCollection();
			foreach($_collection as $_item) {
				$_data = $_item -> getData();
				if ($_data['status'] != 1) continue;
				$_identifier = $_data['identifier'];
				$_url = $_baseUrl . 'blog/' . $_identifier;
				$_result[] = array(
					'url' => $_url,
					'name' => $_data['title'],
				);
			}
			return $_result;
		} 
		return NULL;
	}
	
	private function _getCms() {
		$_result = array();
		$storeId = $this->getStoreId();
		$_baseUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		$_collection = Mage::getModel('cms/page')->getCollection($storeId);
		foreach ($_collection as $_item) {
			
			$_data = $_item -> getData();
			if ($_data['is_active'] != '1') continue;
			$_identifier = $_data['identifier'];
			$_url = $_baseUrl . $_identifier;
			$_result[] = array(
				'url' => $_url,
				'name' => $_item -> getTitle(),
			);
		}
		return $_result;
	}
	
	public function getSitemaps() {
		$_sitemap = array();
		
		$_sitemap['category'] = $this->_getCategories();
		$_sitemap['blog'] = $this->_getBlog();
		$_sitemap['cms'] = $this->_getCms();
		
		return $_sitemap;
	}
	
	private function _printProducts($_products) {
		$_html = '';
		foreach($_products as $_product) {
			$_html .= '<li class="product-li"><a href="'.$_product['url'].'" title="'.$_product['name'].'">'.$_product['name'].'</a></li>';
		}
		return $_html;
	}
	
	public function printTree($_tree) {
		if ($_tree['is_active'] === '0') return '';
		$_html = '';
		if ($_tree['level'] == 1) {
			$_html = '<ul>%s</ul>';
		} else {
			$_class = array();
			$_class[] = 'level-cat-'.(string)($_tree['level']-1);
			$_class[] = 'level-cat';
			$_html .= '<ul class="'.implode(' ', $_class).'">';
			$_html .= '<li><a href="'.$_tree['url'].'" title="'.$_tree['name'].'">'.$_tree['name'].'</a></li>';
			$_html .= '%s</ul>';
		}
		
		$_children = $_tree['children'];
		if (count($_children) > 0)
			foreach ($_children as $_child) {
				$_html = sprintf($_html, $this->printTree($_child).'%s');
			}
		$_html = sprintf($_html, $this->_printProducts($_tree['products']));
		$_html = sprintf($_html, '');
		
		return $_html;
	}
	
	public function isTreeStyle() {
		return true;
		$_style = Mage::helper('seosuite') -> getUserSitemapStyle();
		return ($_style === 'tree');
	}
}
