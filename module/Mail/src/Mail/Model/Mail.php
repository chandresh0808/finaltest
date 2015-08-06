<?php

namespace Mail\Model;

class Mail extends \Application\Model\AbstractCommonServiceMutator {

    protected $_fromEmail;
    /**
     * This Constructor is public.
     * 
     * @return  null
     */
    public function __construct()
    {
        $this->_fromEmail = "support@auditcompanion.biz";
    }

    /**
     * Function sendMail
     *
     * to send mail
     * 
     * @param array $content - mail content
     * @param array $subject - subject of email
     * @param array $to      - to address for email
     * @param array $from    - from address for email
     * @param array $cc      - cc address for email
     * @param array $bcc     - bcc id's for email
     *  
     * @return boolean  
     *
     */
    public function sendMail_normal(
        $template, $to = '', $from = '', $cc = '', $bcc = ''
    ) {
                
        if ($from == '') {
            $from   = $this->_fromEmail;
        }
        $headers = "From:" . $from . "\r\n";
       
        try {                        
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset: utf8\r\n";           
            return mail($to, $template['subject'], $template['message'], $headers);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /*
     * Send mail using Amazon SES
     */
    public function sendMail($template, $to = '', $from = '', $cc = '', $bcc = '') {
        
        $awsService = $this->getAwsService();
        
        if ($from == '') {
            $from   = $this->_fromEmail;
        }
                       
        try {                                   
            $awsSesMailInputArray['Source'] = $from;
            $awsSesMailInputArray['Destination'] = array('ToAddresses' => array(trim($to)));
            $awsSesMailInputArray['Message'] = array('Subject' => array('Data' => $template['subject']),           
                'Body' => array('Text' => array('Data' => ''),
                'Html' => array('Data' => $template['message'])));
            $sesClient = $awsService->get('ses');
            $response = $sesClient->sendEmail($awsSesMailInputArray);
            return $response;
        } catch (Exception $e) {
            return false;
        }
        
    }
    
    protected $_whiteListEmailArray; 
    public function setWhiteListEmailArray($emailArray)
    {
        $this->_whiteListEmailArray = $emailArray;
        return $this;
    }
    public function getWhiteListEmailArray()
    {
        
        return $this->_whiteListEmailArray;
    }
    
    
   
}
