<?php
	/***Bengtson-Fu!!!   13 3 |\| ( ][ 5 () |\| - |= |_|!!! 
	created by Jason Bengtson, MLIS, MA : Available under MIT open source license****/
	/***formats serial solutions csv into google xml for import into Primo***/
	/***done previously in VBScript by Fredrick Reiss***/
	
	
	//uncomment below for debugging

	/*ini_set('display_errors',1); 

	 error_reporting(E_ALL);*/



	//set file and path info here

	$filename="/home/birdlib/public_html/serials/report.csv";

	$destination="/home/birdlib/public_html/serials/01OU-HSC-e-materials";

	//setting my constants
	define ('OBJECT', '<object_type>');
	define ('OBJECT2', '</object_type>');

	define ('TITLE', '<title>');

	define ('TITLE2', '</title>');

	define ('ISSN', '<issn>');

	define ('ISSN2', '</issn>');



	define ('ISBN', '<isbn>');

	define ('ISBN2', '</isbn>');
	define ('EISSN', '<eissn>');
	define ('EISSN2', '</eissn>');





	define ('FROM', '<from>');

	define ('FROM2', '</from>');



	define ('COVERAGE', '<coverage>');

	define ('COVERAGE2', '</coverage>');

	define ('YEAR', '<year>');

	define ('YEAR2', '</year>');

	define ('MONTH', '<month>');

	define ('MONTH2', '</month>');

	define ('TO', '<to>');

	define ('TO2', '</to>');

	define ('ITEM', '<item type="electronic">');

	define ('ITEM2', '</item>');



	define ('DABEGINNING', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

	<!DOCTYPE institutional_holdings PUBLIC "-//GOOGLE//Institutional Holdings 1.0//EN" "http://scholar.google.com/scholar/institutional_holdings.dtd">

	<institutional_holdings>');

	define ('DAEND', '</institutional_holdings>');







	//setting vars . . . I guess I could have used constants for some of these. Didn't.

	$appendix=".xml";

	$dachunk=900000;

	$sizer=filesize($filename);



	$iter=0;
	$outcount=0;

	$counter=1;
	$dectest=0;



	

	$holding="";

	$newcurrent=array();
	
	/*****put in the header****/
	$outer = fopen($destination.$appendix, 'w');
	fwrite($outer, DABEGINNING);
	fclose($outer);
	
	/****the following are normalization functions***/



	function farthis($holdingit)

	{

	/*****find and replace******/



			

			$holdingit=str_replace("&","&amp;",$holdingit);

			$holdingit = str_replace('"',"&quot;",$holdingit);

			$holdingit=str_replace("'","&#39;",$holdingit);

			$holdingit=str_replace("<","&lt;",$holdingit);

			$holdingit=str_replace(">","&gt;",$holdingit);

			$holdingit=str_replace("%","&#37;",$holdingit);

			return $holdingit;

			}
			
	/****normalizes month entries*****/
			
	function fixmonth($monthit) {
		
	$monthit=str_replace("Ja","1",$monthit);
	$monthit=str_replace("Fe","2",$monthit);
	$monthit=str_replace("Ma","3",$monthit);
	$monthit=str_replace("Ap","4",$monthit);
	$monthit=str_replace("Ma","5",$monthit);
	$monthit=str_replace("Ju","6",$monthit);
	
	$monthit=str_replace("Au","8",$monthit);
	$monthit=str_replace("Se","9",$monthit);
	$monthit=str_replace("Oc","10",$monthit);
	$monthit=str_replace("No","11",$monthit);
	$monthit=str_replace("De","12",$monthit);
	$monthit=preg_replace('/Wi/i',"11",$monthit);
	$monthit=preg_replace('/su/i',"6",$monthit);
	$monthit=preg_replace('/sp/i',"3",$monthit);
	$monthit=preg_replace('/fa/i',"9",$monthit);
	return $monthit;
		
	}



	/***************************/

	/*******/

	/****************/

	/******************/

	/*****main loop***/

	

	

	$loopcount=0;



	if (($daobject = fopen($filename, "r")) !== FALSE) {

	while (($currentline=fgetcsv($daobject, ","))!==FALSE)

	{

	if ($loopcount>0)

	{

	//clean out the escaped characters

	$numberof=count($currentline);

	$x=0;

	while ($x<$numberof)

	{

	$currentline[$x]=farthis($currentline[$x]);

	$x++;

	}



	/***do the parsing***/

	//sort out the start and end dates

	//from sub-routine

	if (stripos($currentline[6], '/')<0)

	{

	$startit=YEAR.$currentline[6].YEAR2;



	}

	else if(strlen($currentline[6])>0)

	{

	$lengtho="".strlen($currentline[6]);

	$findmonth="".substr($currentline[6], 0, 2);
	//normalize months
	$findmonth=fixmonth($findmonth);

	$findyear="".substr($currentline[6], $lengtho-4);

	$startit=YEAR.$findyear.YEAR2.MONTH.$findmonth.MONTH2;

	}
	else
	{
	$startit="";	
	}



	//to sub-routine

	if (stripos($currentline[7], '/')<0)

	{

	$endit=YEAR.$currentline[7].YEAR2;



	}

	else if(strlen($currentline[7])>0)

	{

	$lengtho2=strlen($currentline[7]);

	$findmonth2=substr($currentline[7], 0, 2);
	//normalize months
	$findmonth2=fixmonth($findmonth2);

	$findyear2=substr($currentline[7], $lengtho-4);

	$endit=YEAR.$findyear2.YEAR2.MONTH.$findmonth2.MONTH2;

	}
	else
	{
		$endit="";
	}
	
	
	$newcurrent[$counter]=ITEM.OBJECT.$currentline[0].OBJECT2."\n".TITLE.$currentline[1].TITLE2."\n";
	

	if (strlen($currentline[2])>0)
	{

	$newcurrent[$counter]=$newcurrent[$counter].ISSN.$currentline[2].ISSN2."\n";
	}
	
	if (strlen($currentline[3])>0)
	{
	$newcurrent[$counter]=$newcurrent[$counter].EISSN.$currentline[3].EISSN2."\n";
	}
	
	if (strlen($currentline[4])>0)
	{
	$newcurrent[$counter]=$newcurrent[$counter].ISBN.$currentline[4].ISBN2."\n";
	}
	
	if (strlen($currentline[5])>0)
	{
	$newcurrent[$counter]=$newcurrent[$counter].ISBN.$currentline[5].ISBN2."\n";
	}
	
	$newcurrent[$counter]=$newcurrent[$counter].COVERAGE;
	
	if (strlen($startit)>0)
	{
	$newcurrent[$counter]=$newcurrent[$counter].FROM.$startit.FROM2.TO.$endit.TO2;
	}
	
	$newcurrent[$counter]=$newcurrent[$counter].COVERAGE2."\n".ITEM2."\n";
	
	//$newcurrent[$counter]=farthis($newcurrent[$counter]);


	$counter++;
	
	
	





}

/****multiple sourcing subroutine******/
	//I made an array of entries. Now we sort out the copies
	//decrement of $iter used to capture stuff that would have hit the 800 edge
	$x=$loopcount-1;
	while ($newcurrent[$x+1])
	{

	//get from current
	if (stripos($newcurrent[$x], '<title>')>-1)
	{
	$temp=stripos($newcurrent[$x], '<title>')+7;
	$temp2=stripos($newcurrent[$x], '<coverage>');
	$findissn=substr($newcurrent[$x], $temp, $temp2-$temp);
	}
	else
	{
	$findissn="";
	}
	
	if (stripos($newcurrent[$x], '<object_type>')>-1)
	{
	$temp=stripos($newcurrent[$x], '<object_type>')+13;
	$temp2=stripos($newcurrent[$x], '</object_type>');
	$findot=substr($newcurrent[$x], $temp, $temp2-$temp);
	}
	else
	{
	$findot="";
	}

	//get from next in the queue
	if (stripos($newcurrent[$x+1], '<title>')>-1)
	{
	$temp=stripos($newcurrent[$x+1], '<title>')+7;
	$temp2=stripos($newcurrent[$x+1], '<coverage>');
	$compareissn=substr($newcurrent[$x+1], $temp, $temp2-$temp);
	}
	else
	{
	$compareissn="";
	}
	
	if (stripos($newcurrent[$x+1], '<object_type>')>-1)
	{
	$temp=stripos($newcurrent[$x+1], '<object_type>')+13;
	$temp2=stripos($newcurrent[$x+1], '</object_type>');
	$compareot=substr($newcurrent[$x+1], $temp, $temp2-$temp);
	}
	else
	{
	$compareot="";
	}
	
	/*I compare a string of the full set of tags, title through identifiers anded
	with same object type. Seems to be doing the job for de-duping*/

	if ($findissn==$compareissn&&$findot==$compareot)
	{
	$temp=stripos($newcurrent[$x], '<coverage>');
	$temp2=stripos($newcurrent[$x], '</coverage>')+11;
	$getcover=substr($newcurrent[$x], $temp, $temp2-$temp);
	$temp=stripos($newcurrent[$x+1], '</coverage>')+11;
	$holdit=substr($newcurrent[$x+1], 0, $temp);
	$holdend=substr($newcurrent[$x+1], $temp+1);
	$newcurrent[$x+1]=$holdit.$getcover.$holdend;
	$newcurrent[$x]=NULL;
	$iter--;
	//$dectest tells us how many decrements per chunk, so we still capture those entries
	//when we write the file
	$dectest++;
	}

	
	$x++;
	}
	

	/*****multiple sourcing sub-routine ends*****/



	



/******appends to the file in chunks to save on system resources*****/
//800 entries seems to work well, keeping things within a reasonable threshhold for system resources
if ($iter==800)
{
	$t=1;
	while($t<$iter+$dectest)
	{
	
	$holding=$holding.$newcurrent[(800*$outcount)+$t];
	$newcurrent[(800*$outcount)+$t]="";
	
	$t++;
	}

/***append to the file***/
$outer = fopen($destination.$appendix, 'a');
fwrite($outer, $holding);	
fclose($outer);	
$holding="";
$iter=0;	
$outcount++;	
}

$loopcount++;
$iter++;
$dectest=0;

}

	

}
/****main loop ends****/
/************************/



	$outer = fopen($destination.$appendix, 'a');
	fwrite($outer, DAEND);	
	fclose($outer);	

	fclose($daobject);



	/****************************************/

	/*****Bengtson-Fu is the best Kung-Fu***/

	?>
