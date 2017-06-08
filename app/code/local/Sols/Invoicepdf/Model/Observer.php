<?php
/*
 * SolsWebdesign.nl
 *
 * @category    Sols
 * @copyright   Copyright (c) 2017 Sols.
 *
 */
class Sols_Invoicepdf_Model_Observer
{
    public $logging = false;  // please set to true if you need logging
    public $logfile;

    function __construct() {
        $date           = date('Y-m-d');
        $this->logfile  = 'sols_invoicepdf_obs_'.$date.'.log';
    }

    /**
     *
     * @param Varien_Event_Observer $observer
     * @throws Exception
     */
    public function sendPdfEmail(Varien_Event_Observer $observer)
    {
        $event          = $observer->getEvent();
        $invoice        = $event->getInvoice();
        $order          = $invoice->getOrder();
        $customer       = Mage::getModel('customer/customer')->load($order->getCustomerId());
        $invPdfEmail    = $customer->getInvoicepdf();
        $name           = $customer->getName();
        if($this->logging){
            Mage::log('sendPdfEmail customer name '.$name, null, $this->logfile);
            Mage::log('sendPdfEmail customer invoicePDF email '.$invPdfEmail, null, $this->logfile);
        }

        $name           = $customer->getName();
        $vars           = array();

        $mailTemplate 	= Mage::getModel('core/email_template');
        $translate  	= Mage::getSingleton('core/translate');
        $templateId 	= 1; // Please fill in your template id here!
        $templateCol    = $mailTemplate->load($templateId);
        $templateData 	= $templateCol->getData();
        if(!empty($templateData))
        {
            $templateId 	= $templateData['template_id'];
            $mailSubject 	= $templateData['template_subject'];
            $from_email 	= Mage::getStoreConfig('trans_email/ident_general/email'); // fetch sender email
            $from_name 		= Mage::getStoreConfig('trans_email/ident_general/name');  // fetch sender name
            $sender 		= array('name'  => $from_name, 'email' => $from_email);
            $storeId        = Mage::app()->getStore()->getId();
            $model          = $mailTemplate->setReplyTo($sender['email'])->setTemplateSubject($mailSubject);
            $pdf            = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
            $file           = $pdf->render();
            $attachment     = $mailTemplate->getMail()->createAttachment($file);
            $attachment->type			= 'application/pdf';
            $attachment->disposition	= Zend_Mime::DISPOSITION_INLINE;
            $attachment->encoding		= Zend_Mime::ENCODING_BASE64;
            $attachment->filename		= 'invoice.pdf';

            $model->sendTransactional($templateId, $sender, $invPdfEmail, $name, $vars, $storeId);

            if (!$mailTemplate->getSentSuccess()) {
                throw new Exception();
            }
            $translate->setTranslateInline(true);
        }
    }
}