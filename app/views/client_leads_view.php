<div class="panel panel-white ">


    <div class='col-md-5 col-xs-12  text-center'>

        <h3>Download your leads</h3>

        <br>

        <form action="/client_leads/downloadleads" id="LeadsForClient">

            <div class="form-group">

                <div class="input-daterange input-group" id="datepicker">

                    <input type="text" class="input-sm form-control" name="start" value="<?php print date('m/d/Y',strtotime('-1 month'))?>"/>

                    <span class="input-group-addon">to</span>

                    <input type="text" class="input-sm form-control" name="end" value="<?php print date('m/d/Y')?>" />

                </div>

            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Download leads">

            </div>

        </form>

    </div>
    <div class="col-md-3"></div>

    <div class="col-md-2"></div>

</div>





<hr style="clear: both;">

<div class="col-md-12">

    <div class="table-responsive">

        <table class="table responsive" id="client_leads">

            <thead>



            <tr>

                <th>ID</th>

                <th>Name</th>

                <th>Date</th>

                <th>State</th>

                <th>Postcode</th>

                <th>Status</th>

                <th>Action</th>

                <th>Lead details</th>

                <th>Rejection details</th>

            </tr>

            </thead>

            <tfoot class="fortable">

            <tr>

                <th>ID</th>

                <th>Name</th>

                <th>Date</th>

                <th>State</th>

                <th>Postcode</th>

                <th>Status</th>

                <th>Action</th>

                <th>Lead details</th>

                <th>Rejection details</th>

            </tr>

            </tfoot>

        </table>

    </div>



</div>



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



