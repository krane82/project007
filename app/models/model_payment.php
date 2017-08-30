<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 09.08.2017
 * Time: 11:18
 */
class Model_Payment extends model
{
public function isPayer($client)
{
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