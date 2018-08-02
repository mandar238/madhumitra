 <!-- begin #content -->
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-pvr">
                    <div class="panel-body">
                    <div class="row">
                            <div class="col-md-3">
                                <img height="100px" width="130px" src="<?php echo base_url();?>assets/img/logo.png">
                            </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-4">
                                <h3>Madhumitra</h3>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Patient Name :</label> &nbsp; <span><?=ucfirst($userData['username']);?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact No :</label> &nbsp; <span><?=$userData['mobile_no']?></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Date :</label> &nbsp; From : <span><?=$_POST['fromDate']?></span> &nbsp;to &nbsp;<span><?=$_POST['toDate']?></span>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        <!-- begin row -->
        <div class="row">
            

            <!-- begin col-12 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-pvr">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                        <?php 
                                     if(!empty($bslreportData))
                                     {?>
                            <form action="<?php echo base_url();?>index.php/reports/bslPDF" method="POST" autocomplete="off">
                            <a href="javascript:void(0)" id="printdata" class="btn btn-sm btn-warning pull-left m-r-5" title="Print"><i class="material-icons">print</i></a>
                            <input type="hidden" name="fromDate" value="<?=$_POST['fromDate'];?>" />
                            <input type="hidden" name="toDate" value="<?=$_POST['toDate'];?>" />
                            <input type="hidden" name="user_id" value="<?=$this->session->userdata['selectedUser'];?>" />
                            <button type="submit" id="pdfbtn" class="btn btn-sm btn-success pull-left m-r-5" title="Download PDF"><i class="material-icons">picture_as_pdf</i></button>
                            <a href="javascript:void(0)" id="ebtn" class="btn btn-sm btn-warning pull-left m-r-5 open_custombox" data-effect="slide" title="Share"><i class="material-icons">email</i></a>
                            </form>
                            <?php }?>
                        </div>
                        <h4 class="panel-title">BSL Report</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="bslTbl table table-striped table-bordered data_table width-full">
                                <thead>
                                <tr>
                                    <th class="text-center">Sr.No.
                                    </th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>BSL (MG %)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                     if(!empty($bslreportData))
                                     {
                                        $count = 1;
                                        foreach ($bslreportData as $key => $value) {
                                    ?>
                                <tr>
                                    <td class="text-center"><?=$count?></td>
                                    <td>
                                        <?=date('d-M-Y',strtotime($value['date'])); ?>
                                    </td>
                                    
                                    <td>
                                        <?=$value['time']; ?>
                                    </td>
                                    <td>
                                        <?=$value['bsl_value']; ?>
                                    </td>
                                </tr>
                                 <?php  
                                      $count++;
                                      }
                                   }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-12 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end #content -->
</div>
<!-- end page container -->

 <!-- Modal -->
                        <div class="modal" id="custom_box_modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <form action="<?php echo base_url();?>index.php/reports/sendbslPDF" method="POST" autocomplete="off">
                                    <input type="hidden" name="fromDate" value="<?=$_POST['fromDate'];?>" />
                                    <input type="hidden" name="toDate" value="<?=$_POST['toDate'];?>" />
                                    <input type="hidden" name="user_id" value="<?=$_POST['user_id'];?>" />
                                    <div class="modal-header">
                                        <h5 class="modal-title pull-left">Send Report</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                onclick="Custombox.modal.close();" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $doctorData=$this->doctordata->getRows();
                                        ?>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Select Doctor</label>
                                                <select class="form-control" id="doctor_id" name="doctor_id">
                                                    <?php foreach($doctorData as $key => $value){ ?>
                                                        <option value="<?=$value['id'];?>"><?='Dr.'.$value['fname'].' '.$value['lname'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning"
                                                onclick="Custombox.modal.close();">
                                            Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>