<script>





    $(document).ready(function () {		

		var leads = $('#client_leads');



        var table = leads.DataTable({

            //"processing": true,

            "serverSide": true,

            "ajax": {

                "url": "<?php echo __HOST__ . "/client_leads/" ?>getLeads",

                "type": "POST"

            },

//            "columns": [

//                null,

//                null,

//                null,

//                null,

//                null,

//                { "searchable": false },

//                null,

//                { "searchable": false },

//                { "searchable": false },

//

//            ],

            "aoColumnDefs": [

                { 'bSortable': false, 'aTargets': [ 6,7,8 ]}],

            "order": [[ 2, "desc" ]],

            "aLengthMenu": [

                [100, 200, -1],

                [100, 200, "All"]

            ],

            "oLanguage": {

                "sInfoFiltered": ""

            }

        });



        var input_style = ' style="width:100%; padding:1px !important; margin-left:-2px; margin-bottom: 0px;"';

        var new_input_style = 'style="width:75%; padding:1px !important; margin-left:-2px; margin-bottom: 0px;"';

        var select_style = ' style="width:100%; padding:1px; margin-left:-2px; margin-bottom: 0px; height: 24px;"';





        $('#client_leads tfoot th').each( function () {

            var title = $('#client_leads thead tr:eq(0) th').eq( $(this).index() ).text();

            var html_string = '';

//            var title = $(this).text();

//            if ($(this).index() == 6 || $(this).index() == 7 || $(this).index() == 8){

//                return;

//            }



    if( $(this).index() < 5 && $(this).index() != 3 && $(this).index() != 4){

        html_string = '<input type="text" ' + input_style + ' placeholder="Search '+title+'" />';

            if(title=='Date'){

                html_string = '<input type="text" ' + input_style + ' class="datepicker"" />';

            }

    }else if( $(this).index() == 5) {

        html_string = '<select ' + select_style + '>' +

            '<option value="">Select Status...</option>' +

            '<option value="1">Lead Approved</option>' +

            '<option value="3">Reject Disapproved</option>' +

            '<option value="2">Requesting to Reject</option>' +

            '<option value="4">Requesting details</option>' +

            '<option value="0">Rejected</option>' +

            '</select>';

    }else if( $(this).index() == 3) {

        html_string = '<input type="text" ' + new_input_style + ' placeholder="Search '+title+'" />';

    }else if( $(this).index() == 4) {

        html_string = '<input type="text" ' + new_input_style + ' placeholder="Search '+title+'" />';

    }

            $(this).html(html_string);

        } );

        $( ".datepicker" ).datepicker({

            dateFormat: 'yy-mm-dd'

        });

//

//        table.columns().every( function () {

//            var that = this;

//

//            $( 'input', this.footer() ).on( 'keyup change', function () {

//                if ( that.search() !== this.value ) {

//                    that

//                        .search( this.value )

//                        .draw();

//                }

//            } );

//

//        } );



        table.columns().eq( 0 ).each( function ( colIdx ) {

            $( 'input, select', table.column( colIdx ).footer() ).on( 'keyup change', function () {



                if(colIdx == 2 ){

                    console.log(this.value);

                    var dorn = Date.parse( this.value + ' 00:00:00 GMT +1100' );

                    console.log(dorn);

                    var stamp = Math.floor( Number(dorn) / 10000000 );

                    console.log(stamp);

                    table

                        .column( colIdx )

                        .search( isNaN(stamp) ? '' : stamp )

                        .draw();

                }else{

                    table

                        .column( colIdx )

                        .search(this.value)

                        .draw();

                }



            });

        });



        var r = $('#client_leads tfoot tr');

        r.find('th').each(function(){

            $(this).css('padding', 8);



        });

        $('#client_leads thead').append(r);

        $('input').css('text-align', 'center');







        var modalBody = '<form id="rejectForm">' +

            '<p>Choose your rejection reason: </p>' +

            '<label><input type="radio" name="reason" value="1" required> Outside of nominated service area (2 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="2"> Duplicate (2 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="3"> Incorrect Phone Number (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="6"> Unable to contact within 7 days (7 days to reject)</label><br>'+

            '<textarea style="width:100%" rows="3" name="notes" required></textarea>' +

            '</form>';

        var modalBody1 = '<form id="rejectForm">' +

            '<p>Choose your rejection reason: </p>' +

            '<label><input type="radio" name="reason" value="1" disabled> Outside of nominated service area (2 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="2" disabled> Duplicate (2 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="3" required> Incorrect Phone Number (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="4"> Indicated they won\'t purchase the specified service within 6 month (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="5"> Customer is wanting Off Grid System (7 days to reject)</label><br>'+

            '<label><input type="radio" name="reason" value="6"> Unable to contact within 7 days (7 days to reject)</label><br>'+

            '<textarea style="width:100%" rows="3" name="notes" required></textarea>' +

            '</form>';

        var modalFooter = '<span id="timeLeft" class="pull-left"></span><input form="rejectForm" type="submit" class="btn btn-primary reject" value="Reject this lead"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';

        var tt = document.querySelector('#client_leads');

        var modalka = $('#LeadInfo');

        tt.addEventListener('click', function(e){

            if (e.target && e.target.matches('a.viewLeadInfo')) {

                e.preventDefault();

                var btn = e.target;

                var id = btn.getAttribute('attr-lead-id');

                modalka.find('.modal-header').text('Lead details');

                modalka.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

                $.ajax({

                    type: "POST",

                    url: '<?php echo __HOST__ . '/client_leads/LeadInfo/' ?>',

                    data: { id: id },

                    success: function (data) {

                        modalka.find('.modal-body').html(data);

                    }

                });

            }



            if (e.target && e.target.matches('a.leadreject')) {

                e.preventDefault();

                var btn = e.target;

                var sure = true;

                var id = btn.getAttribute('attr-lead-id');

                var permission = btn.getAttribute('data-permission');

				var closeTime=+btn.getAttribute('data-time');

                var leadName = btn.getAttribute('attr-client');

                console.log(e.target.dataset.info);

                //console.log(permission);

                if(!e.target.dataset.info){

                    var sure = confirm('Are you sure you want to reject lead "' + leadName + '"?');

                }

                if (!sure) {

                    return;

                }

                modalka.find('.modal-header').text('Reject lead "' + leadName + '"');

                if(permission) {

                    modalka.find('.modal-body').html(modalBody1);

                } else {

                    modalka.find('.modal-body').html(modalBody);

                }

                document.querySelector('#LeadInfo .modal-footer').innerHTML = modalFooter;

				clearTimeout(x);

				x=countDateLeft(closeTime);

                modalka.modal("show");

                $('#rejectForm').submit(function(e){

                    e.preventDefault();

                    var reason = $(this).find('input[name=reason]:checked').val();

                    var notes = $(this).find('textarea[name=notes]').val();

                    $.ajax({

                        type: "POST",

                        url: '<?php echo __HOST__ . '/client_leads/reject_Lead/' ?>',

                        data: { lead_id: id,

                            reject_reason: reason, notes: notes },

                        success: function (data) {

                            console.log(data);

                            if(data === "Success") { modalka.modal("hide"); table.ajax.reload(); }

                        }

                    });

                });

            }

            if (e.target && e.target.matches('a.RejectionDetails')) {

                e.preventDefault();

                var btn = e.target;

                var id = btn.getAttribute('attr-lead-id');

                modalka.find('.modal-header').text('Rejection Details');

                modalka.find('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>');

                $.ajax({

                    type: "POST",

                    url: '<?php echo __HOST__ . '/client_leads/rejectInfo/' ?>',

                    data: { id: id },

                    success: function (data) {

                        modalka.find('.modal-body').html(data);

                    }

                });

            }



        });

		var x;

	function countDateLeft(w){

	var date1 = new Date();

	var date2 = w;

	console.log(w);

    var delta = date2-date1;

    var seconds = Math.round(delta / 1000);

    var minutes = Math.floor(seconds / 60);

    var hours = Math.floor(minutes / 60);

    var days = Math.floor(hours / 24);

    hours -= (days * 24);

    minutes -= (hours * 60);

    minutes -= (days * 1440);

    seconds -= (minutes * 60);

    seconds -= (hours * 3600);

    seconds -= (days * 86400);

    console.log(days+' Days, '+hours + ' Hours, ' + minutes+' Minutes '+ seconds+' Seconds');

    $('#timeLeft').html(days+' Days, '+hours + ' Hours, ' + minutes+' Minutes '+ seconds+' Seconds left for rejection');

	//return days+' Days, '+hours + ' Hours, ' + minutes+' Minutes '+ seconds+' Seconds';

	x=setTimeout(countDateLeft,1000,w);

}

        $('.input-daterange').datepicker({

            multidate: "true"

        });

        // $('.dateSearch').datepicker({format: 'dd/mm/yyyy'});

    });



</script>





<!--<script src="cdn.datatables.net/plug-ins/1.10.13/filtering/row-based/range_dates.js"></script>-->

