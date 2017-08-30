<?php
if($data['cref'])
{
    //Old Ezi
//print '<iframe src="https://widget.demo.ezidebit.com.au/account/edit?dk=0CCBD0C4-087D-4F12-1044-2980706769F1&er='.$data['cref'].'" style="width:330px;height:774px"></iframe>';
    //New Ezi
    print '<iframe src="https://widget.ezidebit.com.au/account/edit?dk=67B4468B-8D7F-4463-AF71-DDFE213BB615&er='.$data['cref'].'" style="width:330px;height:774px"></iframe>';
}
else
{
    print '<h2>You need to register eidebit payer. Please, fill this form</h2>';
    print '<iframe style="width:768px; height:800px" src="https://demo.ezidebit.com.au/webddr/Request.aspx?a=3ADDDE8F-F289-4CFE-813E-2152F0E2C953&debits=4&uRef='.$data['client'].'&callback=http://leadpoint.energysmart.com.au/api/ezi&cmethod=post"></iframe>';
}
