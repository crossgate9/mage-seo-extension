<?php
/**
 * MagentoGarden
 *
 * @category    controller
 * @package     magentogarden_seosuite
 * @copyright   Copyright (c) 2012 MagentoGarden Inc. (http://www.magentogarden.com)
 * @version		1.0
 * @author		MagentoGarden (coder@magentogarden.com)
 */

class MagentoGarden_Seosuite_IndexController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout();     
		$this->renderLayout();
	}
}