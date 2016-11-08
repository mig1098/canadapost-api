<?php
namespace Canadapost;

class Rates{
    public $endpoint = '/rs/ship/price';
    public function __construct(){

    }
    
    public function build($data){
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v3">
  <customer-number>{$data['customer_number']}</customer-number>
  <parcel-characteristics>
    <weight>{$data['weight']}</weight>
  </parcel-characteristics>
  <origin-postal-code>{$data['from_zip']}</origin-postal-code>
  <destination>
    <domestic>
      <postal-code>{$data['to_zip']}</postal-code>
    </domestic>
  </destination>
</mailing-scenario>
XML;
        return $xml;
    }
    
    
}
