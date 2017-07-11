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
        <tfoot>
        <tr>
          <th>ID</th>
          <th>Client</th>
          <th>Received date</th>
          <th>Rejected date</th>
          <th>Reason  </th>
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


<script type="text/javascript">

$(document).ready(function () {
    var approvals = $("#approvals");
    table = approvals.DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo __HOST__ . '/approvals/GetApprovals/' ?>",
        "type": "POST"
      },
      "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 4 ] }
      ],
      "order": [[ 0, "desc" ],[ 7, "desc" ],[ 3, "desc" ]],
      "aLengthMenu": [
          [100, 200, -1],
          [100, 200, "All"]
      ],
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
      "initComplete": function () {
        var r = $('#approvals tfoot tr');
        r.find('th').each(function(){
          $(this).css('padding', 8);
        });
        $('#approvals thead').append(r);
        $('input').css('text-align', 'center');
      },
      "oLanguage": {
        "sInfoFiltered": ""
      }
    });


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
//            console.log(this);
//            var select = $('<select style="width:auto; padding:1px; margin-left:-2px; margin-bottom: 0px; height: 24px;"><option value="">Select Status...</option><option value="0">Request Approved</option><option value="1">Request Disapproved</option><option value="2">Requesting to Reject</option></select>').appendTo($(this)).on('change',
//                function (){
//                var val = $.fn.dataTable.util.escapeRegex(
//                    $(this).val()
//                );
////                $(this)
////                    .search(val ? '^' + val + '$' : '', true, false)
////                    .draw();
//            });
        }
        else if ( $(this).index() < 5 ){
          html_string = '<input type="text" ' + input_style + ' placeholder="Search ' + $.trim(title) + '"/>';
        }

        $(this).html(html_string);
        // $(this).html( '<input class="searchbox" type="text" placeholder="Search '+title+'" />' );
      } );

    $( ".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd'
    });
 
    // Apply the search
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
    function acceptLead(id, client_id){
      $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/approvals/accept_lead/' ?>',
        data: { id: id, client_id: client_id },
        success: function (data) {
          table.ajax.reload();
        }
      });
    }

    var modalka = $('#LeadInfo');
    function rejectLead(id, client_id){
      modalka.modal('show');
      modalka.find('.modal-header').text('Decline request');
      modalka.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');
      modalka.find('.modal-body').html(
        '<form action="decline" enctype="multipart/form-data" method="POST"><div class="form-group"><textarea id="reason" name="decline" placeholder="Describe your decline reason" class="form-control"></textarea>' +
        '<input type="hidden" name="lead_id" value="'+Number(id)+'" />' +
        '<input type="hidden" name="client_id" value="'+Number(client_id)+'" />' +
        '<br><label class="btn btn-sm btn-success">Add audio attachment<input type="file" name="audiofile" accept="audio/*" style="display:none"></label>'+
        '<br><br><input class="btn" type="submit">' +
        '</div>' +
        '</form>');
      modalka.on('shown', function() {
        $("#reason").focus();
      });

//      $.ajax({
//        type: "POST",
//        url: '<?php //echo __HOST__ . '/approvals/rejectLead/' ?>//',
//        data: { id: id, client_id: client_id },
//        success: function (data) {
//          table.ajax.reload();
//        }
//      });
    }
    function moreInfo(id, client_id) {
      console.log('hello');
        $.ajax({
        type: "POST",
        url: '<?php echo __HOST__ . '/approvals/moreInfo/' ?>',
        data: { id: id, client_id: client_id },
        success: function (data) {
          table.ajax.reload();
        }
      });
    }
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
