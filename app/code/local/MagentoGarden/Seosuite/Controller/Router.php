<?php

class MagentoGarden_Seosuite_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {
	public function initControllerRouters($_observer) {
		$_front = $_observer -> getEvent() -> getFront();
		$_seosuite = new MagentoGarden_Seosuite_Controller_Router();
		$_front->addRouter('seosuite', $_seosuite);
	}
	
	public function match(Zend_Controller_Request_Http $_request) {
		if (!Mage::app()->isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
		
		$_identifier = $_request->getPathInfo();
		
		// review friendly url
		$_review_router = Mage::helper('seosuite')->getRoute('review');
		if (substr(str_replace("/", "",$_identifier), 0, strlen($_review_router)) == $_review_router) {
			$_identifier = substr_replace($_request->getPathInfo(),'', 0, strlen("/" . $_review_router. "/") );
			if ($_identifier == '') return false;
			$_url_rewrite = Mage::getModel('core/url_rewrite')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->loadByRequestPath($_identifier);
			$_product_id = $_url_rewrite->getProductId();
			$_request -> setModuleName('review')
					  -> setControllerName('product')
					  -> setActionName('list')
					  -> setParam('id', $_product_id);
			return true;
		}
		
		// rss friendly url
		$_rss_router = Mage::helper('seosuite')->getRoute('rss');
		if (substr(str_replace("/", "",$_identifier), 0, strlen($_rss_router)) == $_rss_router) {
			$_identifier = substr_replace($_request->getPathInfo(),'', 0, strlen("/" . $_rss_router. "/") );
			if ($_identifier == '') return false;
			
			if ($_identifier == 'new-products.html') {
				$_request -> setModuleName('rss')
						  -> setControllerName('catalog')
						  -> setActionName('new')
						  -> setParam('store_id', Mage::app()->getStore()->getId());
				return true;
			}
			
			if ($_identifier == 'special-products.html') {
				$_request -> setModuleName('rss')
						  -> setControllerName('catalog')
						  -> setActionName('special')
						  -> setParam('store_id', Mage::app()->getStore()->getId());
				return true;
			}
			
			if ($_identifier == 'sales-rule.html') {
				$_request -> setModuleName('rss')
						  -> setControllerName('catalog')
						  -> setActionName('salesrule')
						  -> setParam('store_id', Mage::app()->getStore()->getId());
				return true;
			}
			
			if ($_identifier == 'blog.html') {
				$_request -> setModuleName('blog')
						  -> setControllerName('rss')
						  -> setActionName('index')
						  -> setParam('store_id', Mage::app()->getStore()->getId());
				return true;
			}
			
			$_url_rewrite = Mage::getModel('core/url_rewrite')
                    ->setStoreId(Mage::app()->getStore()->getId())
                    ->loadByRequestPath($_identifier);
			$_category_id = $_url_rewrite->getCategoryId();
			$_request -> setModuleName('rss')
					  -> setControllerName('catalog')
					  -> setActionName('category')
					  -> setParam('cid', $_category_id)
					  -> setParam('store_id', Mage::app()->getStore()->getId());
			return true;
		}
	}
}
