# Sols_Invoicepdf

Extension that lets frontend customers send an automated email with an invoice pdf to an extra email address. 

For instance in a B2B shop (or B2C) frontend customers may want to send their invoices straight to their accountant. This extension allows frontend customers to add an email address to which an automated Invoice is sent (including a PDF of that invoice).

Please note this extension was designed for Magento 1.9.x

Create an extra transactional email template for the invoice pdf and note the ID of the template. Clone or download the code and install in your Magento 1.9.x shop. Login and clear all cache.

Go to app\code\local\Sols\Invoicepdf\Model\Observer.php and replace the tempalte id in line 42 like so:
 $templateId 	= 24; // Please fill in your template id here!
 
Integrate the frontend files with your theme if needed. Clear cache once more and login as a customer on the frontend. You should see the possibility to enter a second email address under My account -> Contact information -> Edit 
