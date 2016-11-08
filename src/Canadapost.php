<?php
namespace Canadapost;
use Canadapost\Rates;

class Canadapost{
    /**
	 * @var username string
	 **/
    private $username;
    /**
	 * @var password string
	 **/
    private $password;
    /**
	 * @var customerNumber string
	 **/
    private $customerNumber;
    /**
	 * @var env string
     * environment : production or development
	 **/
    private $env;
    /**
	 * @var service_url string
	 **/
    private $service_url;
    /**
	 * @var endpoint string
     * type of request
	 **/
    private $endpoint;
    const PRODUCTION_BASE_URL  = 'https://soa-gw.canadapost.ca';
    const DEVELOPMENT_BASE_URL = 'https://ct.soa-gw.canadapost.ca';
    
    public function __construct($data){
        $this->username       = $data['username'];
        $this->password       = $data['password'];
        $this->customerNumber = $data['customer_number'];
        $this->env            = !empty($data['env']) ? $data['env'] : 'development' ;
        $this->service_url = ($this->env == 'production') ? self::PRODUCTION_BASE_URL : self::DEVELOPMENT_BASE_URL ;
    }
    
    private function send($xmlRequest){
        $curl = curl_init($this->service_url.$this->endpoint); // Create REST Request
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlRequest);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/vnd.cpc.ship.rate-v3+xml', 'Accept: application/vnd.cpc.ship.rate-v3+xml'));
        $curl_response = curl_exec($curl); // Execute REST Request
        if(curl_errno($curl)){
        	echo 'Curl error: ' . curl_error($curl) . "\n";
        }
        //echo 'HTTP Response Status: ' . curl_getinfo($curl,CURLINFO_HTTP_CODE) . "\n";
        curl_close($curl);
        return $curl_response;
    }
    
    public function getXml($curl_response){
        // Example of using SimpleXML to parse xml response
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/','',$curl_response) . '</root>');
        /*if (!$xml) {
        	echo 'Failed loading XML' . "\n";
        	echo $curl_response . "\n";
        	foreach(libxml_get_errors() as $error) {
        		echo "\t" . $error->message;
        	}
        }*/
        return $xml;
    }
    
    public function getRawData($curl_response){
        return $curl_response;
    }
    
    public function getRates($data){
        $data['customer_number'] = $this->customerNumber;
        $this->rates    = new Rates();
        $this->endpoint = $this->rates->endpoint;
        $xmlRequest     = $this->rates->build($data);//
        return $this->getXml($this->send($xmlRequest));
    }
    
}
