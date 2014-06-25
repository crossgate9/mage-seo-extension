<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Customurl_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$_customurl = Mage::registry('current_customurl');
		
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/customurl/save'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
		
		$_fieldset = $form->addFieldset('base_fieldset', array('legend'=>$this->__('Custom URL Information')));
		
		if ($_customurl->getData('entity_id'))
			$isElementDisabled = true;
		else
			$isElementDisabled = false;
		
		// [TODO] add field to fieldset
		$_fieldset -> addField(
			'url', 'text',
			array(
				'name' => 'url',
				'label' => $this->__('URL'),
				'title' => $this->__('URL'),
				'class' => 'required-entry',
			)
		);
		
		$_fieldset -> addField(
			'priority', 'text',
			array(
				'name' => 'priority',
				'label' => $this->__('Priority'),
				'title' => $this->__('Priority'),
				'class' => 'required-entry',
		 		'comment' => 'Number from [0, 1], which 1 indicate maximum priority',
			)
		);
		
		$_fieldset -> addField(
			'changefraq', 'select',
			array(
				'name' => 'changefraq',
				'label' => $this->__('Change Frequency'),
				'title' => $this->__('Change Frequency'),
				'class' => 'required-entry',
				'values' => Mage::helper('seosuite')->getChangeFreqArray(),
			)
		);
	
        if ($_customurl->getData('entity_id')) {
            $form->addField('entity_id', 'hidden', array(
                'name' => 'customurl_id',
            ));
			$_customurl_data = $_customurl->getData();
			$form->setValues($_customurl_data);
        }
		
		$_old = $this->_getSession('old');
		if (isset($_old)) {
			unset($_old['key']);
			unset($_old['form_key']);
			$form->setValues($_old);
			$this->_unsetSession();
		}

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
	
	protected function _getSession($_label) {
		return Mage::getSingleton('seosuite/session')->getData($_label);
	}
	
	protected function _unsetSession() {
		Mage::getSingleton('seosuite/session')->clear();
	}
}
