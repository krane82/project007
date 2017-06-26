<?php
class Model_Registration extends Model {

    public function store_client($data, $last_id)
    {
        $con = $this->db();

        if ($con->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        $query = "INSERT INTO `clients` (
                    `id`, 
                    `campaign_name`, 
                    `email`, 
                    `full_name`, 
                    `lead_cost`, 
                    `phone`, 
                    `address`, 
                    `city`, 
                    `state`, 
                    `country`, 
                    `status`, 
                    `abn`, 
                    `authorised_person`, 
                    `position`, 
                    `name_on_card`, 
                    `credit_card_number`, 
                    `expires_mm`, 
                    `expires_yy`, 
                    `cvc`) 
                    VALUES (
                        '".$last_id."',
                        '".$data['campaign_name']."',
                        '".$data['email']."',
                        '".$data['full_name']."',
                        '0',
                        '".$data['phone']."',
                        '".$data['address']."',
                        '".$data['city']."',
                        '".$data['state']."',
                        '".$data['country']."',
                        '1',
                        '".$data['abn']."',
                        '".$data['authorised_person']."',
                        '".$data['position']."',
                        '".$data['name_on_card']."',
                        '".$data['credit_card_number']."',
                        '".$data['expires_mm']."',
                        '".$data['expires_yy']."',
                        '".$data['cvc']."'
                    );";
        $result = $con->query($query);
        if( $result ) {

            $xero_id = '';
            $xero_name = '';

            $sql1 = 'INSERT INTO `clients_billing`';
            $sql1.= '(id, xero_id, xero_name)';
            $sql1.= " VALUES ($last_id, '$xero_id','$xero_name')";

            if($con->query($sql1)) $billing_added = 1;
            return('Client info saved!');

        } else {
            $con->close();
            echo('Error');
            return FALSE;
        }

    }

    public function store_user($data)
    {
        $con = $this->db();

        if ($con->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        $password = md5($data['password']);
        $query = "INSERT INTO `users` (
                    `email`,
                    `password`,
                    `level`,
                    `active`,
                    `full_name`) 
                    VALUES (
                        '".$data['email']."',
                        '".$password."',
                        '3',
                        '1',
                        '".$data['full_name']."'
                    );";
        $result = $con->query($query);
        if( $result ) {

            $last_id = $con->insert_id;

            $states_filter = '';
            $postcodes  = '';
            $weeekly = 10;

            $sql3 = 'INSERT INTO `clients_criteria`';
            $sql3.= '(id, weeekly, states_filter, postcodes )';
            $sql3.= " VALUES ($last_id, $weeekly, '$states_filter', '$postcodes' )";

            if($con->query($sql3)) $criteria_added = 1;
            return($last_id);
        } else {
            $con->close();
            echo('Error');
            return FALSE;
        }
    }

    public function get_email_client($last_id)
    {
        $con = $this->db();

        if ($con->connect_errno) {
            printf("Connect failed: %s\n", $con->connect_error);
            exit();
        }
        $query = "SELECT `email` FROM `users` 
                    WHERE `id` = ".$last_id.";";
        $result = $con->query($query);
        if( $result ) {
            while($row = $result->fetch_assoc()){
                return trim($row['email']);
            }

        } else {
            $con->close();
            echo('Error email');
            return FALSE;
        }

    }

    public function store_file($id, $file_id)
    {
        $dir = _MAIN_DOC_ROOT_.'/app/api/tmp/E'.md5($file_id);
        // echo $dir;
        $filename1 = $dir.'/.pdf';
        $filename2 = $dir.'/1.pdf';
        $dirNew = _MAIN_DOC_ROOT_.'/docs/terms/'.$id;
        if(! is_dir($dirNew))
        {
            mkdir ($dirNew, 0755);
        }
        $filename1New = $dirNew.'/signed_terms_info.pdf';
        $filename2New = $dirNew.'/signed_terms.pdf';
        copy($filename1, $filename1New); 
        unlink($filename1); 
        copy($filename2, $filename2New); 
        unlink($filename2);
        rmdir($dir);
    }
}
