<script type="text/javascript">
    $(document).ready(function(){
    	$(".btn_delete").click(function(){
        var id = $("#user_id").val();
        var url = 'prescription/deletePrescription' 
        swal({
        title: "Are you sure?",
        text: "You want to delete records",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        closeOnConfirm: false,
        closeOnCancel: true
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
                  url : url,
                  type : 'POST',           
                  data :  {
                     id : id,
                    },
                  success: function(data) {
                       location.reload();
                      },
                  error: function() {
                      location.reload();
                  }

             });
          
        } else {
          
        }
      });

    });

    	var checkd = '';
    	$(".drug_details").hide();
        $("#addDrug").click(function(){
            $(".drug_details").toggle(800);
        })

        $('input[type="checkbox"]').on('change', function(e){
           var takeon = $(this).attr('id');
           checkd = takeon;
		   if(e.target.checked){
		     $('#modal_form1').modal({backdrop: 'static', keyboard: false});
		     $("#takenat").val(takeon);
		     $.ajax({  
			    type: 'POST',  
			    dataType: 'json',
			    url: '<?php echo base_url();?>index.php/prescription/getTime', 
			    data: {data:takeon},
			    success: function(response) {
			        $("#time").val(response);
			    }
			});
		   }else{
	   	 	 if(takeon == 'morning'){
					$(".lmdose").html('');
					$("#" + takeon).val('');
				}
				if(takeon == 'afternoon'){
					$(".ladose").html('');
					$("#" + takeon).val('');
				}
				if(takeon == 'evening'){
					$(".ledose").html('');
					$("#" + takeon).val('');
				}
				if(takeon == 'night'){
					$(".lndose").html('');
					$("#" + takeon).val('');
				}
		   }
		});
        $("#errtime").hide();
        $("#errdose").hide();
		$("#getdose").click(function(){
			var dose = $("#dose").val();
			var time = $("#time").val();
			var takenat = $("#takenat").val();
			if(dose == ''){
				$("#errdose").show();
				setTimeout(function() { $("#errdose").hide(); }, 3000);
				return false;
			}
			if(time == ''){
				$("#errtime").show();
				setTimeout(function() { $("#errtime").hide(); }, 3000);
				return false;
			}
			if(dose != '' && time != '' && takenat != ''){
				if(takenat == 'morning'){
					$("#" + takenat).val(takenat+'|'+dose+'|'+time);
					$(".lmdose").html(' ( '+dose+' @ '+time+ ' ) ');
				}
				if(takenat == 'afternoon'){
					$("#" + takenat).val(takenat+'|'+dose+'|'+time);
					$(".ladose").html(' ( '+dose+' @ '+time+ ' ) ');
				}
				if(takenat == 'evening'){
					$("#" + takenat).val(takenat+'|'+dose+'|'+time);
					$(".ledose").html(' ( '+dose+' @ '+time+ ' ) ');
				}
				if(takenat == 'night'){
					$("#" + takenat).val(takenat+'|'+dose+'|'+time);
					$(".lndose").html(' ( '+dose+' @ '+time+ ' ) ');
				}
			} 
		});

		var table = $('.drugdetailsTbl').DataTable();
		$('.prescriptionTbl').dataTable({
	        "iDisplayLength": 10
	    });
		var counter = 1;
		$('#getdrugdetails').on( 'click', function () {
			
		 	var drug_name = $("#drug_name").val();
		 	var nodays = $("#duration_days").val();
		 	var takeon = [];
		 	if($("#morning").val() != ''){
		 		takeon.push($("#morning").val());
		 	}
		 	if($("#afternoon").val() != ''){
		 		takeon.push($("#afternoon").val());
		 	}
		 	if($("#evening").val() != ''){
		 		takeon.push($("#evening").val());
		 	}
		 	if($("#night").val() != ''){
		 		takeon.push($("#night").val());
		 	}
	 	

		 if(drug_name == ''){
		 	alert('Please enter drug name');
		 	return false;
		 }

		 if(nodays == ''){
		 	alert('Please duration of days');
		 	return false;
		 }
		 if(takeon == ''){
		 	alert('Please enter dose details');
		 	return false;
		 }
		 
		 	table.row.add( [
            	counter,
            	drug_name,
            	nodays,
            	takeon
    	 	] ).draw( false );
 			
         counter++;
         $("#drug_name").val('');
         $("#duration_days").val('');
         $(".lmdose").html('');
         $(".ladose").html('');
         $(".ledose").html('');
         $(".lndose").html('');
         $("#morning").val();
         $("#afternoon").val();
         $("#evening").val();
         $("#night").val();
         $('input:checkbox').removeAttr('checked');
        } );

        $("#savePrescription").click(function(){
        	var user_id = $("#user_id").val();
			var doctor_id = $("#doctor_id").val();
			var ddate = $("#ddate").val();
        	if(user_id == ''){
			 	alert('Please patient name');
			 	return false;
			 }

			 if(doctor_id == ''){
			 	alert('Please select doctor name');
			 	return false;
			 }

			 if(ddate == ''){
			 	alert('Please select date');
			 	return false;
			 }
        	var heads = [];
			$("thead").find("th").each(function () {
			  heads.push($(this).text().trim());
			});
			var rows = [];
			$("tbody tr").each(function () {
			  cur = {};
			  $(this).find("td").each(function(i, v) {
			    cur[heads[i]] = $(this).text().trim();
			  });
			  rows.push(cur);
			  cur = {};
			});
			var user_id = $("#user_id").val();
			var doctor_id = $("#doctor_id").val();
			var ddate = $("#ddate").val();

			var pers = {
	            user_id: user_id,
	            doctor_id:doctor_id,
	            ddate:ddate,
	        }
	        console.log(rows);
			$.ajax({  
			    type: 'POST',  
			    dataType: 'json',
			    url: '<?php echo base_url();?>index.php/prescription/savePrescription', 
			    data: {data:pers, dosedetails:rows},
			    success: function(data) {
                       //location.reload();
                      },
                  error: function() {
                     // location.reload();
                  }
			});

        })

        $('#modal_form1').on('hidden.bs.modal', function (e) {
        	if($("#dose").val() == '' || $("#time").val() == ''){
        		$("#"+checkd).prop('checked', false);
        	}
		  $("#dose").val('');
		});

		$("#printdata").click(function(){
	      setTimeout(function(){ $("#printdata").show(); }, 2000);
	      $("#printdata").hide();

	      window.print();
	    });
		var CustomerIDArray=[]; 
	    $('.btn_select').change(function() {
	    	var id = $(this).attr('data-attr');
	    	
	    	
            
                if($(this).is(':checked')){
                    CustomerIDArray.push(id);
                }else{
                	var index = CustomerIDArray.indexOf(id);
					if (index >= 0) {
					  CustomerIDArray.splice( index, 1 );
					}
                }

            $("#selectedRecordId").val(CustomerIDArray);
       	
    	});
})
</script>

<style type="text/css">
	.modal-sm{
		width: 350px !important;
	}
</style>