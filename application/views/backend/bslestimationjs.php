<script type="text/javascript">
$(".btn_delete").click(function(){
        var id = $("#user_id").val();
        var url = 'bslestimation/deletebslestimation' 
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

    $('.bslTbl').dataTable({
        "iDisplayLength": 10
    });

    var ChartHigh = function () {
        "use strict";
        return {
            init: function () {
                high_chart();
                animateamChart();
                dynamic_chart();
            }
        }
    }();
    $(function () {
        ChartHigh.init();
    });
</script>