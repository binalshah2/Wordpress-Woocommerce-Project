<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<div class="rc_cust_wrap">
  <h1>Find Customers</h1>
</div>
<?php
 global $wpdb;
 if($_GET['id']!='')
 {
?>  
    <?php
        //echo "<pre>";
        //print_r($_POST);
        $update ="Update crn_moco_customers Set 
                  Description             = '".$_POST['Description']."' , 
                  ContactName             = '".$_POST['ContactName']."' ,
                  ContactDetails          = '".$_POST['ContactDetails']."' ,
                  AccType                 = '".$_POST['AccType']."' ,
                  CreditLimit             = '".$_POST['CreditLimit']."' ,
                  Email                   = '".$_POST['Email']."' ,
                  CustomerOldEmail        = '".$_POST['CustomerOldEmail']."' ,
                  ContactTitle            = '".$_POST['ContactTitle']."' ,
                  ContactInitials         = '".$_POST['ContactInitials']."' ,
                  ContactPosn             = '".$_POST['ContactPosn']."' ,
                  ChartName               = '".$_POST['ChartName']."' ,
                  PayName                 = '".$_POST['PayName']."' ,
                  Address1                = '".$_POST['Address1']."' ,
                  Address2                = '".$_POST['Address2']."' ,
                  Address3                = '".$_POST['Address3']."' ,
                  City                    = '".$_POST['City']."' ,
                  State                   = '".$_POST['State']."' ,
                  BadCreditFlag           = '".$_POST['BadCreditFlag']."' ,
                  IsCashCustomer          = '".$_POST['IsCashCustomer']."' ,
                  SecondaryEmailAddress   = '".$_POST['SecondaryEmailAddress']."' ,
                  IsPrefferedCustomer     = '".$_POST['IsPrefferedCustomer']."' ,
                  EmailPermission         = '".$_POST['BadCreditFlag']."' ,
                  IsPurchaseOrder         = '".$_POST['IsCashCustomer']."' ,
                  IsCreditFlag            = '".$_POST['SecondaryEmailAddress']."' ,
                  ARBalance               = '".$_POST['IsPrefferedCustomer']."' 
                  WHERE id='".$_POST['id']."'";


                  $wpdb->query($update);

    ?>
    <form name="frmmoco" id="frmmoco" method="post">
        <div class="container-fluid" style="float:left; width:50%;">
            <?php
            
            $query = "SELECT * FROM crn_moco_customers where id='".$_GET['id']."'";
            $result = $wpdb->get_results( $query );

                      // Loop through each WC_Order object
                      foreach( $result as $order )
                      {
                        
                          ?>
                          <div class="row">
                            <div class="row_label">ID</div>
                              <div class="row_txt">
                                <input type="text" name="MId" value="<?php echo $order->MId;?>" readonly>
                              </div>
                          </div>
                          <div class="row">
                            <div class="row_label">T1Customer</div>
                              <div class="row_txt">
                                <input type="text" name="T1Customer" value="<?php echo $order->T1Customer;?>" readonly>
                              </div>
                          </div>
                          <div class="row">
                            <div class="row_label">Description</div>
                              <div class="row_txt">
                                <textarea name="Description"><?php echo $order->Description;?></textarea>
                              </div>
                          </div>
                          <div class="row">
                            <div class="row_label">Contact Name</div>
                              <div class="row_txt">
                                <input type="text" name="ContactName" value="<?php echo $order->ContactName;?>">
                              </div>
                          </div>
                          <div class="row">
                            <div class="row_label">Contact Details</div>
                              <div class="row_txt">
                                <input type="text" name="ContactDetails" value="<?php echo $order->ContactDetails;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">AccType</div>
                              <div class="row_txt">
                                <input type="text" name="AccType" value="<?php echo $order->AccType;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">CreditLimit</div>
                              <div class="row_txt">
                                <input type="text" name="CreditLimit" value="<?php echo $order->CreditLimit;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">Email</div>
                              <div class="row_txt">
                                <input type="text" name="Email" value="<?php echo $order->Email;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">CustomerOldEmail</div>
                              <div class="row_txt">
                                <input type="text" name="CustomerOldEmail" value="<?php echo $order->CustomerOldEmail;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">ContactTitle</div>
                              <div class="row_txt">
                                <input type="text" name="ContactTitle" value="<?php echo $order->ContactTitle;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">ContactInitials</div>
                              <div class="row_txt">
                                <input type="text" name="ContactInitials" value="<?php echo $order->ContactInitials;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">ContactPosn</div>
                              <div class="row_txt">
                                <input type="text" name="ContactPosn" value="<?php echo $order->ContactPosn;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">Phone</div>
                              <div class="row_txt">
                                <input type="text" name="Phone" value="<?php echo $order->Phone;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">ChartName</div>
                              <div class="row_txt">
                                <input type="text" name="ChartName" value="<?php echo $order->ChartName;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">PayName</div>
                              <div class="row_txt">
                                <input type="text" name="PayName" value="<?php echo $order->PayName;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">Address1</div>
                              <div class="row_txt">
                                <input type="text" name="Address1" value="<?php echo $order->Address1;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">Address2</div>
                              <div class="row_txt">
                                <input type="text" name="Address2" value="<?php echo $order->Address2;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">Address3</div>
                              <div class="row_txt">
                                <input type="text" name="Address3" value="<?php echo $order->Address3;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">City</div>
                              <div class="row_txt">
                                <input type="text" name="City" value="<?php echo $order->City;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">State</div>
                              <div class="row_txt">
                                <input type="text" name="State" value="<?php echo $order->State;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">BadCreditFlag</div>
                              <div class="row_txt">
                                <input type="text" name="BadCreditFlag" value="<?php echo $order->BadCreditFlag;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">IsCashCustomer</div>
                              <div class="row_txt">
                                <input type="text" name="IsCashCustomer" value="<?php echo $order->IsCashCustomer;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">SecondaryEmailAddress</div>
                              <div class="row_txt">
                                <input type="text" name="SecondaryEmailAddress" value="<?php echo $order->SecondaryEmailAddress;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">IsPrefferedCustomer</div>
                              <div class="row_txt">
                                <input type="text" name="IsPrefferedCustomer" value="<?php echo $order->IsPrefferedCustomer;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">EmailPermission</div>
                              <div class="row_txt">
                                <input type="text" name="EmailPermission" value="<?php echo $order->EmailPermission;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">IsPurchaseOrder</div>
                              <div class="row_txt">
                                <input type="text" name="IsPurchaseOrder" value="<?php echo $order->IsPurchaseOrder;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">IsCreditFlag</div>
                              <div class="row_txt">
                                <input type="text" name="IsCreditFlag" value="<?php echo $order->IsCreditFlag;?>">
                              </div>  
                          </div>
                          <div class="row">
                            <div class="row_label">ARBalance</div>
                              <div class="row_txt">
                                <input type="text" name="ARBalance" value="<?php echo $order->ARBalance;?>">
                              </div>  
                          </div>
                          <?php
                      }
                      ?>
                        <br/>
                        <div class="row">
                            <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                            <div>
                                <input type="submit" name="Save" value="Save">
                                <input type="button" name="Cancel" value="Cancel" onclick="callcancel();">
                            </div>

                        </div>
              
        </div>
        <div class="container-fluid" style="float:left; width:50%;">
            <?php 
            $query = "SELECT * FROM crn_moco_customers where id='".$_GET['id']."'";
            $result = $wpdb->get_results( $query );
            ?>
            <div class="register_mail">
                <?php
                $link = get_site_url().'/registration/?company='.$result[0]->MId.'&email='.$result[0]->Email.'&username='.$result[0]->T1Customer;
                ?>
                  <div class="rc_message_01">
                    Email Template for customer to self-register on website. Just copy & paste email below and send to customer:
                  </div>
                  <h3>Please click below link to register with our site</h3>
                  <p><a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
                 
                
            </div>

            <div class="reset_password">
                <div class="rc_message_01">
                    Email Template for customer to reset password. Just copy & paste email below and send to customer:
                </div>
               <h3>Please click below link to Reset your password</h3></br>
               <p><a href="<?php echo wp_lostpassword_url().'?email='.$result[0]->Email; ?>"><?php echo wp_lostpassword_url().'?email='.$result[0]->Email; ?></a></p>
            </div>
        </div>
    </form>
<?php
 }
 else
 {
?>
<div class="container-fluid">
      <div class="row">
        
        <div class="col-12 col-sm-4 col-lg-2 rc_srch_cust">
          <input type="text" name="search_text" id="search_text" placeholder="Search by ID,T1 Number or E-mail Address">
        </div>
      
      </div>
      <div id="result"></div>
        
    
</div>
<?php
 }
