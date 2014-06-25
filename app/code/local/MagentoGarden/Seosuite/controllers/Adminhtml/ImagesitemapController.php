<?php 
class MagentoGarden_Seosuite_Adminhtml_ImagesitemapController extends Mage_Adminhtml_Controller_Action {
	
	protected function _initImagesitemap($idFieldName = 'id') {
		$this->_title($this->__('Imagesitemap'))->_title($this->__('Manage Imagesitemaps'));
		$_imagesitemap_id = (int) $this->getRequest()->getParam($idFieldName);
		$_imagesitemap = Mage::getModel('seosuite/imagesitemap');
		if ($_imagesitemap_id) {
			$_imagesitemap->load($_imagesitemap_id);
		}
		Mage::register('current_imagesitemap', $_imagesitemap);
		return $this;
	}
	
	public function indexAction() {
		$this->_title($this->__('MagentoGarden Imagesitemap'))->_title($this->__('Manage Imagesitemap'));
		
		$this->loadLayout();
		$this->_setActiveMenu('magentogarden/seosuite');
		
		$this->_addContent(
			$this->getLayout()->createBlock('seosuite/adminhtml_imagesitemap', 'imagesitemap')
		);
		
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('MagentoGarden Imagesitemap'), Mage::helper('adminhtml')->__('MagentoGarden Imagesitemap'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Imagesitemap'), Mage::helper('adminhtml')->__('Manage Imagesitemap'));
		
		$this->renderLayout();
	}
	
	public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('seosuite/adminhtml_imagesitemap_grid')->toHtml());
    }
	

	public function editAction() {
		$this->_initImagesitemap();
		$this->loadLayout();
		$_imagesitemap = Mage::registry('current_imagesitemap');
		
		$this->_title($_imagesitemap->getData('entity_id') ? $_imagesitemap->getData('attribute_code') : $this->__('New Imagesitemap'));
		$this->_setActiveMenu('seosuite/new');
		$this->renderLayout();
	}	
	
	public function newAction() {
		$this->_forward('edit');
	}
	
	private function _getProductId($_value_id) {
		$_products = Mage::getResourceModel('catalog/product_collection') -> getItems();
		foreach ($_products as $_product) {
			$_product = Mage::getModel('catalog/product')->load($_product->getId());
			$_gallery = $_product -> getMediaGalleryImages();
			foreach ($_gallery as $_image) {
				if ($_image['value_id'] == $_value_id)
					return $_product->getId();
			}
		}
		return 1;
	}
	
	public function saveAction() {
		$_data = $this->getRequest()->getParams();
		
		if ($_data) {
			$_imagesitemap = Mage::getModel('seosuite/imagesitemap');
			
			if (isset($_data['imagesitemap_id'])) {
				$_imagesitemap -> load ($_data['imagesitemap_id']);
			} else {
				$_imagesitemap -> setData('product_id', (int) $this->_getProductId($_data['value_id']));
				$_imagesitemap -> setData('created_time', date('Y-m-d H:i:s',time()));
			}
			
			// todo set data
			$_imagesitemap -> setData('title', $_data['title']);
			$_imagesitemap -> setData('caption', $_data['caption']);
			if (isset($_data['value_id'])) {
				$_imagesitemap -> setData('value_id', (int) $_data['value_id']);
			}
			$_imagesitemap -> setData('update_time', date('Y-m-d H:i:s',time()));
			$_imagesitemap -> save();
		}
		$this->getResponse()->setRedirect($this->getUrl('*/imagesitemap'));
	}
	
	public function deleteAction() {
		$_data = $this->getRequest()->getParams();
		$_entity_id = $_data['id'];
		$_imagesitemap = Mage::getModel('seosuite/imagesitemap')->load($_entity_id);
		$_imagesitemap->delete();
		$this->getResponse()->setRedirect($this->getUrl('*/imagesitemap'));
	}

	public function getinfoAction() {
		$_params = $this->getRequest()->getParams();
		$_value_id = $_params['value_id'];
		$_product_id = $this->_getProductId($_value_id);
		$_product = Mage::getModel('catalog/product')->load($_product_id);
		echo json_encode(array(
			'id' => $_product->getId(),
			'description' => $_product->getDescription(),
		));
	}
}
