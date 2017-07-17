<div id="UpdateClientForm" action="UpdateProfile" >
<h1 class="text-center">Your Profile</h1>

    <?php

    echo $profile;

?>

<!--  <button type="submit" id="Update" class="btn btn-primary">Edit profile</button>-->
</div>
<h1 class="text-center">Your Campaigns</h1>
<div class="table-responsive">
    <table id="campaigns" class="display table responsive table-condensed table-striped table-hover table-bordered pull-left" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>id</th>
            <th><input type="text" placeholder="Campaign Name" id="search_camp"></th>
            <th>Weekly limit</th>
            <th>Postcode<br>(location)</th>
            <th>Radius</th>
            <th>Action</th>
            <th>Status</th>
            <th>Send leads</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($data['campaigns'] as $item)
        {
            echo "<tr>
                        <td attr-id='".$item['id']."'>" . $item['id'] . "</td>
                        <td attr-name='".$item['camp_name']."'>" . $item['camp_name'] . "</td>
                        <td attr-weekly='".$item['weekly']."'>" . $item['weekly'] . "</td>
                        <td class='hidden' attr-codes=''>" .$item['postcodes']. "</td>
                        <td class='hidden' attr-coords=''>" .$item['coords']. "</td>
                        <td attr-nearest='" .$item['nearest']. "'>" .$item['nearest']. "</td>
                        <td attr-radius='" .$item['radius']. "'>" .$item['radius']. "Km</td>
                        <td>
                            <a href='#' class='edit-campaign' data-toggle='modal' data-target='#editClCamp' title='Edit ClCampaign'><i class='fa fa-pencil' aria-hidden='true'></i></a>
                            <a class='delete-campaign' title='Delete ClCampaign'><i class='fa fa-trash-o' aria-hidden='true'></i></a>
                        </td>
                        <td attr-status='".$item['camp_status']."'>" . (($item['camp_status'])? 'Active' : 'Not active') . "</td>".
                (($item['camp_status'])? "<td attr-but><button class='btn btn-danger clCampStopSendLeads'>Stop this campaign</button></td>" : "<td attr-but><button class='btn btn-success clCampSendLeads'>Start to send leads</button></td>").
                "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<!-- #addNewCampaign -->

<div class="form-group">
    <button type="submit" class="btn btn-primary" data-toggle='modal' data-target='#addNewClCamp'>Add new campaign</button>
</div>

<!-- /#editCampaign.modal-->
<div class="modal fade" id="editClCamp"  tabindex="-1" role="dialog" aria-labelledby="editClCamp">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Edit Campaign</h4>
            </div>
            <form id="editCampaignform" action="update_campaign" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" class="campaign-id">
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

<!-- /#addNewCampaign.modal-->
<div class="modal fade" id="addNewClCamp"  tabindex="-1" role="dialog" aria-labelledby="addNewClCamp">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
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

<script>
    $(document).ready(function()
    {
        $('.edit-campaign').each(function(){
            this.addEventListener('click', function(event) {
                var button = event.currentTarget;
                var tr = button.parentNode.parentNode;
                var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
                var name = tr.querySelector('td[attr-name]').getAttribute('attr-name');
                var weekly = tr.querySelector('td[attr-weekly]').getAttribute('attr-weekly');
                var codes = tr.querySelector('td[attr-codes]').innerHTML;
                var coords = tr.querySelector('td[attr-coords]').innerHTML;
                var radius = tr.querySelector('td[attr-radius]').getAttribute('attr-radius');
                var nearest = tr.querySelector('td[attr-nearest]').getAttribute('attr-nearest');
                $('#editClCamp #postcodes').val(codes);
                $('#editClCamp #campaign-name').val(name);
                $('#editClCamp #campaign-weekly').val(weekly);
                $('#editClCamp #campaign-coords').val(coords);
                $('#editClCamp #campaign-radius').val(radius);
                $('#editClCamp #campaign-nearest').val(nearest);
                $('#editClCampSubmit').click(function(){
                    var newPostcodes = $('#editClCamp #postcodes').val();
                    var newName = $('#editClCamp #campaign-name').val();
                    var newWeekly = $('#editClCamp #campaign-weekly').val();
                    var newCoords = $('#editClCamp #campaign-coords').val();
                    var newRadius = $('#editClCamp #campaign-radius').val();
                    var newNearest = $('#editClCamp #campaign-nearest').val();
                    console.log(id, newName, newPostcodes);
                    $.ajax({
                        type: "POST",
                        url: '<?php echo __HOST__ . "/client_campaigns/"; ?>edit_campaign',
                        data:  { id: id, name: newName, weekly: newWeekly, newPostcodes: newPostcodes, coords: newCoords, radius: newRadius, nearest: newNearest },
                        success: function (data) {
                            // console.log(data);
                            //                        console.log(tr.querySelector('td[attr-codes]').innerHTML);
                            if (data)
                            {
                                   location.reload();
                            }
                        }
                    });
                });
            });
        });

        $('.delete-campaign').each(function(){
            this.addEventListener('click', function(event) {
                var sure = confirm('Are you sure that you want to deactivate this campaign?');
                if (!sure) {
                    return;
                }
                var button = event.currentTarget;
                var tr = button.parentNode.parentNode;
                var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo __HOST__ . "/client_campaigns/"; ?>delete_campaign',
                    data:  { id: id},
                    success: function (data) {
//                        console.log(data);
                        if (data)
                        {
                            location.reload();
                        }
                    }
                });
            });
        });

        $('.clCampStopSendLeads').each(function(){
            this.addEventListener('click', function(event) {
                var sure = confirm('Are you sure that you want to deactivate this campaign?');
                if (!sure) {
                    return;
                }
                var button = event.currentTarget;
                var tr = button.parentNode.parentNode;
                var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo __HOST__ . "/client_campaigns/"; ?>delete_campaign',
                    data:  { id: id},
                    success: function (data) {
                        // console.log(data);
                        if (data)
                        {
                            location.reload();
                        }
                    }
                });
            });
        });

        document.getElementById('addNewClCampSubmit').addEventListener('click', function() {
            var newName = $('#campaign-new-name').val();
            var newWeekly = $('#campaign-new-weekly').val();
            var newPostcodes = $('#campaign-new-postcodes').val();
            var newCoords = $('#campaign-new-coords').val();
            var newRadius = $('#campaign-new-radius').val();
            var newNearest = $('#campaign-new-nearest').val();
            $.ajax({
                type: "POST",
                url: '<?php echo __HOST__ . "/client_campaigns/"; ?>add_new_campaign',
                data:  { name: newName, weekly: newWeekly, newPostcodes: newPostcodes, coords: newCoords, radius: newRadius, nearest: newNearest },
                success: function (data) {
//                    console.log(data);
                    if (data)
                    {
                        location.reload();
                    }
                }
            });
        });

        $('.clCampSendLeads').each(function(){
            this.addEventListener('click', function(event) {
                var tr = event.currentTarget.parentNode.parentNode;
                var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo __HOST__ . "/client_campaigns/"; ?>send_leads',
                    data:  { id: id},
                    success: function (data)
                    {
                        // console.log(data);
                        location.reload();
                    }
                });
            });
        });

        $('#search_camp').click(function(e){
            e.stopPropagation();
        })
        $('#search_camp').on( 'keyup change', function () {
            var search_val = this.value;
            $('#campaigns tbody tr').each(function(){
                var find_camp = this.querySelector('td[attr-name]').innerHTML;
                if (find_camp.search(search_val) != -1)
                    $(this).show();
                else
                    $(this).hide();
            });
        });
    });
</script>
<script>
 var btn = $('#Update');
      btn.click(function (ev) {
        $.ajax({
            method: "POST",
            url: '<?php echo __HOST__ . '/profile/'; ?>UpdateProfile',
            success: function (data) {
              document.querySelector('#UpdateClientForm').innerHTML = data;
            }
        });
        ev.preventDefault();
    });
</script>