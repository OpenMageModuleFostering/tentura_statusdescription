<?php
class Tentura_Statusdescription_Block_Adminhtml_Sales_Order_Status_New_Form extends Mage_Adminhtml_Block_Sales_Order_Status_New_Form
{

	/**
     * Prepare form fields and structure
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
    	
        $model  = Mage::registry('current_status');
        $labels = $model ? $model->getStoreLabels() : array();

        $form   = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getData('action'),
            'method'    => 'post'
        ));

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('sales')->__('Order Status Information')
        ));

        $fieldset->addField('is_new', 'hidden', array('name' => 'is_new', 'value' => 1));

        $fieldset->addField('status', 'text',
            array(
                'name'      => 'status',
                'label'     => Mage::helper('sales')->__('Status Code'),
                'class'     => 'required-entry',
                'required'  => true,
            )
        );

        $fieldset->addField('label', 'text',
            array(
                'name'      => 'label',
                'label'     => Mage::helper('sales')->__('Status Label'),
                'class'     => 'required-entry',
                'required'  => true,
            )
        );
        
        $fieldset->addField('description', 'textarea',
            array(
                'name'      => 'description',
                'label'     => Mage::helper('sales')->__('Description'),
                'class'     => '',
                'required'  => false,
            )
        );


        $fieldset = $form->addFieldset('store_labels_fieldset', array(
            'legend'       => Mage::helper('sales')->__('Store View Specific Labels'),
            'table_class'  => 'form-list stores-tree',
        ));

        foreach (Mage::app()->getWebsites() as $website) {
            $fieldset->addField("w_{$website->getId()}_label", 'note', array(
                'label'    => $website->getName(),
                'fieldset_html_class' => 'website',
            ));
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                if (count($stores) == 0) {
                    continue;
                }
                $fieldset->addField("sg_{$group->getId()}_label", 'note', array(
                    'label'    => $group->getName(),
                    'fieldset_html_class' => 'store-group',
                ));
                foreach ($stores as $store) {
                    $fieldset->addField("store_label_{$store->getId()}", 'text', array(
                        'name'      => 'store_labels['.$store->getId().']',
                        'required'  => false,
                        'label'     => $store->getName(),
                        'value'     => isset($labels[$store->getId()]) ? $labels[$store->getId()] : '',
                        'fieldset_html_class' => 'store',
                    ));
                }
            }
        }

        if ($model) {
            $form->addValues($model->getData());
        }
        $form->setAction($this->getUrl('*/sales_order_status/save'));
        $form->setUseContainer(true);
        $this->setForm($form);

//        return parent::_prepareForm();
    }
}
