<?php
	session_start();
	// "install/index.php" will check if CORAL is installed and version is current
	require_once("install/index.php");

	// Include file of language codes
	include_once 'LangCodes.php';
	$lang_name = new LangCodes();

	// Verify the language of the browser
	global $http_lang;
	if(isset($_COOKIE["lang"])){
		$http_lang = $_COOKIE["lang"];
	}else{
		$codeL = $lang_name->getBrowserLanguage();
		$http_lang = $lang_name->getLanguage($codeL);
		if($http_lang == "")
		  $http_lang = "en_US";
	}
	putenv("LC_ALL=$http_lang");
	setlocale(LC_ALL, $http_lang.".utf8");
	bindtextdomain("messages", dirname(__FILE__) . "/locale");
	textdomain("messages");
?>
<?php include_once('dearborn_coral_development/rootAuth/getAuths.php'); ?>
<?php 
	//Get Uniqname from Weblogin
	$uniqname = (isset($_GET['test'])) ? $_GET['test'] : getenv('REMOTE_USER');
	$uniqname = (!$uniqname) ? 'testUser' : $uniqname;
	$_SESSION['loginID'] = $uniqname;

	$modules = [
		"resources" => _("Resources"), 
		"licensing" => _("Licensing"), 
		"organizations" => _("Organizations"), 
		"usage" => _("Usage Statistics"), 
		"management" => _("Management") 
	];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>eRM - eResource Management</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/indexstyle.css" type="text/css" media="screen" />
	<link rel="icon" href="images/favicon.ico" />
	<script type="text/javascript" src="js/plugins/jquery.js"></script>
	<script type="text/javascript" src="js/plugins/Gettext.js"></script>

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

	<header>
		<div class="title-main"><strong><?php echo _("eRM");?></strong> &bullet; <?php echo _("eResource Management");?></div>
        <nav class="language-select"><?php echo _("Change language:");?></nav>
        <?php $lang_name->getLanguageSelector(); ?>
	</header>

	<section class="icons">
		<?php 
			$menuSettings = Config::getSettingsFor('settings');
			$displayFullMenu = ($menuSettings['displayFullMenu'] == "Y");
			foreach ($modules as $key => $value) {
				$linkedIcon = [
					"<div class='main-page-icons'>",
						"<a href='{$key}/'><img src='images/icon-{$key}.png' class='rollover' /><span>{$value}</span></a>",
					"</div>",
				];
				$unlinkedIcon = [
					"<div class='main-page-icons main-page-icons-off'>",
							"<img src='images/icon-{$key}-off.png'><span>{$value}</span>",
					"</div>",
				];
				$skippedMenu = true;
				try {
					$mod_conf = Config::getSettingsFor($key);
					if (isset($mod_conf["enabled"]) && $mod_conf["enabled"] == "Y")
					{	
						$auths = getAuths($uniqname, $key);
						$output = ($auths) ? $linkedIcon : $unlinkedIcon;
						$skippedMenu = false;
						
						echo implode("", $output);
					}
				}
				catch (Exception $e)
				{
					if ($e->getCode() != Config::ERR_VARIABLES_MISSING)
					{
						throw $e;
					}
				}
				if($displayFullMenu && $skippedMenu){

					echo implode("", $unlinkedIcon);
				}
			}
			//Are they an admin?
			$adminAuth = getAuths($uniqname, 'auth');
			if($adminAuth){
				$linkedIcon = [
					"<div class='main-page-icons'>",
						"<a href='/auth/admin.php'><img src='images/icon-admin.png' class='rollover' /><span>Admin</span></a>",
					"</div>",
				];
				echo implode("", $linkedIcon);
			}
		?>
	</section>

	<footer><?php echo _("Powered by");?><img src="images/logo-coral.jpg" /></footer>

	<script type="text/javascript">
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
</body>
</html>