<?php 

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
	protected function _prepareForm() {
		$_imagesitemap = Mage::registry('current_imagesitemap');
		
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/imagesitemap/save'),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
		
		$_fieldset = $form->addFieldset('base_fieldset', array('legend'=>$this->__('Imagesitemap Information')));
		
		if ($_imagesitemap->getData('entity_id'))
			$isElementDisabled = true;
		else
			$isElementDisabled = false;
		
		// [TODO] add field to fieldset
		/*$_fieldset -> addField('enabled', 'select', array(
			'label' => $this->__('Status'),
			'title' => $this->__('Status'),
			'name' => 'enabled',
			'required' => true,
			'options' => Mage::getSingleton('mgserver/feed')->getAvailableStatuses(),
		));*/
		
		$_fieldset -> addField(
			'value_id', 'select',
			array(
				'name' => 'value_id',
				'label' => $this->__('Image'),
				'title' => $this->__('Image'),
				'class' => 'required-entry',
				'values' => Mage::helper('seosuite')->getImageGalleryList(),
				'disabled'  => $isElementDisabled,
			)
		);
		
		$_fieldset -> addField(
			'title', 'text',
			array(
				'name' => 'title',
				'label' => $this->__('Image Title'),
				'title' => $this->__('Image Title'),
				'class' => 'required-entry',
			)
		);
		
		$_fieldset -> addField(
			'caption', 'textarea',
			array(
				'name' => 'caption',
				'label' => $this->__('Image Caption'),
				'title' => $this->__('Image Caption'),
				'class' => 'required-entry',
			)
		);
		
        if ($_imagesitemap->getData('entity_id')) {
            $form->addField('entity_id', 'hidden', array(
                'name' => 'imagesitemap_id',
            ));
			$_imagesitemap_data = $_imagesitemap->getData();
			$form->setValues($_imagesitemap_data);
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
