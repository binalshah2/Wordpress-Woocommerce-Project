<?php
    $toEmail = "info@crane.bb";
    $subject = "Moco - Buy A Container";
    $header  = "From:info@crane.bb \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

    $message  = "<div><img src='https://moco.bb/wp-content/uploads/2020/04/cropped-logo.png' /></div> \r\n";
    $message .= "<div><strong>Name :- ".$_POST['userName']."</strong></div>\r\n";
    $message .= "<div><strong>Phone :- ".$_POST["userPhone"]."</strong></div> \r\n";
    $message .= "<div><strong>E-mail :- ".$_POST["userEmail"]."</strong></div>\r\n";
    $message .= "<div><strong>Container Category :- ".$_POST['contCat']."</strong></div>\r\n";
    $message .= "<div><strong>Storage Size :- ".$_POST['contSize']."</strong></div>\r\n";
   
   

    if(mail($toEmail, $subject, $message, $header)) {
        print "<p class='success'>Mail Sent.</p>";
    } else {
        print "<p class='Error'>Problem in Sending Mail.</p>";
    }
?>