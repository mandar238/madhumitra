
<!-- begin #content -->
<div id="content" class="content">
    <!-- begin row -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- begin panel -->
            <div class="panel panel-pvr">
                <div class="panel-heading">
                    <h4 class="panel-title">Add BSL Estimation</h4>
                </div>
                <div class="panel-body">
                    
                    <form action="<?php echo base_url();?>index.php/bslestimation/saveBslestimation" method="POST" autocomplete="off">
                        <!-- <div class="row">
                            <div class="form-group col-md-12">
                                <label>Select Patient</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="">Select Patient</option>
                                    <?php foreach($userData as $key => $value){ ?>
                                        <option value="<?=$value['id'];?>" <?php //echo ($this->session->userdata['selectedUser']==$value['id'])?" selected=' selected'":""?>><?=$value['username'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="errorMsg"><?php //echo form_error('user_id'); ?></span>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Date</label>
                                <div class="input-group date date_picker">
                                    <input type="text" class="form-control" name="date" value="<?php echo set_value('date') ?>" placeholder="Select Date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <span class="errorMsg"><?php echo form_error('date'); ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Time</label>
                                <!-- <input type="text" class="form-control time_picker" name="time" placeholder="Select Time" value="<?php //echo set_value('time') ?>"> -->
                                <div class="input-group bootstrap-timepicker timepicker">
                                    <input  type="text" name="time" value="<?php echo set_value('time') ?>" class="timepicker1 form-control input-small">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                </div>
                                <span class="errorMsg"><?php echo form_error('time'); ?></span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>BSL value (MG %)</label>
                                <input type="text" class="form-control" name="bsl_value" onkeypress="return isNumber(event)" placeholder="Enter BSL Value" maxlength="5" value="<?php echo set_value('bsl_value') ?>"/>
                                <span class="errorMsg"><?php echo form_error('bsl_value'); ?></span>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-sm btn-success m-r-5">Save</button>
                                <a href="<?php echo base_url();?>index.php/bslestimation" class="btn btn-sm btn-warning m-r-5" title="Cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
            
        </div>
    </div>
    <!-- end row -->
</div>
    <!-- end #content -->