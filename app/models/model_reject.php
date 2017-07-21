<?php
class Model_Reject extends Model
{
    public function delete()
    {
        $id = $_POST['id'];
        $client_id = $_POST['client_id'];

        $sql ="DELETE FROM `leads` WHERE `id`='$id'";
        $sql1="DELETE FROM `leads_delivery` WHERE `id`='$id'";
        $sql2="DELETE FROM `leads_lead_fields_rel` WHERE `id`='$id'";
        $sql3="DELETE FROM `leads_rejection` WHERE `lead_id`='$id'";

        $con = $this->db();
        $res = $con->query($sql);
        $res1 = $con->query($sql1);
        $res2 = $con->query($sql2);
        $res3 = $con->query($sql3);

    }

    public function getLead()
    {

    }

    public function rejectLead()
    {
        $id = $_POST['id'];
        $sql = "UPDATE `leads_rejection`";
        $sql.= " SET approval='1'";
        $sql.= " WHERE lead_id='$id'";
        $con = $this->db();
        $res = $con->query($sql);
    }

    public function approveReject()
    {
        $id = $_POST['id'];
        $client_id = $_POST['client_id'];
        $sql = "UPDATE `leads_rejection`";
        $sql.= " SET approval='0'";
        $sql.= " WHERE lead_id='$id'";
        $con = $this->db();
        $res = $con->query($sql);
        $sql1 = "select camp_id from leads_rejection where lead_id='$id'";
        $res = $con->query($sql1);
        if ($res) $result = $res->fetch_assoc();
        // var_dump($result['camp_id']);die();
        $this->action_autoreroute_lead($client_id, $result['camp_id']);
    }

    public function action_autoreroute_lead($client_id, $camp_id) 
    {
        //getting all postcodes for our client
        $con = $this->db();
        $sql = "SELECT postcodes FROM client_campaigns WHERE id = $camp_id";
        // var_dump($sql);die();
        $query = $con->query($sql);
        // var_dump($query);die();
        $result = $query->fetch_array();
        
        $data = explode(",", $result[0]);
        // var_dump($data);die();


        //getting all leads
        $sql = "SELECT postcode, id FROM leads_lead_fields_rel ORDER BY id DESC";
        $query = $con->query($sql);
        while($row = $query->fetch_array(MYSQLI_NUM)) {
            $result2[] = $row;
        }
        // var_dump($result2);die();
        //die;
        
        //getting all leads that fulfill our clients postcode criteria
        foreach($result2 as $array) {
            if(in_array($array[0], $data)) {
                $seeking_lead[] = $array[1];
            }             
        }
        // var_dump($seeking_lead);       die;

        //getting all leads that have been sent to client
        $sql = "SELECT lead_id FROM leads_delivery WHERE client_id = $client_id ORDER BY lead_id DESC";
        $query = $con->query($sql);
        while($row = $query->fetch_array(MYSQLI_NUM)) {
            $result3[] = $row;
        }
        // var_dump($result3);die;    

        foreach($result3 as $array) {
            foreach($seeking_lead as $lead) {
                if($lead !== $array[0]) {
                    $lead_id[] = $lead;
                } else {
                    $lead_repeat[] = $lead;
                }
            }
        }

        //var_dump($lead_repeat);

        foreach($lead_id as $id) {
            if(in_array($id, $lead_repeat)) {
                //
            } else {
                $wanted_id[] = $id;
            }
        }

        foreach($wanted_id as $id) {
            $sql = "SELECT lead_id FROM leads_delivery WHERE lead_id = $id ORDER BY lead_id DESC";
            $query = $con->query($sql);
            while($row = $query->fetch_array(MYSQLI_NUM)) {
                $result4[] = $row;                
            }
            if(count($result4) < 4) {
                $reroute_id = $id;
                break;
            }
        }

        //var_dump($reroute_id);
        //var_dump($client_id);        

        if(is_null($reroute_id)) {
            echo 'cannot send, all possible leads already sent!';
        } else {
            echo 'we can send';
            // var_dump($camp_id); die();
            include 'app/models/model_leads.php';
            $model = new Model_leads();
            $model->senOneLead($client_id, $reroute_id, $camp_id, true);
            echo 'done';
        }

        
    }




}