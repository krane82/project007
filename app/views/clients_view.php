<div class="col-md-12">
<div class="table-responsive">
<?php
echo $table;
?>
</div>
</div>
<hr>
<input type="button" class="btn btn-primary" value="ADD NEW" onclick="addnew();">
<hr>
<div class="row" id="insertdiv" style="display:none">
  <div class="col-sm-12">
    <div class="widget">
      <div class="widget-header">
        <div class="title">
          <h3>Add New Client</h3>
        </div>
      </div>
        <form action="<?php echo __HOST__  . "/clients/"; ?>addNewClient" id="addNewClient" class="signin-wrapper" method="post">
            <div class="widget-body">
          <div class="form-group">
              <input class="form-control" placeholder="email" type="email" name="email" required>
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="password" type="password" id="password" name="password" required>
            </div>
            <hr>
            <div class="form-group">
              <input class="form-control" placeholder="Campaing Name" type="text" name="campaign_name" required>
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="Full name" type="text" name="full_name" >
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="phone" type="text" id="phone" name="phone" >
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="city" type="text" id="city" name="city" >
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="State" type="text" id="state" name="state" >
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="country" type="text" id="country" name="country" >
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="lead cost" type="text" id="lead_cost" name="lead_cost">
            </div>
<!--            <hr>-->
<!--            <div class="form-group">-->
<!--              <p>PostCodes<button type="button" style="float:right" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#mapAddcl">Select by radius</button></p>-->
<!--              <input type="hidden" name="coords">-->
<!--              <textarea class="form-control" placeholder="Post codes" type="text" id="postcode" name="postcodes" ></textarea>-->
<!--              <div id="mapAddcl" class="collapse">-->
<!--                <br>-->
<!--                <iframe src="/app/map/map.php" style="width:100%; height:400px">Не работает</iframe>-->
<!--              </div>-->
<!--            </div>-->
            <div class="form-group">
              <input class="form-control" placeholder="States Filter - Ex: NSW,VIC,WA,QLD,SA,ACT,TAS" type="text" id="states_filter" name="states_filter">
              <label for="states_filter">
               &nbsp;  (*comma separated) Ex: NSW,VIC,WA,QLD,SA,ACT,TAS
              </label>
            </div>
            <hr>
            <div class="form-group">
              <input class="form-control" placeholder="Xero ID" type="text" id="xero_id" name="xero_id">
            </div>
            <div class="form-group">
              <input class="form-control" placeholder="Xero Name" type="text" id="xero_name" name="xero_name">
            </div>

              <div class="form-group" onclick="limits(this,event)">
                <label class="radio-inline"><input type='radio' name='limits' value='limited' checked><b> LIMITED </b></label>
                <label class="radio-inline"><input type='radio' name='limits' value='unlimited'><b> UNLIMITED </b></label>

                <input class="form-control" placeholder="Weekly limit of leads" type="text" id="weekly" name="weekly">
              </div>

            <input type="hidden" id="status" name="status" value="1" >
          </div>
          <div class="actions">
            <input class="btn btn-info pull-left" type="submit" value="Save">
            <div class="clearfix"></div>
          </div>
        </form>
        <div class="addsuccess bg-success"></div>
      </div>
    </div>
  </div>

<div class="modal fade" id="editClient"  tabindex="-1" role="dialog" aria-labelledby="editCampaign">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edit Client: </h4>
      </div>
      <form id="editClientForm" action="<?php echo __HOST__  . "/clients/"; ?>UpdateClients" method="post">
        <div class="modal-body">

          <div class="clientsfields"></div>

          <div class="bg-success success"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update info</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--Add here the modal window for client's campaign changes-->
