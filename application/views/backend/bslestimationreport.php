
<!-- begin #content -->
<div id="content" class="content">
    <!-- begin row -->
    <div class="row">
        <div class="col-md-12">
            <!-- begin panel -->
            <div class="panel panel-pvr">
                <div class="panel-heading">
                    <h4 class="panel-title">BSL Estimation Report</h4>
                </div>
                <div class="panel-body">
                    
                    <form action="<?php echo base_url();?>index.php/reports/generateBslReport" method="POST" autocomplete="off">
                    <input type="hidden" id="user_id" name="user_id" value="<?=$this->session->userdata['selectedUser'];?>" />
                        <div class="row">
                            <!-- <div class="form-group col-md-3">
                                <label>Select Patient</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="">Select Patient</option>
                                    <?php foreach($userData as $key => $value){ ?>
                                        <option value="<?=$value['id'];?>" <?php //echo ($this->session->userdata['selectedUser']==$value['id'])?" selected=' selected'":""?>><?=$value['username'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="errorMsg"></span>
                            </div> -->

                            <div class="form-group col-md-4">
                                <label>From Date</label>
                                <div class="input-group date date_picker1">
                                    <input type="text" class="form-control" id="fdate" name="fromDate" placeholder="From Date"/>

                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <span class="errorMsg"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>To Date</label>
                                <div class="input-group date date_picker">
                                    <input type="text" class="form-control" id="todate" name="toDate" placeholder="To Date"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <span class="errorMsg"></span>
                            </div>
                            <div class="form-group col-md-4" style="margin-top: 25px;">
                                <button type="button" id="showbslChart" class="btn btn-sm btn-success pull-left m-r-5">Show</button>
                                <button type="submit" class="btn btn-sm btn-success pull-left m-r-5">Report</button>
                                <a href="<?php echo base_url();?>index.php/reports/bslreportform" class="btn btn-sm btn-warning pull-left m-r-5" title="Cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end panel -->
        </div>
    </div>
    <div class="row" id="bslChart">
         <!-- begin col-12 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-pvr">
                    <div class="panel-body">
                        <div class="p-0 high_chart" id="dynamic_update">
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