<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<?php init_head();?>
<style>
  .edit-d{
	margin-top: 25px;
}
</style>
<?php function string_between_two_string($str, $starting_word, $ending_word)
{
    $subtring_start = strpos($str, $starting_word);
    //Adding the starting index of the starting word to
    //its length would give its ending index
    $subtring_start += strlen($starting_word); 
    //Length of our required sub string
    $size = strpos($str, $ending_word, $subtring_start) - $subtring_start; 
    // Return the substring from the index substring_start of length size
    return substr($str, $subtring_start, $size); 
}?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin font-bold"><?php echo _l($title); ?>  traslation</h4>
          <hr />
          <div class="row">
            <?php foreach($content as $key => $value){ 
              if($value!=''){ 
                $lang_name = explode('=', $value ); ?>
                <?php echo form_open('languages/changeline/'); ?>
                  <div class="col-md-10">
                    <div class="form-group" app-field-wrapper="value"><label for="value" class="control-label">
                      <?php echo $lang_name[0] ?>
                      </label> 
                      <input type="text" id="value" name="value" class="form-control" value="<?php echo str_replace("'","",ltrim($lang_name[1])) ?>">
                    </div>
                  </div>
                  <div class="col-md-2 ">
                    <input type="hidden" name="key" id="key" value="<?php echo string_between_two_string($lang_name[0], "'", "'") ?>">
                    <input type="hidden" name="lang" id="lang" value="<?php echo $title ?>">
                        <div class="edit-d">
                          <div>
                          <input type="submit" name="submit" value="Edit" class="form-btn" >
                          </div>
                        </div>
                  </div>
                <?php echo form_close();?>
               <?php }?>
            <?php }?>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>


<!-- /.modal -->
<?php init_tail(); ?>
</body>
</html>
<?php require 'modules/accounting/assets/js/transfer/manage_js.php'; ?>
