<div class=""row">
	<div class="col-md-4">
		<form id="clCampForm">
			<h4 class="text-center">See client campaigns</h4>
			<div class="form-group">
				Select Client
				<span id="infospanclcamp" class="label label-primary" style="float:right"></span>
				<select name="client" class="form-control" id="client_camp">
					<option selected disabled></option>
					<?php
					foreach ($data as $key => $value) {
						echo "<option value='" . $value['id'] . "'>" . $value['campaign_name'] . "</option>";
					}
					?>
				</select>
			</div>

			<hr>
		</form>
	</div>
	<div id="tablClCamp">
	</div>
</div>

<div class="modal fade" id="editClCampAd"  tabindex="-1" role="dialog" aria-labelledby="editClCampAd">
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
						<input type="hidden" name="coords">
						<textarea class="form-control" placeholder="Post codes" type="text" id="postcodes" name="postcodes" ></textarea>
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


<script>
	var table=document.getElementById('tablClCamp');
	var client=document.getElementById('client_camp');

	function inform(){
		$.ajax(
		{
			type:"POST",
			url: "<?php echo __HOST__ . '/client_campaigns/getListOfCampOneClient' ?>",
			data:{'client':client.value},
			success:function(res) {
				// console.log(res);
				data = JSON.parse(res);
				var strHtmlTr = '';
				$('#tablClCamp').html('');
				var resLen = data.length;
				if (res)
				{
					for(i=0;i<resLen;i++){
						strHtmlTr += "<tr>"+
						"<td attr-id='" + data[i]['id'] + "'>" + data[i]['id'] + "</td>"+
						"<td attr-name='" + data[i]['camp_name']+ "'>" + data[i]['camp_name'] + "</td>"+
						"<td attr-clname='" + data[i]['campaign_name']+ "'>" + data[i]['campaign_name'] + "</td>"+
						"<td attr-clemail='" + data[i]['email']+ "'>" + data[i]['email'] + "</td>"+
						"<td attr-weekly='" + data[i]['weekly'] +"'>" + data[i]['weekly'] + "</td>"+
						"<td class='hidden' attr-codes=''>" + data[i]['postcodes'] + "</td>"+
						"<td>"+
						"<a href='#' class='edit-campaign-ad' data-toggle='modal' data-target='#editClCampAd' title='Edit ClCampaign' onclick='editClCamp(event)'><i class='fa fa-pencil' aria-hidden='true'></i></a>"+
						"<a class='delete-campaign' title='Delete ClCampaign' onclick='deleteClCamp(event)'><i class='fa fa-trash-o' aria-hidden='true'></i></a>"+
						"</td>"+
						"<td attr-status='" + data[i]['camp_status'] +"'>" + (data[i]['camp_status']==1? 'Active' : 'Not active') + "</td>"+
						(data[i]['camp_status']==1? "<td attr-but><button class='btn btn-danger clCampStopSendLeads' onclick='deactivate(event)'>Stop this campaign</button></td>" : "<td attr-but><button class='btn btn-success clCampSendLeads' onclick='activateClCamp(event)'>Start to send leads</button></td>")+
						"</tr>";
					}
					$('#tablClCamp').append('<table id="campaigns" class="display table responsive table-condensed table-striped table-hover table-bordered pull-left" cellspacing="0" width="100%">'+
						'<thead><tr>'+
						'<th>id</th>'+
						'<th><input type="text" placeholder="Campaign Name" id="search_camp" onkeyup="searchClCamp(event)"></th>'+
						'<th>Client Name</th>'+
						'<th>Client email</th>'+
						'<th>Weekly limit</th>'+
						'<th>Action</th>'+
						'<th>Status</th>'+
						'<th>Send leads</th>'+
						'</tr></thead>'+
						'<tbody>'+ strHtmlTr + '</tbody>'+
						'</table>');
				}
			}
		}
		)
	};
	function editClCamp(event) {
		var button = event.currentTarget;
		var tr = button.parentNode.parentNode;
		var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
		var name = tr.querySelector('td[attr-name]').getAttribute('attr-name');
		var weekly = tr.querySelector('td[attr-weekly]').getAttribute('attr-weekly');
		var codes = tr.querySelector('td[attr-codes]').innerHTML;
		$('#editCampaignform #postcodes').val(codes);
		$('#editCampaignform #campaign-name').val(name);
		$('#editCampaignform #campaign-weekly').val(weekly);
		$('#editClCampSubmit').click(function(){
			var newPostcodes = $('#editClCampAd #postcodes').val();
			var newName = $('#editClCampAd #campaign-name').val();
			var newWeekly = $('#editClCampAd #campaign-weekly').val();
			// console.log(client.value, id, newName, newWeekly, newPostcodes);
			$.ajax({
				type: "POST",
				url: '<?php echo __HOST__ . "/client_campaigns/"; ?>edit_campaign',
				data:  { client: client.value, id: id, name: newName, weekly: newWeekly, newPostcodes: newPostcodes },
				success: function (data) {
					// console.log(data);
					if (data)
					{
						table.innerHTML='';
						inform();
					}
				}
			});
		});
	}

	function deleteClCamp(event) {
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
			data:  { client: client.value, id: id},
			success: function (data) {
				// console.log(data);
				if (data)
				{
					table.innerHTML='';
					inform();
				}
			}
		});
	}

	function deactivate(event) {
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
			data:  { client: client.value, id: id},
			success: function (data) {
				// console.log(data);
				if (data)
				{
					table.innerHTML='';
					inform();
				}
			}
		});
	}

	function activateClCamp(event) {
		var tr = event.currentTarget.parentNode.parentNode;
		var id = tr.querySelector('td[attr-id]').getAttribute('attr-id');
		$.ajax({
			type: "POST",
			url: '<?php echo __HOST__ . "/client_campaigns/"; ?>send_leads',
			data:  { client: client.value, id: id},
			success: function (data)
			{
				// console.log(data);
				if (data)
				{
					table.innerHTML='';
					inform();
				}
			}
		});
	}

	function searchClCamp() {
		var search_val = $('#search_camp').val();
		$('#tablClCamp tbody tr').each(function(){
			var find_camp = this.querySelector('td[attr-name]').innerHTML;
			if (find_camp.search(search_val) != -1)
				$(this).show();
			else
				$(this).hide();
		});
	}

	client.onchange=function(){
		table.innerHTML='';
		inform();
	};

</script>