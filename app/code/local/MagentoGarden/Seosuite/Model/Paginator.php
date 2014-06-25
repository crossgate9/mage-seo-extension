<?php

class MagentoGarden_Seosuite_Model_Paginator extends Mage_Core_Model_Abstract
{
    protected $_productCollection;
    
    protected $_toolbar;
    
    public function _construct()
    {
        $this->_initProductCollection();
        parent::_construct();
    }
    
    protected function _initProductCollection()
    {
        if ($layer = Mage::getSingleton('catalog/layer')) {
            $this->_productCollection = $layer->getProductCollection();
            
            $limit = (int)$this->_getToolbar()->getLimit();
            if ($limit) {
                $this->_productCollection->setPageSize($limit);
            }
        }

        return $this;
    }
    
    protected function _getToolbar()
    {
        if (is_null($this->_toolbar)) {
            $this->_toolbar = Mage::app()->getLayout()->createBlock('catalog/product_list_toolbar');
        }
        
        return $this->_toolbar;
    }
    
    protected function _getPager()
    {
        return Mage::app()->getLayout()->createBlock('page/html_pager')
                ->setLimit($this->_getToolbar()->getLimit())
                ->setCollection($this->_productCollection);       
    }
    
    /**
     * Create the next and prev rel links. There are 3 possible scenarios:
     * 1. Both next and Prev to be output
     * 2. Only next to be output
     * 3. Only prev to be output
     * 
     * So, decide here what should be output
     *
     * @return Dh_SeoPagination_Model_Paginator
     */
    public function createLinks()
    {
        $pager = $this->_getPager();
        $numPages = count($pager->getPages());

        //Need this to add the links to later on
        $headBlock = Mage::app()->getLayout()->getBlock('head');
        
        //Determine exactly what needs to be output and 
        //add to the head block
        if (!$pager->isFirstPage() && !$pager->isLastPage() && $numPages > 2 ) {
            $headBlock->addLinkRel('prev', $pager->getPreviousPageUrl());
            $headBlock->addLinkRel('next', $pager->getNextPageUrl());
        }
        elseif($pager->isFirstPage() && $numPages > 1) {
            $headBlock->addLinkRel('next', $pager->getNextPageUrl());
        }
        elseif($pager->isLastPage() && $numPages > 1) {
            $headBlock->addLinkRel('prev', $pager->getPreviousPageUrl());
        }
        
        /*if (! Mage::helper('seopagination')->useCanonical()) {
            $this->removeCanonical($headBlock);
        }*/
        
        return $this;
    }
	
	/**
     * Should canonical links be used in conjunction with the next and previous
     * seo links?  If not then remove from headblock here
     * 
     * @param Mage_Page_Block_Html_Head
     * @return Dh_SeoPagination_Model_Paginator
     */
    public function removeCanonical($headBlock)
    {
        $categoryUrl = Mage::registry('current_category')->getUrl();
        $headBlock->removeItem('link_rel', $categoryUrl);
        
        return $this;
    }
}