<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>
	<?php
	//if(get_the_ID()=="895")
	//{
	?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">
		<div class="row bot-fotter contact_f">
				 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				  <div class="logo"><img src="<?php echo get_site_url();?>/wp-content/themes/storefront/assets/images/mococontainerstoragelogo.png"></div>
				 </div>
				 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				  <h4>Company Info</h4>
				  <a href="<?php echo get_site_url();?>/about-us/">About Us</a>
				  <a href="<?php echo get_site_url();?>/contact/">Contact Us</a>
				  <a href="<?php echo get_site_url();?>/terms-privacy-policy/">Terms & Privacy Policy</a>
				  <!--<a href="<?php echo get_site_url();?>/delivery-method-timeframe/">Contact Us</a>-->
				 </div>
				 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				  <h4>Our Services</h4>
				  <a href="#">Rentals</a>
				  <a href="<?php echo get_site_url();?>/sales/">Sales</a>
				  <a href="<?php echo get_site_url();?>/contact/">Conversions</a>
				  <a href="<?php echo get_site_url();?>/sales/" class="rc_req_qut">
					   		Request A Quote
					</a>
				 </div>
				 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<h4>
					  	
					   	<img src="<?php echo get_site_url(); ?>/wp-content/themes/storefront/assets/images/payment_icon.png" alt="Payment We Support">
					   	<br/>
					   	<img src="<?php echo get_site_url(); ?>/wp-content/themes/storefront/assets/images/ssl_encrypt.png" alt="Payment We Support">
					   	<br />
					   	<a href="https://aws.amazon.com/">Site Hosted On: Amazon Webservices</a>
					</h4>
				 	
				 </div>

				 </div>
				 </div>

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			//do_action( 'storefront_footer' );
			?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->
	<?php
	//}	
	?>
	<?php //do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-3.4.1.slim.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> 
				<script src="<?php echo get_template_directory_uri(); ?>/assets/js/popper.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> 
				<script src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php //wp_footer(); ?>
<div class="modal fade" id="your-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            	<h1>Thank You For Contacting Us</h1>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
		function sendContact() {
		    var valid;	
		    valid = validateContact();
		    if(valid) {
		        jQuery.ajax({
		            url: "<?php echo site_url(); ?>/wp-content/themes/storefront/contact_mail.php",
		            data:'userName='+$("#userName").val()+'&userEmail='+
		            $("#userEmail").val()+'&userPhone='+$("#userPhone").val()+'&mocomsg='+$("#mocomsg").val(),
		            type: "POST",
		            success:function(data){
		            	document.getElementById("userName").value="";
		            	document.getElementById("userEmail").value="";
		            	document.getElementById("userPhone").value="";
		            	document.getElementById("mocomsg").value="";
		            	 $("#userName").css('background-color','');
		            	 $("#userEmail").css('background-color','');
		            	 $("#userPhone").css('background-color','');
		            	 $("#mocomsg").css('background-color','');
		            	 $("#chkmocop").css('background-color','');
		                $('#your-modal').modal('toggle');
		            },
		            error:function (){}
		        });
		    }
		}
		function validateContact() {
		    var valid = true;	

		    $(".demoInputBox").css('background-color','');
		    $(".info").html('');
		    if(!$("#userName").val()) {
		        $("#userName").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userPhone").val()) {
		        $("#userPhone").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val()) {
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
		        $("#userEmail-info").html("(invalid)");
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#mocomsg").val()) {
		        $("#content-info").html("(required)");
		        $("#mocomsg").css('background-color','#FF0000');
		        valid = false;
		    }
		   if (!$('#mocoagree:checked').val())
		   {
		   		$("#chkmocop").css('background-color','#FF0000');
		   		valid = false;
		   }
		        
		        
		   
		    return valid;
		}
		</script>




<div class="modal fade" id="sales-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            	<h1>Thank You For Request Sales Quote</h1>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
		function sendSalesContact() {
		    var valid;	
		    valid = validateSalesContact();
		    if(valid) {
		        jQuery.ajax({
		            url: "<?php echo site_url(); ?>/wp-content/themes/storefront/contactsales_mail.php",
		            data:'contCat='+$("#contCat").val()+'&contSize='+$("#contSize").val()+
		            	'&userName='+$("#userName").val()+'&userEmail='+$("#userEmail").val()+
		            	'&userPhone='+$("#userPhone").val(),
		            type: "POST",
		            success:function(data){
		            	document.getElementById("userName").value="";
		            	document.getElementById("userEmail").value="";
		            	document.getElementById("userPhone").value="";
		            	document.getElementById("contCat").value="";
		            	document.getElementById("contSize").value="";
		            	$("#contCat").css('background-color','');
		            	$("#contSize").css('background-color','');
		            	$("#userName").css('background-color','');
		            	$("#userEmail").css('background-color','');
		            	$("#userPhone").css('background-color','');
		                $('#sales-modal').modal('toggle');
		            },
		            error:function (){}
		        });
		    }
		}
		function validateSalesContact() {
		    var valid = true;	
		    if(!$("#contCat").val()) {
		        $("#contCat").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#contSize").val()) {
		        $("#contSize").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userName").val()) {
		        $("#userName").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val()) {
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
		        $("#userEmail-info").html("(invalid)");
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userPhone").val()) {
		        $("#userPhone").css('background-color','#FF0000');
		        valid = false;
		    }
		    return valid;
		}
		</script>


