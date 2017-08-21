<h3>Dashboard</h3>
<div class="row">
<form id="dashboard">
    <div class="form-group col-lg-4 col-sm-4 col-xs-12">
        <label for="datepicker">Select Date Range</label>
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm st form-control" name="begin" value="<?php print date('m/01/Y')?>"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm en form-control" name="end" value="<?php print date('m/d/Y')?>"/>
        </div>
    </div>
    <div class="form-group col-lg-4 col-sm-4 col-xs-12">
        <label for="selectClient">Select Date Range</label>
        <div>
        <select name="client" id="selectClient" class="form-control">
<!--            <option selected disabled></option>-->
            <?php
            foreach ($data["campaigns"] as $key => $value) {
                echo "<option value='" . $value['id'] . "'>" . $value['name'] . "</option>";
            }
            ?>
        </select>
        </div>
    </div>
    <div class="form-group col-lg-4 col-sm-4 col-xs-12">
        <label>Press to generate reports</label>
        <div>
        <input type="submit" class="btn btn-primary btn-sm" value="Generate reports">
        </div>
        </div>
</form>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
    <table id="dashResult" class="display table table-condensed table-striped table-hover table-bordered clients responsive pull-left dataTable no-footer align-center" style="word-wrap:break-word; width:100%">
        <thead>
        <tr>
            <th style="width: 16.6%">total leads</th>
            <th style="width: 16.6%">total rejections</th>
            <th style="width: 16.6%">average sales per lead</th>
            <th style="width: 16.6%">gross income</th>
            <th style="width: 16.6%">cost </th>
            <th style="width: 16.6%" colspan="2">profit max/min</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td id="dashLeadsCome"></td>
            <td id="dashRejections"></td>
            <td id="SalesPerLead"></td>
            <td id="grossIncome"></td>
            <td id="cost"></td>
            <td id="profitMax"></td>
            <td id="profitMin"></td>
        </tr>
        </tbody>
    </table>
<!--    <div class="col-lg-2">total leads</div>-->
<!--    <div class="col-lg-2">total rejections</div>-->
<!--    <div class="col-lg-2">average sales per lead</div>-->
<!--    <div class="col-lg-2">gross income</div>-->
<!--    <div class="col-lg-2">cost</div>-->
<!--    <div class="col-lg-2">profit</div>-->
    </div>
</div>

<h3>Planning</h3>

<form id="fact">
    <div class="form-group">
        <label for="datepicker">Select Date Range</label>
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm st form-control" name="start" value="<?php print date('m/01/Y')?>"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm en form-control" name="end" value="<?php print date('m/d/Y')?>"/>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Show Leads Count">
    </div>
</form>
<div class="table-responsive">
<table id="plan" class="display table table-condensed table-striped table-hover table-bordered pull-left">
    <thead>
    <tr>
        <th>Affiliate</th>
        <th colspan="2">NSW<br>fact | plan</th>
        <th colspan="2">QLD<br>fact | plan</th>
        <th colspan="2">SA<br>fact | plan</th>
        <th colspan="2">TAS<br>fact | plan</th>
        <th colspan="2">VIC<br>fact | plan</th>
        <th colspan="2">WA<br>fact | plan</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['plans'] as $item=>$key)
{
   print '<tr class="campaign" id="'.$key['id'].'">';
    print '<td>'.$item.'</td>';
    print '<td style="width:6%">'.$key['NSW']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="NSW" style="width:2em" value="'.$key['NSW']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['QLD']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="QLD" style="width:2em" value="'.$key['QLD']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['SA']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="SA" style="width:2em" value="'.$key['SA']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['TAS']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="TAS" style="width:2em" value="'.$key['TAS']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['VIC']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="VIC" style="width:2em" value="'.$key['VIC']['plan'].'"></td>';
    print '<td style="width:6%">'.$key['WA']['count'].'</td><td style="width:6%"><input type="text" maxlength="3" class="WA" style="width:2em" value="'.$key['WA']['plan'].'"></td>';
    print '</td>';
    print '</tr>';
}
?>
    </tbody>
</table>
</div>
<script>
    $(document).ready(function () {
    var campaign=$('.campaign');
    campaign.bind("input", function(e) {
        if (e.target.value.match(/[^0-9]/g)) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        }
    });
    campaign.on('change',function(e){
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/campaigns/plans_update/' ?>',
            data: { 'campaign':this.id, 'state': event.target.className, 'value':event.target.value},
            success: function (data) {
            }
        });
    });
     var form=$('#fact');
     form.submit(function(e) {
     e.preventDefault();
        var start = form.find('input[name=start]').val();
        var end = form.find('input[name=end]').val();
        $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . '/campaigns/planing_ajax/' ?>',
            data: { 'start':start, 'end':end},
            success: function (data) {
               var resp=JSON.parse(data);
                for (var i=0;i<campaign.length;i++){
                    campaign[i].innerHTML=resp[i];
                }
            }
        });
    });    
    $('.input-daterange').datepicker({
        multidate: "true"
    });
    });

    //Here is the script for dashboard jeneration
    $('#dashboard').submit(function(e){
        e.preventDefault();
        $('#dashLeadsCome').html('Counting');
        $('#dashRejections').html('Counting');
        $('#SalesPerLead').html('Counting');
        $('#grossIncome').html('Counting');
        $('#cost').html('Counting');
        $('#profitMax').html('Counting');
        $('#profitMin').html('Counting');
       // $('#dashResult').html('Wait please. . . generating invoice');
        var data=$(this).serialize();
        $.ajax(
            {
                type:"POST",
                url: "<?php echo __HOST__ . '/campaigns/getDashboard' ?>",
                data:data,
                success:function(data){
                    console.log(data);
                    var respond=JSON.parse(data);
                    var averageSalesPerLead=respond.averageSalesPerLead;
                    var grossIncome=respond.grossIncome;
                    var profitMax=respond.profitOnlyAccepted;
                    var profitMin=respond.profitWithPendings;
                    $('#dashLeadsCome').html(respond.totalLeadsCome);
                    $('#dashRejections').html(respond.totalRejections);
                    averageSalesPerLead?$('#SalesPerLead').html(averageSalesPerLead.toFixed(1)):$('#SalesPerLead').html(0);
                    grossIncome?$('#grossIncome').html(grossIncome.toFixed(2)):$('#grossIncome').html(0);
                    profitMax?$('#profitMax').html(profitMax.toFixed(2)):$('#profitMax').html(0);
                    profitMin?$('#profitMin').html(profitMin.toFixed(2)):$('#profitMin').html(0);
                    $('#cost').html(respond.cost);
                }
            }
        )
    })
    //
</script>