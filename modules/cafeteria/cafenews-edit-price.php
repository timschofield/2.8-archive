<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require_once('./roots.php');
require_once($root_path.'include/helpers/inc_environment_global.php');
/**
* CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
* GNU General Public License
* Copyright 2002,2003,2004,2005 Elpidio Latorilla
* elpidio@care2x.org, 
*
* See the file "copy_notice.txt" for the licence notice
*/
define('LANG_FILE','editor.php');
$local_user='ck_cafenews_user';
require_once($root_path.'include/helpers/inc_front_chain_lang.php');

$breakfile='cafenews.php'.URL_APPEND;
$returnfile='cafenews-edit-price-select.php'.URL_APPEND;

$dbtable='care_cafe_prices';

if(!isset($mode)) $mode='';

if($mode=='save')
{
	$savefail=0;
	for($i=0;$i<$maxitem;$i++)
	{
		$product="product$i";
		$item="item$i";
		$price="price$i";

		if($$item)
		{
			if(!$$product)
				$sql="DELETE FROM $dbtable WHERE item='".$$item."'";
			else
				$sql="UPDATE $dbtable SET productgroup='$groupname',
					article='".$$product."',
					price='".$$price."',
					modify_id='".$_COOKIE[$local_user.$sid]."',
					modify_time='".date('YmdHis')."'
					WHERE item='".$$item."'";
		}
		else
		{
			if($$product)
			$sql="INSERT INTO $dbtable (lang,productgroup,article,price,create_id,create_time)
					VALUES ('$lang','$groupname','".$$product."','".$$price."','".$_COOKIE[$local_user.$sid]."','".date('YmdHis')."')";
			else continue;
		}
		//echo $sql."<br>";
		if(!$ergebnis=$db->Execute($sql)) $savefail=1;
	}
	if($savefail) echo "<p>".$sql."<p>".$LDDbNoSave;
		else {header("Location: cafenews-edit-price.php?sid=$sid&lang=$lang&mode=saveok&groupname=$groupname"); exit;};
}
else
{
	$sql="SELECT * FROM $dbtable WHERE productgroup='$groupname'";

	if(defined('LANG_DEPENDENT') && (LANG_DEPENDENT==1))
	{
		$sql.="' AND lang='".$lang."'";
	}

	$sql.=" ORDER BY article";

	if($ergebnis=$db->Execute($sql))
	{
		$rows=$ergebnis->RecordCount();
	}
		else echo "<p>".$sql."<p>$LDDbNoRead";
}

if(!$currency_short||!$currency_long)
{
	$sql="SELECT short_name, long_name FROM care_currency WHERE status='main'";
	if($c_result=$db->Execute($sql))
	{
	if($c_result->RecordCount())
	{
		$currency=$c_result->FetchRow();
		$currency_short=$currency['short_name'];
		$currency_long=$currency['long_name'];
	} // else get default from ini file
	} // else get default from ini file
}

?>
<?php html_rtl($lang); ?>
<!-- Generated by AceHTML Freeware http://freeware.acehtml.com -->
<!-- Creation date: 21.12.2001 -->
<head>
<?php echo setCharSet(); ?>
<title></title>

<?php require($root_path.'include/helpers/inc_css_a_hilitebu.php'); ?>
</head>
<body>
<FONT  SIZE=6 COLOR="#cc6600">
<a href="javascript:editcafe()"><img <?php echo createComIcon($root_path,'basket.gif','0') ?>></a> <b><?php echo $LDCafePrices ?></b></FONT>

<form action="cafenews-edit-price.php" method="post"><hr>
<?php if($mode=='saveok') : ?>
<table border=0>
  <tr>
    <td><img <?php echo createMascot($root_path,'mascot1_r.gif','0') ?>></td>
    <td colspan=2><FONT  SIZE=3 COLOR="#000066"><?php echo $LDPriceSaved ?></font><p>
			<font size=2><?php echo $LDClk2End ?> <input type="button" value="<?php echo $LDFinishBut ?>" onClick="window.location.replace('cafenews-prices.php?sid=<?php echo $sid."&lang=".$lang; ?>')">
                                                    </td>
  </tr>
</table>
<hr>
<?php endif ?>
<?php echo $LDProdGroup ?>: <FONT  SIZE=3 COLOR="#000099"><?php echo $groupname; ?><font>
<p>
<table border=0 cellspacing=0>
  <tr bgcolor="ccffff" >
    <td><b><?php echo $LDProdName ?></b></td>
	 <td>&nbsp;<b><?php echo $LDPrice." ".$currency_short." ".$currency_long ?></b></td>
  </tr>
<?php 

if($rows<10) $maxitem=10; else $maxitem=$rows+3;

for($i=0;$i<$maxitem;$i++) {

    $prod=$ergebnis->FetchRow();
    
	echo '
    <tr bgcolor="ccffff" >
    <td><input type="text" name="product'.$i.'" size=40 maxlength=40 value="'.$prod['article'].'">
        </td>
    <td><input type="text" name="price'.$i.'" size=4 maxlength=5 value="'.$prod['price'].'"> 
	<input type="hidden" name="item'.$i.'" value="'.$prod['item'].'">
 	</td>
  </tr>';
}
?>
 <tr>    
 <td><p><br>
 <?php if($mode!='saveok') : ?>
	<a href="<?php echo $returnfile ?>"><img <?php echo createLDImgSrc($root_path,'back2.gif','0') ?>></a>
 <?php endif ?>
<input type="image" <?php echo createLDImgSrc($root_path,'continue.gif','0') ?>>
  </td>  <td align=right><p><br>
	<a href="<?php echo $breakfile ?>"><img <?php echo createLDImgSrc($root_path,'cancel.gif','0') ?>></a>
	</td>
  </tr>
 </table>
<input type="hidden" name="sid" value="<?php echo $sid ?>">
<input type="hidden" name="lang" value="<?php echo $lang ?>">
<input type="hidden" name="maxitem" value="<?php echo $maxitem?>">
<input type="hidden" name="groupname" value="<?php echo $groupname?>">
<input type="hidden" name="currency_short" value="<?php echo $currency_short?>">
<input type="hidden" name="currency_long" value="<?php echo $currency_long?>">
<input type="hidden" name="mode" value="save">
</form></body>
</html>
