<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require('../../include/helpers/inc_environment_global.php');
define('MODULE','radiology');
define('LANG_FILE_MODULAR','radiology.php');
//$local_user='ck_radio_user';
define('NO_2LEVEL_CHK',1);
require_once($root_path.'include/helpers/inc_front_chain_lang.php');
if(isset($nr)&&$nr){
	require_once($root_path.'modules/photolab/model/class_image.php');
	$img=new Image;
	$img_notes=$img->ImgNotes($nr);
}
//if(!isset($_SESSION['sess_dicom_viewer'])) $_SESSION['sess_dicom_viewer']='nagoyatech';
?><html>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<head>

<title><?php echo $LDImageNotes; ?></title>

</head>
<body onLoad="if (window.focus) window.focus()"><font face=arial>

<form name="classif" >
<table border=0 cellpadding=0 cellspacing=0 bgcolor="#efefef" width=100%>
  <tr>
    <td>
	<table border=0 cellspacing=1 width=100%>
   <tr>
     <td background="../../gui/img/common/default/tableHeaderbg.gif">
	 <font face=arial color="#efefef" size=2><b><?php echo $LDImageNotes  ?> </b>
	 </td>
   </tr>
   <tr>
     <td>
	&nbsp;<p>
	 <font face=arial size=2>	
	 <?php echo nl2br($img_notes) ?>
	  </td>
   </tr>

 </table>&nbsp;
 <p>
	</td>
  </tr>
</table>
<center>
  <input type="button" value="<?php echo $LDClose ?>" onClick="window.close()">
 </center>
</form>

</font>
</body>
</html>
