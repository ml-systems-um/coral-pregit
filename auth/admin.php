<?php

/*
**************************************************************************************************************************
** CORAL Authentication Module v. 1.0
**
** Copyright (c) 2011 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


session_start();

include_once 'directory.php';

if (isset($_SESSION['loginID'])){
	$loginID=$_SESSION['loginID'];
    $user = new User(new NamedArguments(array('primaryKey' => $loginID)));
}

if (isset($user) && ($user->isAdmin) && ($user->getOpenSession())){

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CORAL Authentication</title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="images/favicon.ico" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:100,400,300,600,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link  rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="../js/plugins/Gettext.js"></script>
<?php
   // Add translation for the JavaScript files
    global $http_lang;
    $str = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,5);
    $default_l = $lang_name->getLanguage($str);
    if($default_l==null || empty($default_l)){$default_l=$str;}
    if(isset($_COOKIE["lang"])){
        if($_COOKIE["lang"]==$http_lang && $_COOKIE["lang"] != "en_US"){
            echo "<link rel='gettext' type='application/x-po' href='./locale/".$http_lang."/LC_MESSAGES/messages.po' />";
        }
    }else if($default_l==$http_lang && $default_l != "en_US"){
            echo "<link rel='gettext' type='application/x-po' href='./locale/".$http_lang."/LC_MESSAGES/messages.po' />";
    }
?>
</head>
<body>
<noscript><font face=arial><?php echo _("JavaScript must be enabled in order for you to use CORAL. However, it seems JavaScript is either disabled or not supported by your browser. To use CORAL, enable JavaScript by changing your browser options, then")." <a href=''>"._("try again")."</a>."?></font></noscript>

<center>
<form name="reportlist" method="post" action="report.php">

	<br />

	<div id="title-div">
        <div id="img-title"><img src="images/authtitle.png" /></div>
        <div id="text-title"><?php echo _("eRM Authentication"); ?></div>
        <div class="clear"></div>
    </div>

	<div class='bordered' style='width:416px;'>

		<br />
		<div class='headerText' style='text-align: left;margin:0 0 3px 60px;'><?php echo _("Users")?></div>
		<div class='smallDarkRedText' style='margin-bottom:5px;'>* <?php echo _("Login ID must match the login ID set up in the modules")?></div>


		<div style='text-align:left;margin:0px 60px 60px 38px;' id='div_users'>
            <br />
            <br />
            <img src='images/circle.gif'>  <span style='font-size:90%'><?php echo _("Processing...")?></span>
		</div>
	</div>
    <br>
    <div style="margin:auto;width:416px;">
        <?php 
            $fileName = "../common/configuration.ini";
			$configFile = parse_ini_file($fileName, true);
            $menuSettingExists = (isset($configFile['settings']['displayFullMenu']));
            if($menuSettingExists){ 
                $menuSetting = ($configFile['settings']['displayFullMenu'] == 'Y') ? 'checked' : '';
        ?>
            <label for="showAllMenu" style="width:75%;margin-left:0px;"><?php echo _("Show uninstalled menu options")?></label>
            <input style="margin-top:0px;" type="checkbox" onchange="showMenuChange()" id="showAllMenu" <?php echo $menuSetting?>/>
        <?php } else { ?> 
            <input style="width:100%;padding:1rem;" type="button" value="Add Menu Options Setting" onclick="updateMenuSettings()"/>
        <?php } ?>
    </div>
    <br>
    <div class='boxRight'>
		<p class="fontText"><?php echo _("Change language:");?></p>
        <?php $lang_name->getLanguageSelector(); ?>
	</div>
	<div class='smallerText' style='text-align:center; margin-top:13px;'><a href='index.php' id='login-link'><?php echo _("Login page")?></a></div>
    <?php include '../templates/footer.php'; ?>

</form>


<br />
<br />


</center>
<br />
<br />
<script>
    /*
     * Functions to change the language with the dropdown
     */
    $("#lang").change(function() {
        setLanguage($("#lang").val());
        location.reload();
    });
    // Create a cookie with the code of language
    function setLanguage(lang) {
        var wl = window.location, now = new Date(), time = now.getTime();
        var cookievalid=2592000000; // 30 days (1000*60*60*24*30)
        time += cookievalid;
        now.setTime(time);
        document.cookie ='lang='+lang+';path=/'+';domain='+wl.hostname+';expires='+now;
    }
</script>
<script type="text/javascript" src="js/admin.js"></script>

</body>
</html>


<?php

}else{

	if (isset($user) && $user->getOpenSession()){
		header('Location: index.php?service=admin.php&invalid');
        exit; //PREVENT SECURITY HOLE
	}else{
		header('Location: index.php?service=admin.php&admin');
        exit; //PREVENT SECURITY HOLE
	}
}

?>
