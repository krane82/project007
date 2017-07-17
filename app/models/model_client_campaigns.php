<?php
include_once 'model_profile.php';
class Model_Client_Campaigns extends Model
{



    public function getMyCampaigns($user_id)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "select * from `client_campaigns` where `client_id` = " . $user_id." order by `camp_status` desc";

        if ($result = $con->query($query)) {

            while ($row = $result->fetch_assoc()) {

                $data[] = $row;

            }

            return $data;

        }

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function getClientStatus($user_id)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "select status from `clients` where `id` = " . $user_id;

        if ($result = $con->query($query))

        {

            if ($row = $result->fetch_assoc()) $status = $row['status'];

            return $status;

        }

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function add_new_campaign($client_id, $camp_name, $camp_weekly, $postcodes, $coords, $camp_radius, $camp_nearest)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "insert into `client_campaigns` (`camp_name`, `weekly`, `client_id`, `postcodes`, `camp_status`, `coords`, `radius`, `nearest`) values('" . $camp_name . "', '" . $camp_weekly ."', '" . $client_id . "', '" . $postcodes . "', '0', '".$coords."', '".$camp_radius."', '".$camp_nearest."')";

        if ($result = $con->query($query)) {

            if ($this->getAllClCampPostcodes($client_id))

                return $con->insert_id;

            else

                return false;

        } 

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function edit_campaign($camp_id, $camp_name, $camp_weekly, $postcodes, $coords,  $camp_radius, $camp_nearest, $client_id)

    {
		session_start();
        $prof=new Model_Profile();
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}
        //Here I added the function that gets data to email sending
        $sql="SELECT cam.postcodes , cam.camp_name, cam.weekly,
        cam.radius, cam.nearest, cli.campaign_name FROM `client_campaigns` cam left join
        clients cli on cam.client_id=cli.id
        WHERE cam.id='".$camp_id."'";
        $res=$con->query($sql);
        if($res)
        {
         $row=$res->fetch_assoc();
        }
        //End of email sending function
        $query = "update `client_campaigns` set       
      `postcodes` ='" . $postcodes . "', `coords`='".$coords."',
       `camp_name` ='" . $camp_name . "', `weekly` ='" . $camp_weekly . "',
        `radius`='".$camp_radius."', `nearest`='".$camp_nearest."' 
        where `id` = '" . $camp_id."'";
		$p=[
            'camp_nam'=>$camp_name,
            'weekly'=>$camp_weekly,
            'radius'=>$camp_radius,
            'nearest'=>$camp_nearest,
            'postcodes'=>$postcodes
        ];
        $before=[[
            'camp_nam'=>$row['camp_name'],
            'weekly'=>$row['weekly'],
            'radius'=>$row['radius'],
            'nearest'=>$row['nearest'],
            'postcodes'=>$row['postcodes']
            ],['c'=>'true','k'=>$row['campaign_name']]
        ];

        $before=serialize($before);
        $before=urlencode($before);
        //return var_dump($before);

        if ($result = $con->query($query)) {
            $prof->UserChangeNotif($p,$before);

            if ($this->getAllClCampPostcodes($client_id))  return true;

            else return false;

        }

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function delete_campaign($camp_id, $client_id)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "update `client_campaigns` set `camp_status` = '0' where `id` = " . $camp_id;

        if ($result = $con->query($query)) {

            if ($this->getAllClCampPostcodes($client_id))  return true;

            else return false;

        }

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function activate_campaign($camp_id, $client_id)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "update `client_campaigns` set `camp_status` = '1' where `id` = " . $camp_id;

        if ($result = $con->query($query)) {

            if ($this->getAllClCampPostcodes($client_id)) 

                return true;

            else

                return false;

        } 

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    public function getAllClCampPostcodes($client_id)

    {

        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



        $query = "select postcodes from `client_campaigns` where `client_id` = ". $client_id." and `camp_status` = 1";

        if ($result = $con->query($query))

        {

            while ($row = $result->fetch_assoc()) {

                $postcodes[] = $row;

            }

        }

        else {die ("database access failed1: " . $con->error);}

        

        $postcodes_str = '';

        foreach ($postcodes as $key=>$value) {

            if ($key != 0) $postcodes_str .= ',';

            $postcodes_str .= $value['postcodes'];

        }

        $query = "update `clients_criteria` set `postcodes` = '".$postcodes_str." 'where `id` = ". $client_id;

        if ($result = $con->query($query)) {

            return $result;

        } 

        else {die ("database access failed: " . $con->error);}

        $con->close();

    }



    // public function send_leads($client_id, $camp_id)

    // {

    //     $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    //     if ($con->connect_errno) {printf("connect failed: %s\n", $con->connect_error);exit();}



    //     $query = "insert into `client_campaign_cron` (`camp_id`, `client_id`) values('" . $camp_id . "', '" . $client_id ."')";

    //     if ($result = $con->query($query))

    //     {

    //         // return $result;

    //     } 

    //     else {die ("database access failed: " . $con->error);}

    //     $con->close();

    // }



    public function getAllClients()

    {

        $con = $this->db();

        $clients = array();

        $sql = "SELECT `id`, `campaign_name` FROM `clients`";

        $res = $con->query($sql);

        while ($row = $res->fetch_assoc()) {

            $clients[] = $row;

        }

        $con->close();

        return $clients;

    }



    public function getListOfCampOneClient($client_id)

    {

        $con = $this->db();

        $clients = array();

        $sql = "SELECT camp.*, c.email, c.campaign_name FROM `client_campaigns` camp left join `clients` c on camp.client_id = c.id where c.id=$client_id order by camp.camp_status desc";

        $res = $con->query($sql);

        while ($row = $res->fetch_assoc()) {

            $clients[] = $row;

        }

        $con->close();

        return $clients;

    }

    public function getCurrentCampaign($id)
    {
        $con=$this->db();
        $sql="SELECT * FROM client_campaigns WHERE id='".$id."'";
        $res=$con->query($sql);
        $result=$res->fetch_assoc();
        return $result;
    }

}



