<?php
include_once("Mage/Adminhtml/controllers/Sales/OrderController.php");
class Tentura_Statusdescription_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    
    /**
     * Add order comment action
     */
    public function addCommentAction()
    {
    	if ($order = $this->_initOrder()) {
            try {
                $response = false;
                $data = $this->getRequest()->getPost('history');
                $notify = isset($data['is_customer_notified']) ? $data['is_customer_notified'] : false;
                $visible = isset($data['is_visible_on_front']) ? $data['is_visible_on_front'] : false;

                $comment = $data['comment'];
                
            	preg_match_all('/{{[0-9a-zA-Z_.,]*}}/', $comment, $matches);
				foreach ($matches as $match){
    				foreach ($match as $value){
    					$codeValue = $order->getData(str_replace('}}','',str_replace('{{','',$value)));
    					if ( $codeValue ){
    						$comment = str_replace($value, $codeValue, $comment);
    					}
    				}
				}
                
                $order->addStatusHistoryComment($comment, $data['status'])
                    ->setIsVisibleOnFront($visible)
                    ->setIsCustomerNotified($notify);

                $comment = trim(strip_tags($comment));

                $order->save();
                
				$order->sendOrderUpdateEmail($notify, nl2br($comment));

                $this->loadLayout('empty');
                $this->renderLayout();
            }
            catch (Mage_Core_Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $e->getMessage(),
                );
            }
            catch (Exception $e) {
                $response = array(
                    'error'     => true,
                    'message'   => $this->__('Cannot add order history.')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }
    
}