<div class="modal fade" id="rental-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            	<h1>Thank You For Request Rental Quote</h1>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
		function sendRentalContact() {
		    var valid;	
		    valid = validateRentalContact();
		    if(valid) {
		    	if(document.getElementById("onsite").checked)
		    	{
		    		var onsite = document.getElementById("onsite").value;
		    	}
		    	if(document.getElementById("offSite").checked)
		    	{
		    		var offSite = document.getElementById("offSite").value;
		    	}
		    	
		        jQuery.ajax({
		            url: "<?php echo site_url(); ?>/wp-content/themes/storefront/contactrental_mail.php",
		            data:'onsite='+onsite+'&offSite='+offSite+'&storCont='+$("#storCont").val()+
		            	'&contCap='+$("#contCap").val()+
		            	 '&contSize='+$("#contSize").val()+'&userName='+$("#userName").val()+'&userEmail='+$("#userEmail").val()+'&userPhone='+$("#userPhone").val(),
		            type: "POST",
		            success:function(data){
		            	document.getElementById("onsite").checked=false;
		            	document.getElementById("offSite").checked=false;
		            	document.getElementById("storCont").value="";
		            	document.getElementById("contCap").value="";
		            	document.getElementById("contSize").value="";
		            	document.getElementById("userName").value="";
		            	document.getElementById("userEmail").value="";
		            	document.getElementById("userPhone").value="";
		            	
		            	$("#storCont").css('background-color','');
		            	$("#contCap").css('background-color','');
		            	$("#contSize").css('background-color','');
		            	$("#userName").css('background-color','');
		            	$("#userEmail").css('background-color','');
		            	$("#userPhone").css('background-color','');
		                $('#rental-modal').modal('toggle');
		            },
		            error:function (){}
		        });
		    }
		}
		function validateRentalContact() {
		    var valid = true;	
		    if(!$("#storCont").val()) {
		        $("#storCont").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#contCap").val()) {
		        $("#contCap").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#contSize").val()) {
		        $("#contSize").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userName").val()) {
		        $("#userName").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val()) {
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userEmail").val().match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/)) {
		        $("#userEmail-info").html("(invalid)");
		        $("#userEmail").css('background-color','#FF0000');
		        valid = false;
		    }
		    if(!$("#userPhone").val()) {
		        $("#userPhone").css('background-color','#FF0000');
		        valid = false;
		    }
		    return valid;
		}
		</script>
<!-- code added by binal pickup_location-->

<script>
jQuery(document).ready(function($){

	$(".moco_plus,.moco_minus").on("click",function(){
		var currentVal = parseInt($("#moco_inventory_quantity").val());

		if ($(this).hasClass('moco_plus'))
		{
			$("#moco_inventory_quantity").val(currentVal+1);
		}
		else
		{
			if (currentVal>1)
			{
				$("#moco_inventory_quantity").val(currentVal-1);
			}
		}

		$("form.cart").trigger("change");
	});
	$(".pickup_location").on("change",function(){
		console.log($(this).val());
		if ($(this).val()=="OnSite|OnSite|0")
		{
			$(".OffSite").hide();
			$(".OnSite").show();
			$(".On_Site").hide();
			var onn = $('.OnSite');
			$(onn[0]).attr('checked', true);
			$(onn[0]).prop('checked', true);
		}
		else
		{
			$(".OnSite").hide();
			$(".OffSite").show();
			var off = $('.OffSite');
			$(off[0]).attr('checked', true);
			$(off[0]).prop('checked', true);

			
			
		}
	});
	var place_order_clicked = false;
	$('#place_order').click(function(e){

		/*if(place_order_clicked)
		{
			e.preventDefault();
			$(this).attr("disabled",true);
		}
		place_order_clicked=true;
		*/
	});
});
</script>

<script src="<?php echo site_url(); ?>/wp-content/plugins/woocommerce-rental-and-booking/assets/js/accounting.js"></script> 

</body>
</html>
