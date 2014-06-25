<?php

class MagentoGarden_Seosuite_Model_Blog_Paginator extends Mage_Core_Model_Abstract {
	
	public function _construct() {
		$this->_get_collection();
		parent::_construct();
	}
	
	protected function _get_collection() {
		if ($this->getData('collection')) {
			return $this->getData('collection');
		}
		
		$_collection = Mage::getModel('blog/blog')->getCollection()
						->addPresentFilter()
						->addStoreFilter()
						->setOrder('created_time', 'desc');
		
		/*$_tag_filter = '';
		if ($_tag = $this->getRequest()->getParam('tag')) {
			$_collection -> addTagFilter(urldecode($_tag));
			$_tag_filter = "/tag/{$_tag}/";
		}
		
		$this->setTagFilter($_tag_filter);*/
		
		Mage::getSingleton('blog/status')->addEnabledFilterToCollection($_collection);
		
		if ($this->getCategoryMode()) {
			Mage::getSingleton('blog/status')->addCatFilterToCollection($_collection, $this->getCatId());
		}
		
		$this->setData('collection', $_collection);
		
		return $this->getData('collection');
	}
	
	private function _get_current_page() {
		$_current_page = (int) Mage::app()->getFrontController()->getRequest()->getParam('page');
		if (! $_current_page) {
			$_current_page = 1;
		}
		return $_current_page; 
	}
	
	private function _get_pages_count() {
        return ceil($this->_get_collection()->count() / (int) Mage::getStoreConfig('blog/blog/perpage'));
    }
	
	public function createLinks() {
		$_current_page = $this->_get_current_page();
		$_total_page = $this->_get_pages_count();
		$_gets = Mage::app()->getFrontController()->getRequest()->getParams();
		unset($_gets['page']);
		$_head_block = Mage::app()->getLayout()->getBlock('head');
		
		if ($_current_page > 1) {
			$_params = array_merge($_gets, array('page'=>$_current_page-1));
			$_url = Mage::getUrl('blog/*/*', $_params);
			$_url = str_replace('/index/index', '', $_url);
			$_url = str_replace('/view/identifier', '', $_url);
			$_url = str_replace('/page/1', '', $_url);
			$_head_block->addLinkRel('prev', $_url);
		}
		
		if ($_current_page < $_total_page) {
			$_params = array_merge($_gets, array('page'=>$_current_page+1));
			$_url = Mage::getUrl('blog/*/*', $_params);
			$_url = str_replace('/index/index', '', $_url);
			$_url = str_replace('/view/identifier', '', $_url);
			$_head_block->addLinkRel('next', $_url);
		}
		
		return $this;
	}
}
