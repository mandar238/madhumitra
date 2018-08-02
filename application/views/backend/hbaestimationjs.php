<script type="text/javascript">
$(".btn_delete").click(function(){
        var id = $("#user_id").val();
        var url = 'hbaestimation/deletehbaestimation' 
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

    $('.hbaTbl').dataTable({
        "iDisplayLength": 10
    });
</script>