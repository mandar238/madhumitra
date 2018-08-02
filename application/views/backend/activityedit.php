
<!-- begin #content -->
<div id="content" class="content">
    <!-- begin row -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- begin panel -->
            <div class="panel panel-pvr">
                <div class="panel-heading">
                    <h4 class="panel-title">Update Activity</h4>
                </div>
                <div class="panel-body">
                    
                    <form action="<?php echo base_url();?>index.php/activity/saveactivity" method="POST" autocomplete="off">
                        <input type="hidden" name="id" value="<?php echo set_value('id', $getactivityData['id']); ?>">
                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Activity Name</label>
                                <input type="text" class="form-control" id="activity_name" name="activity_name" placeholder="Enter Activity Name" value="<?php echo $getactivityData['activity_name']; ?>"/>
                                <span class="errorMsg"><?php echo form_error('activity_name'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Calories Spent</label>
                                <input type="text" class="form-control" id="calories_spent" name="calories_spent" placeholder="Enter Calories Spent" onkeypress="return isNumber(event)" maxlength="5" value="<?php echo $getactivityData['calories_spent']; ?>"/>
                                <span class="errorMsg"><?php echo form_error('calories_spent'); ?></span>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-sm btn-success  m-r-5">Save</button>
                                <a href="<?php echo base_url();?>index.php/activity" class="btn btn-sm btn-warning m-r-5" title="Cancel">Cancel</a>
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