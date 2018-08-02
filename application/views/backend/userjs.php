<script type="text/javascript">
$(".btn_status").click(function(){
        var id = $(this).attr('data-attr');
        var url = 'updateStatus';
        var status = this.value;
        if(status == 1){
          status = 0;
        }else{
          status = 1;
        }
          $.ajax({
                  url : url,
                  type : 'POST',           
                  data :  {
                     id : id, status: status
                    },
                  success: function(data) {
                     //  location.reload();
                      },
                  error: function() {
                    //  location.reload();
                  }

             });
    });

    $(".btn_delete").click(function(){
        var id = $(this).attr('data-attr');
        var url = 'deleteUser' 
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
    
    $('.userTbl').dataTable({
        "iDisplayLength": 10,
        "scrollY":        "600px",
        "scrollX":        true,
        "scrollCollapse": true,
        "paging":         false,
        "fixedColumns":   {
            "leftColumns": 1,
            "rightColumns": 1
        }
    });
</script>