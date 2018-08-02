    <!-- begin #content -->

<div id="content" class="content">

        <!-- begin row -->
        <div class="row">
            <!-- begin col-12 -->
            <div class="col-md-12">
                <!-- begin panel -->
                <div class="panel panel-pvr">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <i class="material-icons" data-click="panel-reload">refresh</i>
                            <i class="material-icons" data-click="panel-collapse">import_export</i>
                         </div>
                        <h4 class="panel-title">User List</h4>
                        <form action="<?php echo base_url();?>index.php/user/updateuserLimit" method="POST" autocomplete="off">
                        <div class="col-md-9 col-md-offset-2">
                            <label>Limit Of add patient per user: </label>
                            <input type="text" onkeypress="return isNumber(event)" class="form-control" style="width:100px;display:inline-block;" name="no_of_emp" id="no_of_emp" maxlength="2" value="<?php echo $userLimit;?>" required/>
                            <button type="button" class="btn btn-danger" id="enableText">Enable</button>
                            <button type="submit" class="btn btn-success m-r-5">Update</button>
                        </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <table class="userTbl table table-striped table-bordered data_table" style="width:100%">
                                <thead>
                                <tr>
                                    <th class="text-center">No.
                                    </th>
                                    <th>User Details</th>
                                    <th>Birthdate</th>
                                    <th>Weight/Height</th>
                                    <th>Location</th>
                                    <th>Registered On</th>
                                    <th class="text-center">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                     if(!empty($userData))
                                     {
                                        $count = 1;
                                        foreach ($userData as $key => $value) {
                                    ?>
                                <tr>
                                    <td class="text-center"><?=$count?></td>
                                    <td>
                                        <div class="">
                                            <span>
                                                <?=$value['username']; ?>
                                            </span>
                                            <br>
                                            <span class="f-s-12" style="word-wrap: break-word;">
                                               <?=$value['email_id']; ?>
                                            </span>
                                            <span class="f-s-12">
                                                <?=$value['mobile_no']; ?>
                                            </span>
                                        </div>
                                    </td>
                                   
                                    <td>
                                         <?php if($value['birthdate'] != '' && $value['birthdate'] != "0000-00-00"){
                                        echo date('d-M-Y',strtotime($value['birthdate']));
                                        }else{ 
                                        echo '-';
                                        }?>
                                    </td>
                                    <td>
                                        Weight : <?=$value['weight']; ?> <br>
                                        Height : <?=$value['height']; ?>
                                    </td>
                                    <td>
                                        <?=$value['city_name']; ?>
                                    </td>
                                    <td>
                                       <?=date('d-M-Y',strtotime($value['created_at'])); ?>
                                    </td>
                                    <td  class="text-center"><label class="switch1">
                                        <input type="checkbox" class="btn_status" data-attr="<?php echo $value['id'];?>" value="<?=$value['user_status'];?>"<?=$value['user_status'] == '1' ? ' checked="checked"' : '';?>>
                                        <span class="slider round"></span>
                                        </label>

                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="btn_delete" data-attr="<?php echo $value['id'];?>" class="text-danger" title="Delete"><i class="material-icons">delete</i></a>
                                        <a href="#modal_form" class="open-changePassword" data-toggle="modal" data-id="<?php echo $value['id'];?>" data-pname="<?php echo $value['username'];?>" data-emailid="<?php echo $value['email_id'];?>" data-pmobileno="<?php echo $value['mobile_no'];?>" title="Change Password"><i class="material-icons">vpn_key</i></a>
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
                <!-- end panel -->
                </div>
            </div>
            <!-- end col-12 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end #content -->
</div>
<!-- end page container -->

<!-- #modal-form -->
                    

