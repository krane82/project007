<?php
class Controller_Getleads extends Controller
{
function action_getAll()
{
	$i=20;
	$con=mysqli_connect('localhost','fencingm','energysm@rt@u5','fencingm_leadpoint');
	//$sql="SELECT * FROM lead_fields";
	$sql="SELECT * FROM lead_fields WHERE campaign_id='$i'";
	$res=mysqli_query($con,$sql);
	//print $res;die();
	while($row=mysqli_fetch_assoc($res))
	{
		//print_r($row);
		$result[$row['lead_id']][$row['field_name']]=$row['field_value'];
		//print '<tr><td>'.$row['lead_id'].'</td>';
		//print '<td>'.$row['field_name'].'</td>';
		//print '<td>'.$row['field_value'].'</td></tr>';
	}
$csv_file = '';//'TypeofHouse;SystemUse;SystemSize;TypeofRoof;Ageofproperty;UtilityProvider;Comments;FirstName;LastName;PhoneNumber;ZipCode;Address;Suburb;Email;State'."\r\n";
	/*
	print '<table>';
	print '<tr>';
	print '<td>TypeofHouse</td>';
	print '<td>SystemUse</td>';
	print '<td>SystemSize</td>';
	print '<td>TypeofRoof</td>';
	print '<td>Ageofproperty</td>';
	print '<td>UtilityProvider</td>';
	print '<td>Comments</td>';
	print '<td>FirstName</td>';
	print '<td>LastName</td>';
	print '<td>PhoneNumber</td>';
	print '<td>ZipCode</td>';
	print '<td>Address</td>';
	print '<td>Suburb</td>';
	print '<td>Email</td>';
	print '<td>State</td>';
	print '</tr>';
	*/
	foreach ($result as $item)
	{
		if ($item === reset($result))
		{
			foreach ($item as $item1=>$key)
		{
			$csv_file .=$item1.';';
		}
		$csv_file .="\r\n";
		}
		foreach ($item as $item1=>$key)
		{
			$csv_file .=$key.';';
		}
		$csv_file .="\r\n";
	}
	$file_name = $i.'.csv'; // название файла
$file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
fwrite($file,trim($csv_file)); // записываем в файл строки
fclose($file); // закрываем файл
// задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
header('Content-type: application/csv'); // указываем, что это csv документ
header("Content-Disposition: inline; filename=".$file_name); // указываем файл, с которым будем работать
readfile($file_name); // считываем файл
unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера
	}
}