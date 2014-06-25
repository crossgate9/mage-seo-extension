<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Customurl_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	public function __construct() {
		$this -> _objectId = 'entity_id';
		$this -> _blockGroup = 'seosuite';
		$this -> _controller = 'adminhtml_customurl';

		parent::__construct();

		$this -> _updateButton('save', 'label', Mage::helper('seosuite') -> __('Save Custom URL'));
		$this -> _updateButton('delete', 'label', Mage::helper('seosuite') -> __('Delete Custom URL'));
		$this -> _removeButton('delete');
	}
	
	public function getCustomurlId() {
		return Mage::registry('current_customurl') -> getData('entity_id');
	}
	
	public function getHeaderText()
    {
    	if (Mage::registry('current_customurl')->getData('entity_id')) {
            return $this->htmlEscape(Mage::registry('current_customurl')->getData('title'));
        }
        else {
            return Mage::helper('seosuite')->__('New Custom URL');
        }
    }
	
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
	
	protected function _getSaveAndContinueUrl() {
		return $this -> getUrl('*/*/save', array('_current' => true, 'back' => 'edit', 'tab' => '{{tab_id}}'));
	}
	
	public function getFormHtml()
    {
        $html = parent::getFormHtml();
        return $html;
    }
}
