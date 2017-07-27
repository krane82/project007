<?php
include'app/libs/tfpdf/tfpdf.php';
/**
* Created by PhpStorm.
* User: Admin
* Date: 11.04.2017
* Time: 16:52
*/
class Model_Terms extends Model
{
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


    public function getMyTerms($id)
    {
        $dir=$_SERVER['DOCUMENT_ROOT'].'/docs/terms/'.$id.'/';
        $arr=array();
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                $arr[]=$file;
            }
            if ($arr) return $arr;
            return false;
        }
    }

    public function getListOfCurrent($id)
    {
        $data=$this->getMyTerms($id);
        $dir=__HOST__.'/docs/terms/'.$id.'/';
        $result= '<table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Terms and Conditions copy</th>
                            <th>Docusign Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>';
                foreach ($data as $item)
                {
                    if ($item=='.' || $item=='..')continue;
                    $result.='<td><a href="'.$dir.$item.'" target="_blank">'.$item.'</a></td>';
                }
                $result.= '</tr></tbody></table>';
                return $result;
            }

    public function count_notifications($id) {
        $con = $this->db();
        $sql = "SELECT count(*) FROM `leads_delivery` WHERE client_id = $id AND seen = 0";
        $res = $con->query($sql);
        if ($res) {
            $row = $res->fetch_row();
            $num = $row[0];
            return $num;
        }
    }

    public function get_new_notifications($id) {
        $con = $this->db();
        $sql = "SELECT leads_delivery.timedate, leads_lead_fields_rel.suburb FROM leads_delivery JOIN leads_lead_fields_rel ON leads_delivery.lead_id = leads_lead_fields_rel.id WHERE leads_delivery.client_id = $id AND seen = 0 ORDER BY leads_delivery.timedate DESC LIMIT 8";
        $res = $con->query($sql);
        if ($res) {
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                $array[] = $row;
            }
            return $array;
        }
    }

}