<div class="modal fade" id="editClCampAd"  tabindex="-1" role="dialog" aria-labelledby="editClCampAd">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background-color: #CFCFD0">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edit Campaign</h4>
      </div>
      <form id="editCampaignform" action="update_campaign" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" class="campaign-id" id="campaign-id">
          <input type="hidden" name="client-id" class="client-id" id="client-id">
          <div class="form-group">
            <label for="campaign-name" class="control-label">Campaign Name:</label>
            <input type="text" class="form-control" name="name" id="campaign-name">
          </div>
          <div class="form-group">
            <label for="campaign-weekly" class="control-label">Campaign Weekly Limit:</label>
            <input type="text" class="form-control" name="weekly" id="campaign-weekly">
          </div>
          <div class="form-group">
            <p>PostCodes<button type="button" style="float:right" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#mapEditClCam">Select by radius</button></p>
            <input type="hidden" name="coords" id="campaign-coords">
            <input type="hidden" name="radius" id="campaign-radius">
            <input type="hidden" name="nearest" id="campaign-nearest">
            <textarea class="form-control" placeholder="Post codes" type="text" id="postcodes" name="postcodes" readonly></textarea>
            <div id="mapEditClCam" class="collapse">
              <br>
              <iframe src="/app/map/map.php" style="width:100%; height:400px">Не работает</iframe>
            </div>
          </div>
          <div class="form-group">
            <input type="checkbox" name="status" checked>
          </div>
          <div class="bg-success success"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="editClCampSubmit">Update info</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--End of campaigns editing modal-->
<!--Modal to add new client's campaign-->
<div class="modal fade" id="addNewClCamp"  tabindex="-1" role="dialog" aria-labelledby="addNewClCamp">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background-color: #CFCFD0">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Add New Client Campaign</h4>
      </div>
      <form id="addNewCampaignform" action="add_campaign" method="post">
        <div class="modal-body">
          <input type="hidden" name="id" class="campaign-id">
          <div class="form-group">
            <label for="campaign-new-name" class="control-label">Campaign Name:</label>
            <input type="text" class="form-control" name="name" id="campaign-new-name">
          </div>
          <div class="form-group">
            <label for="campaign-new-weekly" class="control-label">Campaign Weekly Limit:</label>
            <input type="text" class="form-control" name="weekly" id="campaign-new-weekly">
          </div>
          <div class="form-group">
            <p>PostCodes<button type="button" style="float:right" class="btn btn-sm btn-success" data-toggle="collapse" data-target="#mapAddClCam">Select by radius</button></p>
            <input type="hidden" name="coords" id="campaign-new-coords">
            <input type="hidden" name="radius" id="campaign-new-radius">
            <input type="hidden" name="nearest" id="campaign-new-nearest">
            <textarea class="form-control" placeholder="Post codes" type="text" readonly id="campaign-new-postcodes" name="postcodes" ></textarea>
            <div id="mapAddClCam" class="collapse">
              <br>
              <iframe src="/app/map/map.php" id="frame1" style="width:100%; height:400px">Не работает</iframe>
            </div>
          </div>
          <div class="bg-success success"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="addNewClCampSubmit" data-dismiss="modal">Add campaign</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--End of creating modal-->
