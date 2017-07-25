<!-- .panel panel-white -->

<div class="panel panel-white ">

<div class="col-md-12">

  <div class="row">

  <div class="table-responsive">

      <table class="table display responsive no-wrap" id="approvals">

        <thead>

        <tr>

          <th>ID</th>

          <th>Client</th>

          <th>Receiving date</th>

          <th>Rejection date</th>

          <th>Reason  </th>

          <th>Note</th>

          <th>Decline Reason</th>

          <th>Status</th>

          <th>Audiofile</th>

          <th>View</th>

          <th>Action</th>

        </tr>

        </thead>
<tbody>
<?php
$str='';
foreach($data['approvals'] as $item) {
$str.= '<tr><td data-order="'.$item[0].'">';
  $str.=$item[0];
  if ($item[11] == NULL) {

    $str.='<div><button type="button" data-act="conversation" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalka" value="' . $item[9] . '">open</button></div>';

  } else {
    $whoSeen = explode(',', $item[11]);

    if (!in_array($_SESSION['user_id'], $whoSeen)) {

      $str.='<div><button type="button" data-act="conversation" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalka" value="' . $item[9] . '">open</button></div>';

    } else {

      $str.='<div><button type="button" data-act="conversation" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalka" value="' . $item[9] . '">open</button></div>';

    }
  }
  $str.='</td><td>'.$item['1'].'</td>';
  $str.='<td>'.$item[2].'</td>';
  $str.='<td>'.$item[3].'</td>';
  $str.='<td>'.$item[4].'</td>';
  $str.='<td>'.$item[5].'</td>';
  $str.='<td>'.$item[6].'</td>';
  switch ($item[7]) {
    case 0:
      $str.="<td class='status'><span class=\"bg-primary pdfive\">Reject accepted</span></td>";
      break;
    case 2:
      $str.="<td class='status'><span class=\"bg-warning\">Requested to Reject</span></td>";
      break;
    case 3:
      $str.="<td class='status'><span class=\"bg-danger pdfive\">Reject not Approved</span></td>";
      break;
    case 4:
      $str.="<td class='status'><span class=\"bg-info pdfive\">More info required</span></td>";
      break;
    default:
  }
  $str.='<td>';
  if($item['8']) {
    $str.="<td><form method='POST' action='". __HOST__ ." / docs / audios / download . php'>
            <input type='hidden' name='file' value='".basename($item['8'])."'>
            <input type='hidden' name='folder' value='".$item[10]."'>
            <input type='submit' class='btn btn - xs btn - success' value='Download file'></form><br><br>
            <button type='button' class='btn btn - xs btn - danger' onclick='delbutfile(this)' value='".$item['8']."'>Delete file</button></td></tr>";
  }
  $str.='</td>';
  $str.="<td><a href='#' class='viewLeadInfo btn btn-info' attr-id='$item[0]' data-toggle=\"modal\" data-target=\"#LeadInfo\">View</a></td>";
  $str.='<td><a href="#" role="button" onclick="rejectLead('.$item[0]. ', '. $item[10] .');" class="btn btn-small btn-danger hidden-tablet hidden-phone" data-toggle="modal" data-target="#disapprove" data-original-title="">
						    Disapprove Request </a><br>
						    <a href="#" role="button" onclick="acceptLead('.$item[0]. ', '. $item[10] .',this);" class="btn btn-small btn-success hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Approve Request</a><br>
						    <a href="#" role="button" onclick="moreInfo('.$item[0]. ', '. $item[10] .',this);" class="btn btn-small btn-info hidden-tablet hidden-phone" data-toggle="modal" data-original-title="">
						    Request More Info</a></td>';
  $str.='</tr>';
}
print $str;
?>
</tbody>
        <tfoot>

        <tr>

          <th>ID</th>

          <th>Client</th>

          <th>Received date</th>

          <th>Rejected date</th>

          <th></th>

          <th>Note</th>

          <th>Decline Reason</th>

          <th>Status</th>

          <th>Audiofile</th>

          <th>View</th>

          <th>Action</th>

        </tr>

        </tfoot>

      </table>

    </div>

  </div>

</div>

<!-- /.panel panel-white -->



<div id="LeadInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="LeadInfo">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="exampleModalLabel">Lead details</h4>

      </div>

        <div class="modal-body">



        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

    </div>

  </div>

