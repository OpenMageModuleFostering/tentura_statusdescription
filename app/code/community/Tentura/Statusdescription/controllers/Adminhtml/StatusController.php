<?php

class Tentura_Statusdescription_Adminhtml_StatusController extends Mage_Adminhtml_Controller_Action {

	function getAction(){
	
		$status = $this->getRequest()->getParam('id');
	    $status = Mage::getModel('sales/order_status')->load($status);
	    echo $status->getDescription();
        
	}
	
}

