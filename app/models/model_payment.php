<?php

/**
 * Created by PhpStorm.
 * User: Oleg
 * Date: 8/11/2017
 * Time: 4:15 PM
 */
class Model_Payment extends model
{
    public function isPayer($client)
    {    //var_dump($client);
        $con=$this->db();
        $sql="SELECT cref from ezidebit_cref WHERE client_id='$client'";
        $res=$con->query($sql);
        $con->close();
        if($res)
        {
            $result=$res->fetch_assoc();
            return $result['cref'];
        }
        return false;
    }
}