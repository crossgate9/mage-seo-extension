<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('imagesitemapGrid');
		$this->setUseAjax(true);
		$this->setDefaultSort('entity_id');
		$this->setSaveParametersInSession(true);
	}
	
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('seosuite/imagesitemap_collection');
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
		
		$this->addColumn('value_id', array(
			'header' => $this->__('Image'),
			'width' => '70px',
			'index' => 'value_id',
			'type' => 'text',
			'renderer' => 'MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Valueid',
		));
		
		$this->addColumn('product_id', array(
			'header' => $this->__('Product'),
			'width' => '70px',
			'index' => 'product_id',
			'type' => 'text',
			'renderer' => 'MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Productid',
		));
		
		$this->addColumn('title', array(
			'header' => $this->__('Title'),
			'width' => '70px',
			'index' => 'title',
			'type' => 'text',
		));
		
		$this->addColumn('caption', array(
			'header' => $this->__('Caption'),
			'width' => '70px',
			'index' => 'caption',
			'type' => 'text',
			'renderer' => 'MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Caption',
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
