<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Customurl_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('customurlGrid');
		$this->setUseAjax(true);
		$this->setDefaultSort('entity_id');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('seosuite/customurl_collection');
		$this->setCollection($collection);
		
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns() {
		$this->addColumn('entity_id', array(
			'header' => $this->__('ID'),
			'width' => '50px',
			'index' => 'entity_id',
			'type' => 'number',
		));
		
		// [TODO] add column to each field
		$this->addColumn('url', array(
			'header' => $this->__('URL'),
			'width' => '70px',
			'index' => 'url',
			'type' => 'text',
		));
		
		$this->addColumn('priority', array(
			'header' => $this->__('Priority'),
			'width' => '70px',
			'index' => 'priority',
			'type' => 'text',
		));
		
		$this->addColumn('changefraq', array(
			'header' => $this->__('Change Frequency'),
			'width' => '70px',
			'index' => 'changefraq',
			'type' => 'text',
		));
		
		$this->addColumn('creation_time', array(
			'header' => $this->__('Date Created'),
			'width' => '50px',
			'index' => 'created_time',
			'type' => 'datetime',
		));
		
		$this->addColumn('update_time', array(
			'header' => $this->__('Date Updated'),
			'width' => '70px',
			'index' => 'update_time',
			'type' => 'datetime',
		));
		
		return parent::_prepareColumns();
	}
	
	public function getGridUrl() {
		return $this->getUrl('*/*/grid', array('_current'=> true));	
	}
	
	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
	}
}