<script>
  function addnew(){
    $('#insertdiv').toggle("slow");
  }
  $(document).ready(function(){
    var table =  $('.clients').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "<?php echo __HOST__ . "/clients/"; ?>ajax_get",
      aaSorting:[],
      "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ 4, 5, 6 ] }
      ],
      "order": [[ 0, "asc" ]],
      "aLengthMenu": [
        [100, 200, -1],
        [100, 200, "All"]
    ],
      "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        nRow.querySelector('.edit-client').addEventListener('click', function(event) {
          var button = event.currentTarget;
          var id = button.getAttribute('attr-id');
          console.log(id);
          $.ajax({
            type: "POST",
            url: '<?php echo __HOST__ . "/clients/"; ?>editClient',
            data:  { id: id },
            success: function (data) {
              document.querySelector('.clientsfields').innerHTML = data;
            }
          });
        });

        $(nRow).find('.delivery_status').bootstrapSwitch();
        $(nRow).find('.status_client').bootstrapSwitch();
        $(nRow).find('.bootstrap-switch-container').on('switchChange.bootstrapSwitch', function(event, state) {
          var button = event.currentTarget;
          var tr = $(this).closest('tr');
          var id = tr.find('td:first').text();
          var val;
          val = button.value = Number(state);
          if(this.querySelector('input').classList.contains('delivery_status')){
            console.log(id, button.value);
            $.ajax({
              type: "POST",
              url: "<?php echo __HOST__ . "/clients/"; ?>update_clients_fields_rel",
              data: {
                "id": id,
                "status": button.value
              },
              success: function(data) {
                console.log(data);
              }
            });
          } else if(this.querySelector('input').classList.contains('status_client')) {
            console.log(id, button.value);
            $.ajax({
              type: "POST",
              url: "<?php echo __HOST__ . "/clients/"; ?>update_user_active",
              data: {
                "id": id,
                "status": button.value
              },
              success: function(data) {
                console.log(data);
              }
            });
          }

        });
        $(nRow).find('.delete-client').click(function(){
          var id = $(this).attr("attr-id");
          var sure = confirm('Are you sure you want to delete this?');
          if (!sure) {
            return;
          }
          $.ajax({
            type: "POST",
            url: "<?php echo __HOST__  . "/clients/"; ?>delete_clients",
            data: {"id": id},
            success: function(data) {
              console.log(data);
            }
          });
          table.ajax.reload();
          return false;
        });
      }
      });

      $('.edit-client').each(function () {
        $(this).addEventListener('click', function (e) {
          var $this = e.currentTarget;
          var id = $($this).attr("attr-id");
          console.log(id);
          $.ajax({
            type: "POST",
            url: '<?php echo __HOST__  . "/clients/"; ?>editClient',
            data:  { id: id },
            success: function (data) {
              document.querySelector('.clientsfields').innerHTML = data;
            }
          });
        });
      });
      var frm = $('#editClientForm');
      frm.submit(function (ev) {
        $.ajax({
            type: frm.attr('method'),
            url: '<?php echo __HOST__  . "/clients/"; ?>UpdateClients',
            data:  frm.serialize(),
            success: function (data) {
              document.querySelector('.success').innerHTML = '<p>' + data + '</p>';
            }
        });
        ev.preventDefault();
    });
    $('#editClient').on('hidden.bs.modal', function () {
      table.ajax.reload();
      document.querySelector('.success').innerHTML = '';
    });
    var addfrm = $('#addNewClient');
    addfrm.submit(function (ev) {
        $.ajax({
            type: addfrm.attr('method'),
            url: '<?php echo __HOST__  . "/clients/"; ?>addNewClient',
            data:  addfrm.serialize(),
            success: function (data) {
              document.querySelector('.addsuccess').innerHTML = '<p>' + data + '</p>';
              table.ajax.reload();
            }
        });
        ev.preventDefault();
    });
    //This is the function to add search fields in datatables
    $('table thead th').each( function () {
      var title = $(this).text();
      if(title=='ID' || title=='Lead Cost' || title=='Delivery' || title=='Status' || title=='Actions') {return;}
      $(this).html( '<input type="text" style="width:100px" placeholder="'+title+'" />' );
    });
    //
    table.columns().every( function () {
      var that = this;
      $( 'input', this.header() ).click(function(e)
      {
        e.stopPropagation();
      })
      $( 'input', this.header() ).on( 'keyup change', function () {
        if ( that.search() !== this.value ) {
          that
              .search( this.value )
              .draw();
        }
      } );
    } );
    //End of function
  });
  function limits(t,e){
    if (e.target.value=="unlimited")
    {
      t.lastElementChild.setAttribute("disabled","disabled");
    }
    else if (e.target.value=="limited")
    {
      t.lastElementChild.removeAttribute("disabled");
    }
    else return;
  }
  function frameinf()
  {
    var frameinf=window.frames[0];
    var framedata=frameinf.returnVal();
    console.log(framedata);
  }

