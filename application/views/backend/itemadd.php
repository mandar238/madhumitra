
<!-- begin #content -->
<div id="content" class="content">
    <!-- begin row -->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <!-- begin panel -->
            <div class="panel panel-pvr">
                <div class="panel-heading">
                    <h4 class="panel-title">Add Item</h4>
                </div>
                <div class="panel-body">
                    
                    <form action="<?php echo base_url();?>index.php/item/saveitem" method="POST" autocomplete="off">
                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Select Food Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    <?php foreach($categoryData as $key => $value){ ?>
                                        <option value="<?=$value['id'];?>" <?php echo (set_value('category_id')==$value['id'])?" selected=' selected'":""?>><?=$value['category_name'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="errorMsg"><?php echo form_error('category_id'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Item Name</label>
                                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter Item Name" value="<?php echo set_value('item_name'); ?>"/>
                                <span class="errorMsg"><?php echo form_error('item_name'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Quantity Label </label>
                                <input type="text" class="form-control" id="quantity_label" name="quantity_label"  placeholder="Enter quantity label" value="<?php echo set_value('quantity_label'); ?>"/>
                                <span class="errorMsg"><?php echo form_error('quantity_label'); ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Calories </label>
                                <input type="text" class="form-control" id="calories" name="calories" onkeypress="return isNumber(event)" placeholder="Enter Calories" maxlength="5" value="<?php echo set_value('calories'); ?>"/>
                                <span class="errorMsg"><?php echo form_error('calories'); ?></span>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" class="btn btn-sm btn-success m-r-5">Save</button>
                                <a href="<?php echo base_url();?>index.php/item" class="btn btn-sm btn-warning m-r-5" title="Cancel">Cancel</a>
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