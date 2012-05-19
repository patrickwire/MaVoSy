<?php
include("database.php");

function request_URI() {
    if(!isset($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
        if($_SERVER['QUERY_STRING']) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
    return $_SERVER['REQUEST_URI'];
}
$zoom=1;
function zoom(){
global $zoom;
if(isset($_GET['zoom'])){
$zoom=$_GET['zoom'];
}else{
$zoom=1;
}

echo"<span style='padding:5px;background-color:#0e0e6a;;color:#FFFFFF'> ";
if($zoom>1){
echo '<a style="color:#FFFFFF;text-decoration:none" href="voting.php?zoom='.($zoom-0.5).'&img='.$_GET['img'].'"> - </a>';
}else{
echo ' - ';
}
echo"</span><span style='padding:5px;background-color:#0e0e6a;color:#FFFFFF'> ";
if($zoom<2){
echo '<a style="color:#FFFFFF;text-decoration:none" href="voting.php?zoom='.($zoom+0.5).'&img='.$_GET['img'].'"> + </a>';
}else{
echo ' + ';
}
echo "</span>";
echo'<br>';
echo'<br>';
}

mysql_connect($DB_CONFIG['host'], $DB_CONFIG['login'], $DB_CONFIG['password']);
mysql_select_db($DB_CONFIG['database']);
if(isset($_GET['img'])){
 $sql="SELECT * FROM `voting` WHERE `id`=".$_GET['img'];
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
if(!isset($_GET['nozoom'])){
zoom();
}
?>
<meta http-equiv="refresh" content="3; URL="<? echo request_URI()?>">

<<?if(!isset($_GET['zoom'])){echo "a";}else{echo "b";}?> target="_BLANK" href=?zoom=2&img=<? echo $row['id']?>><img src="http://chart.apis.google.com/chart?chxt=y&chbh=a&chs=<? echo (int)(300*$zoom) ?>x<? echo (int)(225*$zoom)?>&cht=bvg&chco=FF0000,FFCC33,00FF00,0000FF&chtt=<? echo $row['Frage'];?>&chdl=<? echo $row['A1'];?>|<? echo $row['A2'];?>|<? echo $row['A3'];?>|<? echo $row['A4'];?>&chd=t:<? echo $row['C1'];?>|<? echo $row['C2'];?>|<? echo $row['C3'];?>|<? echo $row['C4'];?>&chxt=y&chxr=0,0,<?echo max(array($row['C1'],$row['C2'],$row['C3'],$row['C4'],10));?>&chds=0,<?echo max(array($row['C1'],$row['C2'],$row['C3'],$row['C4'],10));?>"></a>
<?
}elseif(isset($_GET['id'])){
 $sql="SELECT * FROM `voting` WHERE `id`=".$_GET['id'];
$res=mysql_query($sql);
$row=mysql_fetch_array($res);

if(isset($_GET['a1'])){
$sql="Update voting SET`C1`=`C1`+1 WHERE `id`=".$_GET['id'];
mysql_query($sql);
setcookie("Question".$_GET['id'], 42, time()+3600*24*30); 
header('Location: '. str_replace("a1", "", request_URI()));
exit();
}
if(isset($_GET['a2'])){
$sql="Update voting SET`C2`=`C2`+1 WHERE `id`=".$_GET['id'];
mysql_query($sql);
setcookie("Question".$_GET['id'], 42, time()+3600*24*30); 
header('Location:'. str_replace("a2", "", request_URI()));
exit();
}
if(isset($_GET['a3'])){
$sql="Update voting SET`C3`=`C3`+1 WHERE `id`=".$_GET['id'];
mysql_query($sql);
setcookie("Question".$_GET['id'], 42, time()+3600*24*30); 
header('Location: '. str_replace("a3", "", request_URI()));
exit();
}
if(isset($_GET['a4'])){
$sql="Update voting SET`C4`=`C4`+1 WHERE `id`=".$_GET['id'];
mysql_query($sql);
setcookie("Question".$_GET['id'], 42, time()+3600*24*30); 
header('Location: '. str_replace("a4", "", request_URI()));
exit();
}
?><title>HS MaVoSy<?echo $row['Frage']?></title>
<div align="center"><img src="MaVoSy.png"><br><h1  style='color:#0e0e6a'><?echo $row['Frage']?></h1>
<? if(isset($_COOKIE["Question".$_GET['id']])||$row['blind_mode']!=1){?>
<iframe frameborder="0" src="voting.php?zoom=1&nozoom&img=<? echo $_GET['id']?>" width="330" height="300" name="chart"></iframe><br>

<? }
	if(!isset($_GET['voted'])){
if(!isset($_COOKIE["Question".$_GET['id']])){
?>
<form method="get">
<input type="hidden" name="id" value="<? echo $_GET['id']?>">
<table border="0">
 <? if($row['A1']!=""){?><tr><td><input style="height: 25px; width: 100px" type="submit" name="a1" value="Antwort 1"></td><td><b style="color:red"><? echo nl2br($row['A1']);?></b></td></tr>
  <?} if($row['A2']!=""){?><tr><td><input style="height: 25px; width: 100px" type="submit" name="a2" value="Antwort 2"></td><td><b style="color:orange"><? echo nl2br($row['A2']);?></b><br></td></tr>
<?} if($row['A3']!=""){?> <tr><td><input style="height: 25px; width: 100px" type="submit" name="a3" value="Antwort 3"></td><td><b style="color:green"><? echo nl2br($row['A3']);?></b><br></td></tr>
<?} if($row['A4']!=""){?> <tr><td><input style="height: 25px; width: 100px" type="submit" name="a4" value="Antwort 4"></td><td><b style="color:blue"><? echo nl2br($row['A4']);?></b><br></td></tr>
<?}?></table></form>

<?
}else{
	echo "<b>Du hast bereits abgestimmt</b>";
}
	}else{
		echo "<b>Vielen Dank fürs Mitmachen!</b>";
	}
}elseif(isset($_GET['list'])){
?><title>HS MaVoSy<?echo $row['Frage']?></title><?
echo "<table border=0 >"; 
echo "<tr  style='color:#0e0e6a'>"; 
echo "<td><b>Id</b></td>"; 
echo "<td><b>Frage</b></td>"; 
echo "<td><b>Antwort 1</b></td>"; 
echo "<td><b>Antwort 2</b></td>"; 
echo "<td><b>Antwort 3</b></td>"; 
echo "<td><b>Antwort 4</b></td>"; 
echo "<td>#1</b></td>"; 
echo "<td>#2</b></td>"; 
echo "<td>#3</b></td>"; 
echo "<td>#4</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `voting`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td valign='top'>" . nl2br( $row['id']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['Frage']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['A1']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['A2']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['A3']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['A4']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['C1']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['C2']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['C3']) . "</td>";  
echo "<td valign='top'>" . nl2br( $row['C4']) . "</td>";  
echo "<td valign='top'><a href=?id={$row['id']}>Abstimmen</a></td><td><a href=?img={$row['id']}>Ergebnis</a></td> "; 
echo "</tr>"; 
} 
echo "</table>"; 
}else{
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `voting` ( `Frage` ,  `A1` ,  `A2` ,  `A3` ,  `A4` ,  `C1` ,  `C2` ,  `C3` ,  `C4` ,blind_mode ) VALUES(  '{$_POST['Frage']}' ,  '{$_POST['A1']}' ,  '{$_POST['A2']}' ,  '{$_POST['A3']}' ,  '{$_POST['A4']}' ,  '{$_POST['C1']}' ,  '{$_POST['C2']}' ,  '{$_POST['C3']}' ,  '{$_POST['C4']}' ,'{$_POST['blind_mode']}' ) "; 
mysql_query($sql) or die(mysql_error()); 

?><title>HS MaVoSy: <?echo $_POST['Frage']?></title><?
echo "<div align='center'><img src='MaVoSy.png'><br>
<h2 style='color:#0e0e6a'>Ihr ID</h2>
<div style='padding:3px;background-color:grey;width: 80px; color:#FFFFFF;text-decoration:none;font-size:18px'>".mysql_insert_id()."</div>
<h2 style='color:#0e0e6a'>Ihr Link zur Frage</h2>"; 
echo "<div style='padding:3px;background-color:grey;width: 300px;'><a style='color:#FFFFFF;text-decoration:none;font-size:18px' href='voting.php?id=".mysql_insert_id()."'>"."http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?id=".mysql_insert_id()."</a></div>"; 
echo "<h2  style='color:#0e0e6a'>Ihr Link zum Ergebnis</h2>"; 
echo "<div style='padding:3px;background-color:grey;width: 300px;'><a style='color:#FFFFFF;text-decoration:none;font-size:18px' href='voting.php?img=".mysql_insert_id()."'>http://".$_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME']."?img=".mysql_insert_id()."</a></div>"; 

?>
<br>
<img src="http://chart.apis.google.com/chart?chs=400x400&cht=qr&chl=http%3A%2F%2<? echo urlencode($_SERVER["HTTP_HOST"].$_SERVER['SCRIPT_NAME'])?>%3Fid%3D<? echo mysql_insert_id()?>%0A%0A" width="400" height="400" alt="" />
</div>
<?
exit();
} 
?>
<title>HS MaVoSy</title>
<div align="center"><img src='MaVoSy.png'><br><h2 style="color:#0e0e6a">Per ID zur Umfrage:</h2>
<form method="GET">
<b>ID:</b><br />
<input name="id" size="4">
</form>
<h2>oder</h2>
<h2 style="color:#0e0e6a">Neue Umfrage Anlegen:</h2>
<form action='' method='POST'> 
<p><b>Frage:</b><br /><textarea name='Frage'></textarea> 
<p><b>Antowrt 1:</b>*<br /><textarea name='A1'></textarea> 
<p><b>Antwort 2:</b>*<br /><textarea name='A2'></textarea> 
<p><b>Antwort 3:</b>*<br /><textarea name='A3'></textarea> 
<p><b>Antwort 4:</b>*<br /><textarea name='A4'></textarea> 
<p><b>Blind Mode:</b><input value="1" type='checkbox' name='blind_mode'/><br>(kein Ergebnis vor Stimmabgabe)
<input type='hidden' name='C1'/> 
<input type='hidden' name='C2'/> 
<input type='hidden' name='C3'/> 
<input type='hidden' name='C4'/> 
<p><input type='submit' value='Frage Stellen' /><input type='hidden' value='1' name='submitted' /> 
</form> 
*leere Antworten werden keinen Button bekommen
</div>
<?}?>

<?  
//print_r($_COOKIE);
?>