<div class=""row">
<div class="col-md-4">
    <form id="terms">
      <h4 class="text-center">Open signed terms</h4>
        <div class="form-group">
Select Client
          <span id="infospanterm" class="label label-primary" style="float:right"></span>
          <select name="client" class="form-control" id="client_term">
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
<div class="col-md-6">
    <div id="listOfTerms">
    </div>
</div>
</div>
<script>
    var infospan=document.getElementById('infospanterm');
    var listOfTerms=document.getElementById('listOfTerms');
    var client=document.getElementById('client_term');

    function inform(){
        $.ajax(
            {
                type:"POST",
                url: "<?php echo __HOST__ . '/terms/getListOfCurrent' ?>",
                data:{'client':client.value},
                success:function(data) {
                    listOfTerms.innerHTML=data;
                }
            }
        )
    };

    client.onchange=function(){
        infospan.innerHTML='';
        inform();
    };

</script>