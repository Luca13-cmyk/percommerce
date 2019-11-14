<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Address extends Model 
{   

       public static function getCEP($nrcep)
       {
           $nrcep = str_replace("-", "", $nrcep);
           //https://viacep.com.br/ws/01001000/json/

           $ch = curl_init();

           curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");

           curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

           $data = json_decode(curl_exec($ch), true);

           curl_close($ch);


           return $data;
       }



}


?>