//  Here is he function to open campaign's settings in modal window
  function inform(idVariable){
    $.ajax(
        {
          type:"POST",
          url: "<?php echo __HOST__ . '/client_campaigns/getCurrentCampaign' ?>",
          data:{'campaign':idVariable},
          success:function(res) {
            console.log(res);
            data = JSON.parse(res);
            //var strHtmlTr = '';
            //$('#tablClCamp').html('');
            $('#editClCampAd #campaign-name').val(data.camp_name);
            $('#editClCampAd #campaign-weekly').val(data.weekly);
            $('#editClCampAd #postcodes').val(data.postcodes);
            $('#editClCampAd #campaign-id').val(data.id);
            $('#editClCampAd #client-id').val(data.client_id);
            $('#editClCampAd #campaign-coords').val(data.coords);
            $('#editClCampAd #campaign-radius').val(data.radius);
            $('#editClCampAd #campaign-nearest').val(data.nearest);
          }
   })
  };
  //End of editing modal function
//Here is funcion for Update campaign
  $('#editClCampSubmit').click(function(){
    var newPostcodes = $('#editClCampAd #postcodes').val();
    var newName = $('#editClCampAd #campaign-name').val();
    var newWeekly = $('#editClCampAd #campaign-weekly').val();
    var id = $('#editClCampAd #campaign-id').val();
    var client=$('#editClCampAd #client-id').val();
    var coords=$('#editClCampAd #campaign-coords').val();
    var radius=$('#editClCampAd #campaign-radius').val();
    var nearest=$('#editClCampAd #campaign-nearest').val();

    //console.log(nearest);return true;
    $.ajax({
      type: "POST",
      url: '<?php echo __HOST__ . "/client_campaigns/"; ?>edit_campaign',
      data:  { id: id, name: newName, weekly: newWeekly, client: client, newPostcodes: newPostcodes, coords: coords, radius: radius, nearest: nearest},
      success: function (data) {
        $.ajax({
          type: "POST",
          url: '<?php echo __HOST__  . "/clients/"; ?>editClient',
          data:  { id: client },
          success: function (data) {
            document.querySelector('.clientsfields').innerHTML = data;
          }
        });
      }
    });
  });
//End of function of editing campaign  
//Here is the functon to stop current campaign
  function stopCampaign(id,client) {
    var sure = confirm('Are you sure that you want to deactivate this campaign?');
    if (!sure) {
      return;
    }
    $.ajax({
      type: "POST",
      url: '<?php echo __HOST__ . "/client_campaigns/"; ?>delete_campaign',
      data: {id: id, client: client},
      success: function (data) {
        // console.log(data);
        if (data) {
          $.ajax({
            type: "POST",
            url: '<?php echo __HOST__  . "/clients/"; ?>editClient',
            data:  { id: client },
            success: function (data) {
              document.querySelector('.clientsfields').innerHTML = data;
            }
          });
          //location.reload();
        }
      }
    });
  }

  function activateClCamp(id,client) {
      $.ajax({
      type: "POST",
      url: '<?php echo __HOST__ . "/client_campaigns/"; ?>send_leads',
      data:  { client: client, id: id},
      success: function ()
      {
          $.ajax({
          type: "POST",
          url: '<?php echo __HOST__  . "/clients/"; ?>editClient',
          data:  { id: client },
          success: function (data) {
            document.querySelector('.clientsfields').innerHTML = data;
          }
        });
      }
    });
  }
//end of functionof stopping of campaign
//Function for creating new campaign
  document.getElementById('addNewClCampSubmit').addEventListener('click', function() {
    var client = $('#editClientForm').find('input[name=id]').val();
    var newName = $('#campaign-new-name').val();
    var newWeekly = $('#campaign-new-weekly').val();
    var newPostcodes = $('#campaign-new-postcodes').val();
    var newCoords = $('#campaign-new-coords').val();
    var newRadius = $('#campaign-new-radius').val();
    var newNearest = $('#campaign-new-nearest').val();
    console.log(client);
    $.ajax({
      type: "POST",
      url: '<?php echo __HOST__ . "/client_campaigns/"; ?>add_new_campaign',
      data:  { client: client, name: newName, weekly: newWeekly, newPostcodes: newPostcodes, coords: newCoords, radius: newRadius, nearest: newNearest },
      success: function () {
        $.ajax({
          type: "POST",
          url: '<?php echo __HOST__  . "/clients/"; ?>editClient',
          data:  { id: client },
          success: function (data) {
            document.querySelector('.clientsfields').innerHTML = data;
          }
        });
      }
    });
  });
</script>
