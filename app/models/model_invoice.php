<?php
include'app/libs/tfpdf/tfpdf.php';
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 16:52
 */

class Model_Invoice extends Model
{
//    private $pdf;
//    public function __construct()
//    {
//        $this->pdf=new tFPDF();
//    }
    public function getMyInvoices($id)
{
    $dir=$_SERVER['DOCUMENT_ROOT'].'/docs/invoices/'.$id.'/';
    $arr=array();
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            $arr[]=$file;
        }
        if ($arr) return $arr;
        return false;
    }
}

    private function getData()
    {
        $con=$this->db();
        $client=$_POST['client'];
       // var_dump($client);
        
        $currentday = date("j");
             if($currentday > 8 && $currentday < 23)
            {
                 $startdate = strtotime("first day of previous month");
                 $start = strtotime("+15 days", $startdate);
                 $end = strtotime('last day of previous month');
            }
           else
           {
                $start = strtotime(date('Y-m-1'));
                $end = strtotime(date('Y-m-15'));
           }
               // var_dump(date("jS F, Y", $start));
               // var_dump(date("jS F, Y", $end));
              // var_dump($start);
              // var_dump($end);
        $sql = "SELECT ler.full_name, led.lead_id, cli.lead_cost, cli.campaign_name, cli.email, cli.phone, cli.id from clients cli left join leads_delivery led on cli.id=led.client_id left join leads_rejection rej on led.id=rej.id left join leads_lead_fields_rel ler on led.lead_id=ler.id";
        $sql.=" where (led.timedate between $start and $end) and (rej.approval=1 or rej.approval=3)";
        // var_dump($sql);
        $res=$con->query($sql);
        // var_dump($res);
        if($res) {
            while($row=$res->fetch_array(MYSQLI_NUM))
            {
                if($row[2]!='0.00')
                {
                 //   var_dump($row);
                    $result[$row[6]][]=$row;
                }
            }
            //var_dump($result);
            return $result;
        }
        return false;
    }


    public function generate()
    {
            $data = $this->getData();
            foreach ($data as $item) {
                $pdf = new tFPDF();
                $currentday = date("j");
                if($currentday > 8 && $currentday < 23)
                {
                    $startdate = strtotime("first day of previous month");
                    $start = strtotime("+15 days", $startdate);
                    $end = strtotime('last day of previous month');
                }
                else
                {
                    $start = strtotime(date('Y-m-1'));
                    $end = strtotime(date('Y-m-15'));
                }
                    $start = (date("d_m_Y", $start));
                    $end = (date("d_m_Y", $end));

            //$today = date('d_m_Y');
            $company_name = $item[0][3];
            $company_phone = $item[0][5];
            $company_email = $item[0][4];
            $invoice_number = '45779';
            $invoice_date = date('d M Y');
            $total_due = '$1,500.00';
            $terms = '7 days from issue';
            $subtotal = '3,477.00';
            $payment = 'Payment information here';
            $gst = '243.39';
            $discount = '243.39';
            $totalDue = '1,500.00';
            $termsAndCond = 'Terms and conditions here';

            $pdf->AddPage('', 'Letter');
           // var_dump(pdf->AddPage('', 'Letter'));

            $pdf->SetAutoPageBreak(auto);
            $pdf->Image('app/libs/tfpdf/template/logo1.png', 13, 11, '65', '15', 'png');
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetDrawColor(213, 90, 36);
            $pdf->SetFillColor(213, 90, 36);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(135, 12);
            $pdf->MultiCell(70, 4, '395 Nepean Highway, Frankston, 3199, VIC
Phone: 1300 850 117
E-mail: support@energysmart.com.au', '', 'L');

            $pdf->SetLineWidth(0.1);
            $pdf->SetDrawColor(50, 186, 217);
            $pdf->Line(10, 30, 205, 30);

            $pdf->SetFont('Times', 'B', 36);
            $pdf->setTextColor(50, 186, 217);
            $pdf->SetXY(135, 47);
            $pdf->MultiCell(70, 4, 'INVOICE', '', 'L');

            $pdf->SetFont('Helvetica', 'B', 13);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(12, 55);
            $pdf->MultiCell(40, 4, 'INVOICE TO:', '', 'L');

            //Here will be company name
            $pdf->SetFont('Helvetica', 'B', 11);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(12, 65);
            $pdf->MultiCell(100, 4, $company_name, '', 'L');

            //Here will be company's phone
            $pdf->Image('app/libs/tfpdf/template/ico_phone.png', 13, 71, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(18, 71);
            $pdf->MultiCell(60, 4, $company_phone, '', 'L');

            //Here will be company's email
            $pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, 77, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(18, 77);
            $pdf->MultiCell(80, 4, $company_email, '', 'L');

            //Here will be invoice number
            $pdf->Image('app/libs/tfpdf/template/ico_nr.png', 137, 65, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(141, 65);
            $pdf->MultiCell(40, 4, 'invoice No:', '', 'L');

            $pdf->SetXY(165, 65);
            $pdf->MultiCell(40, 4, $invoice_number, '', 'L');

            //Here will be invoice Date
            $pdf->Image('app/libs/tfpdf/template/ico_nr.png', 137, 71, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(141, 71);
            $pdf->MultiCell(40, 4, 'invoice Date:', '', 'L');

            $pdf->SetXY(165, 71);
            $pdf->MultiCell(40, 4, $invoice_date, '', 'L');

            //Here will be Total Due
            $pdf->Image('app/libs/tfpdf/template/ico_price.png', 137, 77, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(141, 77);
            $pdf->MultiCell(40, 4, 'Total Due:', '', 'L');

            $pdf->SetXY(165, 77);
            $pdf->MultiCell(40, 4, $total_due, '', 'L');

            //Header of Leads Table
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->setTextColor(255, 255, 255);
            $pdf->SetXY(10, 100);
            $pdf->SetFillColor(143, 145, 147);
            $pdf->MultiCell(97, 8, '   Lead Name', '', 'L', true);
            $pdf->SetXY(107, 100);
            $pdf->MultiCell(33, 8, 'Lead Number', '', 'C', true);
            $pdf->SetXY(140, 100);
            $pdf->MultiCell(30, 8, 'Lead Price', '', 'C', true);
            $pdf->SetFillColor(50, 186, 217);
            $pdf->SetXY(170, 100);
            $pdf->MultiCell(35, 8, 'Total', '', 'C', true);

            //Here will be added all leads in cycle
            $pdf->setTextColor(0, 0, 0);
            $text1 = 'weqgf qwerf';
            $text2 = 'weqgf qwerf';
            $text3 = 'weqgf qwerf';
            $text4 = 'weqgf qwerf';
            $vertical = 109;
            for ($i = 0; $i < count($item); $i++) {
                $pdf->SetFillColor(240, 241, 241);
                $pdf->SetXY(10, $vertical);
                $pdf->SetFont('Helvetica', 'B', 10);
                $pdf->MultiCell(97, 5, '   ' . $item[$i][0], '', 'L', true);
                $pdf->SetXY(107, $vertical);
                $pdf->SetFont('Helvetica', '', 10);
                $pdf->MultiCell(33, 5, $item[$i][1], '', 'C', true);
                $pdf->SetXY(140, $vertical);
                $pdf->MultiCell(30, 5, $item[$i][2], '', 'C', true);
                $pdf->SetFillColor(234, 246, 249);
                $pdf->SetXY(170, $vertical);
                $pdf->MultiCell(35, 5, ($item[$i][2] * 1.1), '', 'C', true);
                $vertical += 6;
                if ($i == 22 || ($i - 22) % 40 == 0) {
                    $vertical = 10;
                    $pdf->AddPage('', 'Letter');
                }
            }

            //Here will be Terms
            //Here will be company's email
            $vertical += 20;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }

            //Here Will be terms
            $pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, $vertical, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(18, $vertical);
            $pdf->MultiCell(80, 4, 'Terms: ' . $terms, '', 'L');

            $subtotal = ($item[0][2] * ($i + 1));
            $gst = ((($item[0][2] * 1.1) - $item[0][2]) * ($i + 1));
            $totaldue = $subtotal + $gst;

            //Here will be Subtotal
            $pdf->SetXY(140, $vertical);
            $pdf->MultiCell(30, 4, 'Subtotal: ', '', 'R');

            $pdf->SetXY(178, $vertical);
            $pdf->MultiCell(60, 4, '$ ' . $subtotal, '', 'L');

            $vertical += 10;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }

            //GST %
            $pdf->SetXY(140, $vertical);
            $pdf->MultiCell(30, 4, 'GST %: ', '', 'R');

            $pdf->SetXY(178, $vertical);
            $pdf->MultiCell(60, 4, '$ ' . $gst, '', 'L');

            $vertical += 20;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }

            //Here Will be terms
            $pdf->Image('app/libs/tfpdf/template/ico_mail.png', 13, $vertical, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(18, $vertical);
            $pdf->MultiCell(80, 4, 'Payment Information: ' . $payment, '', 'L');

            $pdf->SetXY(140, $vertical);
            $pdf->MultiCell(30, 4, 'Discount 0%: ', '', 'R');

            $pdf->SetXY(178, $vertical);
            $pdf->MultiCell(60, 4, '$ ' . $discount, '', 'L');

            $vertical += 10;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }

            //Total Due
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->setTextColor(255, 255, 255);
            $pdf->SetXY(10, 100);
            $pdf->SetFillColor(143, 145, 147);

            $pdf->SetXY(140, $vertical);
            $pdf->MultiCell(30, 8, 'Total Due: ', '', 'R', true);

            $pdf->SetFillColor(50, 186, 217);
            $pdf->SetXY(170, $vertical);
            $pdf->MultiCell(35, 8, '$ ' . $totaldue, '', 'C', true);

            $vertical += 20;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }

            $pdf->SetFillColor(50, 186, 217);
            $pdf->SetXY(170, $vertical);
            $pdf->MultiCell(35, 8, '  Pay Now', '', 'C', true);
            $pdf->Image('app/libs/tfpdf/template/ico_paynow.png', 172, 1 + $vertical, '6', '6', 'png');

            $vertical += 20;
            if ($vertical >= 253) {
                $pdf->AddPage('', 'Letter');
                $vertical = 10;
            }
            //Here Will be Terms & Conditions
            $pdf->Image('app/libs/tfpdf/template/ico_terms.png', 13, $vertical, '4', '4', 'png');
            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(0, 0, 0);
            $pdf->SetXY(18, $vertical);
            $pdf->MultiCell(80, 4, 'Terms & Conditions: ' . $termsAndCond, '', 'L');

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->setTextColor(255, 255, 255);
            $pdf->SetFillColor(143, 145, 147);
            $pdf->SetXY(10, 259);
            $pdf->MultiCell(65, 10, 'support@energysmart.com.au  ', '', 'R', true);
            $pdf->SetXY(75, 259);
            $pdf->MultiCell(65, 10, '   1300 850 117', '', 'C', true);
            $pdf->SetXY(140, 259);
            $pdf->MultiCell(65, 10, 'www.energysmart.com.au   ', '', 'R', true);
            $pdf->Image('app/libs/tfpdf/template/footer_mail.png', 18, 261, '6', '6', 'png');
            $pdf->Image('app/libs/tfpdf/template/footer_phone.png', 90, 261, '6', '6', 'png');
            $pdf->Image('app/libs/tfpdf/template/footer_web.png', 152, 261, '6', '6', 'png');
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/docs/invoices/' . $item[0][6];
            // var_dump($dir);
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
                   // $pdf->output($dir . "/" . $today . ".pdf", "F");
                  $pdf->output($dir . "/".$start."-".$end."-".$company_name.".pdf", "F");
        }
    }
    public function getListOfCurrent($id)
    {

        $data=$this->getMyInvoices($id);
        $dir=__HOST__.'/docs/invoices/'.$id.'/';
        $result='<ul class="list-group">';
        $isPayer = $this->isPayer(md5('leadpoint'.$id.'leadpoint'));
 //  return var_dump($isPayer);
        foreach ($data as $item)
        {
            if ($item=='.' || $item=='..')continue;
        $result.='<li class="list-group-item">';
        if($isPayer)
            {
                $result.=' <button type="button" class="btn btn-success paybut" id = "pay"> Pay !</button>';
            }
            else
            {
                $result.='<span><i>Unregistered payer</i></span>';
            }

            $result.='    <a href="'.$dir.$item.'" target="_blank" style="margin-left:250px;">'.$item.'</a>   <button type="button" value="'.$item.'" class="btn btn-xs btn-danger badge delbut" id="delete">Delete</button></li>';
        }
        $result.='</ul>';
        return $result;
    }
    public function deleteFile()
    {
        $id=$_POST['client'];
        $file=$_POST['file'];
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/docs/invoices/'.$id.'/'.$file)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/docs/invoices/' . $id . '/' . $file);
        return 'deleted';
        }
    return 'File not exsist';
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

    public function getPayment($id) {
        //var_dump($id);
        $payer = new Model_Payment();
        $cref = $payer->isPayer();
        var_dump($cref);
        $currentday = date("j");
        if($currentday > 8 && $currentday < 23)
        {
            $startdate = strtotime("first day of previous month");
            $start = strtotime("+15 days", $startdate);
            $end = strtotime('last day of previous month');
        }
        else
        {
            $start = strtotime(date('Y-m-1'));
            $end = strtotime(date('Y-m-15'));
        }

        $con = $this->db();
        $sql ="SELECT sum(cli.lead_cost) from clients cli left join leads_delivery led on cli.id=led.client_id left join leads_rejection rej on led.id=rej.id left join leads_lead_fields_rel ler on led.lead_id=ler.id where (led.timedate between $start and $end ) and (rej.approval=1 or rej.approval=3)and cli.id = $id";
        //var_dump($sql);
        $res = $con->query($sql);
       // var_dump($res);
        if ($res) {
            $row = $res->fetch_row();
            $num = $row[0];

            $sum = $num * 0.1 + $num;
            var_dump($sum);
            // return $sum;
        //}

            $url = 'https://api.demo.ezidebit.com.au/v3-5/nonpci?wsdl';
            $soap = new SoapClient($url);
            print '<pre>';
            var_dump($soap->__getFunctions());

            $addPayment = array(
                'DigitalKey' => '0CCBD0C4-087D-4F12-1044-2980706769F1',
                'EziDebitCustomerID' => '503361', //Тут ставится cref
                'DebitDate' =>  '2017-08-20', //Тут текущая дата
                'PaymentAmountInCents' => $sum, //Тут сумма на оплату
                'PaymentReference' => 'TestSoap'
            );

            if ($x = $soap->AddPayment($addPayment)) {
                print 'ok';
            } else print 'neud';
            var_dump($x);

        }
    }

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