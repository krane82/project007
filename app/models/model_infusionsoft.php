<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.05.2017
 * Time: 12:22
 */
class Model_Infusionsoft extends Model
{
      private function gettoken()
    {
        $con=$this->db();
        $gettokenQuery="SELECT token FROM token";
        $gettokenResult=mysqli_query($con, $gettokenQuery);
        $result=mysqli_fetch_assoc($gettokenResult);
        $oldToken=$result['token'];
        return $oldToken;
    }
//    function sendLead($p)
//    {
//        session_start();
//        $_SESSION['token']=$this->gettoken();
//        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
//            'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
//            'clientSecret' => 'jDUKwcvSg4',
//            'redirectUri' => 'http://project008/test.php',
//        ));
//            $infusionsoft->setToken(unserialize($_SESSION['token']));
//        if ($infusionsoft->getToken()) {
//            $infusionsoft->sendLead($p);
//        }
//
//    }
    function sendClient($p)
    {
        session_start();
       // $_SESSION['token']=$this->gettoken();
        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
            'clientId' => '5fdsmkxjb6h9k6g2y7nkx2tu',
            'clientSecret' => 'jDUKwcvSg4',
            'redirectUri' => '',
        ));
        $infusionsoft->setToken(unserialize($_SESSION['token']));
        var_dump($infusionsoft->getToken());
        if ($infusionsoft->getToken()) {
            $infusionsoft->contacts->add(array(
                  'Email' => $p['email'],
                  'Phone1'=>$p['phone'],
                  'State'=>$p['state'],
                  '_RoofType'=>$p['roof_type'],
                  '_HouseAge'=>$p['house_age'],
                  '_houseType'=>$p['house_type'],
                  '_Electricity'=>$p['electricity'],
                  '_SystemSize'=>$p['system_size'],
                  '_HouseType'=>$p['house_type'],
//                  'SystemFor'=>$p['system_for'],
                  'PostalCode'=>$p['postcode'],
//                  '_Whatsizesystemareyouconsidering'=>$p['system'],

//                'address'=>$p['address'],
                '_Suburb'=>$p['suburb'],
                'ContactNotes'=>$p['note'],
                '_Source'=>$p['source'],
                'FirstName' => $p['full_name'],
                'LastName' => $p['full_name']
            ));
        }

    }
}