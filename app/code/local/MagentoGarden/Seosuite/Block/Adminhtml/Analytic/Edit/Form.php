<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Analytic_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$_form = new Varien_Data_Form(array(
			'id' => 'edit_form',
			'action' => $this->getData('action'),
			'method' => 'post',
			'enctype' => 'multipart/form-data',	
		));
		$_form->setUseContainer(true);
		$this->setForm($_form);
		return parent::_prepareForm(); 
	}
}
