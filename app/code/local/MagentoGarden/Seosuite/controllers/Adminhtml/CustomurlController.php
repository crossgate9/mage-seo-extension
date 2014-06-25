<?php 
class MagentoGarden_Seosuite_Adminhtml_CustomurlController extends Mage_Adminhtml_Controller_Action {
	
	protected function _initCustomurl($idFieldName = 'id') {
		$this->_title($this->__('Manage Customer URLs'));
		$_customurl_id = (int) $this->getRequest()->getParam($idFieldName);
		$_customurl = Mage::getModel('seosuite/customurl');
		if ($_customurl_id) {
			$_customurl->load($_customurl_id);
		}
		Mage::register('current_customurl', $_customurl);
		return $this;
	}
	
	public function indexAction() {
		$this->_title($this->__('Manage Custom URLs'));
		
		$this->loadLayout();
		$this->_setActiveMenu('magentogarden/seosuite');
		
		$this->_addContent(
			$this->getLayout()->createBlock('seosuite/adminhtml_customurl', 'customurl')
		);
		
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('MagentoGarden Custom URL'), Mage::helper('adminhtml')->__('MagentoGarden Custom URL'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Custom URL'), Mage::helper('adminhtml')->__('Manage Custom URL'));
		
		$this->renderLayout();
	}
	
	public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('seosuite/adminhtml_customurl_grid')->toHtml());
    }
	

	public function editAction() {
		$this->_initCustomurl();
		$this->loadLayout();
		$_customurl = Mage::registry('current_customurl');
		
		$this->_title($_customurl->getData('entity_id') ? $_customurl->getData('attribute_code') : $this->__('New Custom URL'));
		$this->_setActiveMenu('seosuite/new');
		$this->renderLayout();
	}	
	
	public function newAction() {
		$this->_forward('edit');
	}
	
	public function saveAction() {
		$_data = $this->getRequest()->getParams();
		
		// upload image file
		// todo process different type
		/*
		$_has_file = false;
		if (strlen($_FILES['image_url']['name']) > 0) {
			$_filename = rand(0, 10000).$_FILES['image_url']['name'];
			$_uploader = new Varien_File_Uploader('image_url');
			$_path = Mage::helper('smartlabel/data')->getMediaDir();
			$_uploader->save($_path, $_filename);
			$_has_file = true;
		}
		 */
		
		if ($_data) {
			$_customurl = Mage::getModel('seosuite/customurl');
			$_is_create = false;
			
			if (isset($_data['customurl_id'])) {
				$_customurl -> load ($_data['customurl_id']);
			} else {
				$_customurl -> setData('created_time', date('Y-m-d H:i:s',time()));
				$_is_create = true;
			}
			
			// todo set data
			$_customurl -> setData('url', $_data['url']);
			$_customurl -> setData('priority', $_data['priority']);
			$_customurl -> setData('changefraq', $_data['changefraq']);
			$_customurl -> setData('update_time', date('Y-m-d H:i:s',time()));
			$_customurl -> save();
		}
		$this->getResponse()->setRedirect($this->getUrl('*/customurl'));
	}
}