?>

<script type="text/javascript">
  function callcancel()
  {
    window.location.href = "<?php echo admin_url(); ?>admin.php?page=customers";

  }
</script>
<script>
jQuery(document).ready(function(){

 //load_data();

 function load_data(query)
 {
  jQuery.ajax({
   url:"<?php echo get_site_url(); ?>/wp-content/plugins/mocosearch/includes/livesearch.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    jQuery('#result').html(data);
   }
  });
 }
 jQuery('#search_text').keyup(function(){
  var search = jQuery(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   //load_data();
  }
 });
});
</script>
<style type="text/css">
  .rc_cust_wrap {
    display: block;
    float: left;
    width: 100%;
  }
  .rc_cust_wrap h1 {
    font-size: 18px;
    font-weight: 600;
  }
  .rc_srch_cust {
    display: block;
    float: left;
    width: 100%;
    padding-left: 0px;
  }
  .rc_srch_cust input {
    width: 380px;
  }
  div#result {
    display: block;
    float: left;
    width: 100%;
    margin-top: 2rem;
  }
  div#result .row {
    display: block;
    float: left;
    /* padding: 2rem !important; */
    width: 100%;
    padding: 1rem 0rem;
    border-bottom: 1px solid #888484;
}
.rc_rslt_action {
    text-align: right;
    font-weight: 600;
    color: #000;
}
form#frmmoco {
    display: block;
    float: left;
    width: 100%;
}
form#frmmoco .row{
  margin: 20px 0px
}
form#frmmoco .row input, form#frmmoco .row textarea{
  width: 90%;
}
input[type="submit"] {
    width: 45% !important;
}
input[name="Cancel"] {
    width: 45% !important;
}
.register_mail {
    border: 1px solid #040404;
    padding: 10px 10px;
}
.rc_message_01 {
    background: #ccc;
    padding: 10px;
    font-weight: 600;
}
.register_mail h3, .reset_password h3{
  font-size: 15px;
}
.reset_password {
    border: 1px solid #040404;
    padding: 10px 10px;
    margin-top: 20px;
}
</style>