</div>

  <div class="modal fade" id="modalka"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">

    <div class="modal-dialog" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

          <h4 class="modal-title" id="exampleModalLabel">Conversation: </h4>

        </div>

        <div class="modal-body" id="chat">

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

          <!--                    <button type="submit" class="btn btn-primary">Update info</button>-->

        </div>

        </form>

      </div>

    </div>

  </div>

  <div class="modal fade" id="disapprove"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Decline request
        </div>
        <div class="modal-body">
          <form id="disapprReason" enctype="multipart/form-data" method="POST"><div class="form-group"><textarea id="reason" name="decline" placeholder="Describe your decline reason" class="form-control"></textarea>
              <input type="hidden" name="lead_id" value="" />
              <input type="hidden" name="client_id" value="" />
              <br><label class="btn btn-sm btn-success">Add audio attachment<input type="file" name="audiofile" accept="audio/*" style="display:none"></label>
              <br><br><input class="btn" type="submit">
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function () {

    var approvals = $("#approvals");

    $('#approvals tfoot th').each( function () {
        var title = $('#approvals thead tr:eq(0) th').eq( $(this).index() ).text();

        var html_string = '';

        var input_style = ' style="width:100%; padding:1px !important; margin-left:-2px; margin-bottom: 0px;"';

        var select_style = ' style="width:100%; padding:1px; margin-left:-2px; margin-bottom: 0px; height: 24px;"';

        if ($(this).index() == 2 || $(this).index() == 3){

          html_string = '<input type="text" ' + input_style + ' class="datepicker">';

        }

        else if ( $(this).index() == 7 ) {

          html_string = '<select ' + select_style + '>' +

          '<option value="">Select Status...</option>' +

          '<option value="0">Request Approved</option>' +

          '<option value="3">Request Disapproved</option>' +

          '<option value="2">Requesting to Reject</option>' +

          '<option value="4">Requesting details</option>' +

          '</select>';
        }

        else if ( $(this).index() < 5 ){

          html_string = '<input type="text" ' + input_style + ' placeholder="Search ' + $.trim(title) + '"/>';

        }



        $(this).html(html_string);

      } );

  var table = approvals.DataTable({
    "initComplete": function () {

      var r = $('#approvals tfoot tr');

      r.find('th').each(function(){

        $(this).css('padding', 8);

      });

      $('#approvals thead').append(r);

      $('input').css('text-align', 'center');

    },
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
      $(nRow).find('.viewLeadInfo').on('click', function () {
        var id = $(this).attr('attr-id');
        $.ajax({
          type: "POST",
          url: '<?php echo __HOST__ . '/leads/LeadInfo/' ?>',
          data: {id: id},
          success: function (data) {
            $('#LeadInfo').find('.modal-body').html(data);
          }
        });
      });
    },
    "aoColumnDefs": [
      {'bSortable': false, 'aTargets': [ 4 ] }
    ],
    "columnDefs":[null,{'type':'html'}],
    "order": [[ 0, "desc" ],[ 7, "desc" ],[ 3, "desc" ]],
    "aLengthMenu": [
      [100, 200, -1],
      [100, 200, "All"]
    ]});

    $( ".datepicker" ).datepicker({

      dateFormat: 'yy-mm-dd'

    });

 

    // Apply the search for datatable fields

    table.columns().eq( 0 ).each( function ( colIdx ) {
        $( 'input, select', table.column( colIdx ).footer() ).on( 'keyup change', function () {
          if(colIdx == 2 || colIdx == 3){
            var dorn = Date.parse( this.value + ' 00:00:00 GMT +1100' );
            console.log(dorn);
            var stamp = Math.floor( Number(dorn) / 10000000 );
            table
              .column( colIdx )
              .search( isNaN(stamp) ? '' : stamp )
              .draw();
          } else {
            table
             .column( colIdx )
             .search(this.value)
             .draw();
          }
        } );
    } );
});

function delbutfile(d){

  var data=d.value;

  console.log(data);

  $.ajax({

    type: "POST",

    url: '<?php echo __HOST__ . '/approvals/deleteFile/' ?>',

    data: { 'path':data},

    success: function (respond) {

      console.log(respond);

      window.location.href='<?php print  __HOST__ . '/approvals/';?>';

    }

  });

};
//Function to accept rejection
    function acceptLead(id, client_id,e){

      $.ajax({

        type: "POST",

        url: '<?php echo __HOST__ . '/approvals/accept_lead/' ?>',

        data: { id: id, client_id: client_id },

        success: function (data) {
      $(e).parent().siblings('.status').html('<span class="bg-primary pdfive">Reject accepted</span>');
        }

      });

    }
//Function to request more info
function moreInfo(id, client_id,e) {

  console.log('hello');

  $.ajax({

    type: "POST",

    url: '<?php echo __HOST__ . '/approvals/moreInfo/' ?>',

    data: { id: id, client_id: client_id },

    success: function (data) {
      $(e).parent().siblings('.status').html('<span class="bg-info pdfive">More info required</span>');
    }

  });

}



    var modalka = $('#LeadInfo');

//Function when you open the modal to add rejection reason
    function rejectLead(id, client_id,context){
      $('#disapprReason').find('input[name=lead_id]').val(+Number(id));
      $('#disapprReason').find('input[name=client_id]').val(+Number(client_id));
    }

//Action to decline rejection
$('#disapprReason').submit(function(e)
{
  e.preventDefault();
  var data=$(this).serialize();
  console.log(data);
  $.ajax({
    type: "POST",
    url: "<?php echo __HOST__ . "/approvals/decline/"; ?>",
    data: data,
    success: function(respond) {
      console.log(respond);
      if(respond=='ok')
      {
      }
    }
  });
});

$('#approvals').click(function(e)
{
  if(e.target.dataset.act!="conversation")
  {
    return;
  }
  console.log(e.target.value);
  $.ajax({
    type: "POST",
    url: "<?php echo __HOST__ . "/leads/getConvForLead/"; ?>",
    data: {'lead_id':e.target.value},
    success: function(respond) {
      $("#chat").html(respond);
    }
  });
});
</script>

