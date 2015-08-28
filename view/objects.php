<?php
require_once '../config.php';
require_once filepath() . "app/sql.php";
require_once filepath() . "app/barcode.php";

function getProjectID($hash)
{
	$hash = sqlSafe($hash);
	$query = "SELECT id FROM projects WHERE hash=$hash";
	$result = readQuery($query);

	if(hasSQLerrors())
	{
		var_dump(getSQLerrors());
	}
	if($result)
	{
		while($row = $result->fetch_assoc())
		{
			return $row['id'];
		}
	}
	return null;
}

function getProjectInfo($id)
{
	$query = "SELECT p.id, p.duedate, p.title, p.notes, a.fullname as name, p.moveto FROM projects p LEFT JOIN accounts a on (a.id = p.account_id) WHERE p.id = " . sqlSafe($id);

		$result = readQuery($query);


		$return = array();
		while($row = $result->fetch_assoc())
		{
			$return = $row;
		}

		return $return;
}
function generateObjectRows($id)
{
	$query = "SELECT object_irn as irn FROM objectProject WHERE project_id = " . sqlSafe($id);

	$result = readQuery($query);
	$html = array();
	while($row = $result->fetch_assoc())
	{
		$html .= generateObjectRow($row['irn']);
	}

	return $html;

}

function generatePDFcells($id)
{
	$query = "SELECT object_irn as irn FROM 
objectProject op
LEFT JOIN objects o on (o.irn = op.object_irn)
 WHERE op.project_id =  " . sqlSafe($id) . " order by location_name, accession_no";
 //print $query;
	$result = readQuery($query);
	$cells = array();
	while($row = $result->fetch_assoc())
	{
		array_push($cells, generatePDFRow($row['irn']));
	}

	return $cells;
}

function generatePDFrow($irn)
{
	

	$sirn = sqlSafe($irn);
	$query = "SELECT location_name, location_barcode, image_url, title, year, accession_no, medium, barcode, notes FROM objects WHERE irn=$sirn";

	$result = readQuery($query);

	if($row = $result->fetch_assoc())
	{
		$info = $row;
	}
	else
	{
		return null;
	}

	$lbarcode = generateBarcode($info['location_barcode']);
	$lname = $info['location_name'];
	$title = $info['title'];
	$year = $info['year'];
	$creators = getCreators($irn);
	$acc = $info['accession_no'];
	$medium = $info['medium'];
	$measures = getMeasurements($irn);
	$obarcode = generateBarcode($info['barcode']);
	$children = getChildren($irn);
	$notes = $info['notes'];
	$titlealt = htmlentities ($title);

	$cells = array();


	$html = '';
	//Current Location
	$html .="\n<p>$lname</p>";
	$html .="\n$lbarcode";
	$cells[] = $html;


	//Object
	

	$html = '';
	$html .= "\n<table class=\"noborder\" summary=\"Alignment Table\">
	<tr>
	\n\t\t<td class=\"obj-img\">";
	if(isset($info['image_url']))
	{
		$img = $info['image_url'];
		$img = $img;
		$html .= "\n\t\t<img src=\"$img\" height=\"75px\" alt=\"$titlealt\"/>";
	}

	$html .= "\n\t\t\t</td>";
	

	$html .= "\n\t\t<td class=\"obj-txt\">";
	$html .= "<p>";
	$html .= "<span class=\"obj-title\">$title,</span> <span class=\"obj-year\">$year</span><br>";
	foreach ($creators as $key => $c) {
		$cname = $c['name'];
		$html .= "\n\t\t\t<span class=\"cre-name\">$cname</span>";

		if(!empty($c['role']))
		{
			$crole = $c['role'];
			$html .= "<span class=\"cre-role\"> ($crole)</span>";
		}
		$html .= "<br>";
	}
	$html .= "\n\t\t\t<span class=\"obj-acc\">$acc</span><br>";
	$html .= "\n\t\t\t<span class=\"obj-med\">$medium</span><br>";
	foreach ($measures as $key => $m)
	 {
		if(empty($m['type']))
		{
			$type = 'Unknown';
		}
		else
		{
			$type = $m['type'];
		}
		$html .= "\n\t\t\t<span class=\"obj-measure\">$type: ";

		$first = true;
		if(!empty($m['height']))
		{
			$first = false;
			$html .= "H: " . $m['height'] . " in.";
		}

		if(!empty($m['width']))
		{
			if(!$first)
			{
				$html .= " x";
			}
			$first = false;
			$html .= " W: " . $m['width'] . " in.";
		}

		if(!empty($m['depth']))
		{
			if(!$first)
			{
				$html .= " x";
			}
			$html .= " D: " . $m['depth'] . " in.";
		}
		$html .= "</span><br>";
		
	}

	if(!empty($notes))
	{
		$html .= "<span class=\"yellow\">$notes</span><br>";
	}
	
	$html .= "\n\t\t</p>$obarcode</td>";
	$html .= "</tr></table>";


	
	if(count($children) >= 1)
	{
		$html .= "\n\t\t<p class=\"tbl-spacing\"> </p><table summary=\"Children of $titlealt\"class=\"obj-parts\">";
		$html .= "\n\t\t\t<tr>";
		$html .= "\n\t\t\t\t<th>Location<br></th>";
		$html .= "\n\t\t\t\t<th>Parts<br></th>";
		$html .= "\n\t\t\t</tr>";

		foreach ($children as $key => $c) 
		{
			$bcode = generateBarcode($c['barcode']);
			$lbcode = generateBarcode($c['location_barcode']);
			$title = $c['title'];
			$accno = $c['accession_no'];
			$loc = $c['location_name'];
			$cirn = $c['irn'];

			$html .= "\n\t\t\t<tr>";
			$html .= "\n\t\t\t\t<td >";
			$html .= "\n\t\t\t\t\t<p class=\"part\">$loc</p>";
			$html .= "\n\t\t\t\t\t$lbcode";
			$html .= "\n\t\t\t\t</td>";
			$html .= "\n\t\t\t\t<td ><p class=\"part\">$accno - $title</p>";
			$html .= "\n\t\t\t\t\t$bcode";
			$html .= "\n\t\t\t\t</td>";
			$html .= "\n\t\t\t</tr>";
		}


		$html .= "\n\t\t</table>";

	}
	
	$cells[] = $html;

	$html = '';
//<div class=\"specLoc\">

	//Specific Location
	$html .= "<p class=\"specLoc\"><form action=\"http://nowhere.com\">";
	$html .= "\n\t\t\tBuilding: <input type=\"text\" size=\"18\" width=\"40\" name=\"$irn-a\" value=\"\" /><br>";
	$html .= "\n\t\t\tRoom / Gallery: <input type=\"text\" size=\"8\" width=\"40\" name=\"$irn-b\" value=\"\" /><br>";
	$html .= "\n\t\t\tBin / Rack / Zone: <input type=\"text\" size=\"8\" width=\"40\" name=\"$irn-c\" value=\"\" /><br>";
	$html .= "\n\t\t\tUnit / Cab. / Case: <input type=\"text\" size=\"8\" width=\"40\" name=\"$irn-d\" value=\"\" /><br>";
	$html .= "\n\t\t\tShlf / Drwr / Rack: <input type=\"text\" size=\"8\" width=\"40\" name=\"$irn-e\" value=\"\" /><br>";
	$html .= "\n\t\t\tOther:<br><textarea cols=\"25\" rows=\"2\" name=\"$irn-f\"></textarea>";
	$html .= "</form></p>";
	
	$cells[] = $html;

	$html = '';

	//Audit

	$html .= "\n\t\t<p class=\"audit\">";
	$html .= "\n\t\t\tMoved By:<br><input type=\"text\" size=\"20\" width=\"40\" name=\"$irn-g\" value=\"\" /><br>";
	$html .= "\n\t\t\tDate:<br><input type=\"text\" size=\"20\" width=\"40\" name=\"$irn-h\" value=\"\" /><br>";
	$html .= "\n\t\t\tRec. in EMu By:<br><input type=\"text\" size=\"20\" width=\"40\" name=\"$irn-i\" value=\"\" /><br>";
	$html .= "\n\t\t\tDate:<br><input type=\"text\" size=\"20\" width=\"40\" name=\"$irn-j\" value=\"\" /><br>";
	$html .= "\n\t\t</p>";


	$cells[] = $html;

	$html = '';

	return $cells;
}


function generateObjectRow($irn)
{
	$iw = "150";

	$sirn = sqlSafe($irn);
	$query = "SELECT location_name, location_barcode, image_url, title, year, accession_no, medium, barcode FROM objects WHERE irn=$sirn";

	$result = readQuery($query);

	if($row = $result->fetch_assoc())
	{
		$info = $row;
	}
	else
	{
		return null;
	}

	$lbarcode = generateBarcode($info['location_barcode']);
	$lname = $info['location_name'];
	$title = $info['title'];
	$year = $info['year'];
	$creators = getCreators($irn);
	$acc = $info['accession_no'];
	$medium = $info['medium'];
	$measures = getMeasurements($irn);
	$obarcode = generateBarcode($info['barcode']);
	$children = getChildren($irn);

	$html = '';

	$html .="\n<tr>";
	//Current Location
	$html .="\n<td width=\"16%\">";
	$html .="\n<p>$lname</p>";
	$html .="\n$lbarcode";
	$html .="\n</td>";


	//Object


	$html .="\n\t<td width=\"50%\">";
	$titlealt = htmlentities ($title);

	$html .= "\n<table class=\"noborder\" summary=\"Alignment Table\">
	<tr>";
	if(isset($info['image_url']))
	{
		$img = $info['image_url'];
		$html .= "\n\t\t<td class=\"obj-img\"><img src=\"$img\" width=\"$iw\" alt=\"$titlealt\"/></td>";
	}
	

	$html .= "\n\t\t<td class=\"obj-txt\">";
	$html .= "\n\t\t\t<p><span class=\"obj-title\">$title,</span> <span class=\"obj-year\">$year</span></p>";
	foreach ($creators as $key => $c) {
		$cname = $c['name'];
		$html .= "\n\t\t\t<p><span class=\"cre-name\">$cname</span>";

		if(!empty($c['role']))
		{
			$crole = $c['role'];
			$html .= "<span class=\"cre-role\"> ($crole)</span>";
		}
		$html .= "</p>";
	}
	$html .= "\n\t\t\t<p class=\"obj-acc\">$acc</p>";
	$html .= "\n\t\t\t<p class=\"obj-med\">$medium</p>";
	foreach ($measures as $key => $m)
	 {
		if(empty($m['type']))
		{
			$type = 'Unknown';
		}
		else
		{
			$type = $m['type'];
		}
		$html .= "\n\t\t\t<p class=\"obj-measure\">$type: ";

		$first = true;
		if(!empty($m['height']))
		{
			$first = false;
			$html .= "H: " . $m['height'] . " in.";
		}

		if(!empty($m['width']))
		{
			if(!$first)
			{
				$html .= " x";
			}
			$first = false;
			$html .= " W: " . $m['width'] . " in.";
		}

		if(!empty($m['depth']))
		{
			if(!$first)
			{
				$html .= " x";
			}
			$html .= " D: " . $m['depth'] . " in.";
		}
		$html .= "</p>";
		
	}
	$html .= "\n\t\t\t$obarcode";
	$html .= "\n\t\t</td>";
	$html .= "</tr></table>";


	
	if(count($children) >= 1)
	{
		$html .= "\n\t\t<table summary=\"Children of $titlealt\"class=\"obj-parts\">";
		$html .= "\n\t\t\t<tr>";
		$html .= "\n\t\t\t\t<th colspan=\"1\">Location</th>";
		$html .= "\n\t\t\t\t<th>Parts</th>";
		$html .= "\n\t\t\t</tr>";

		foreach ($children as $key => $c) 
		{
			$bcode = generateBarcode($c['barcode']);
			$lbcode = generateBarcode($c['location_barcode']);
			$title = $c['title'];
			$accno = $c['accession_no'];
			$loc = $c['location_name'];
			$cirn = $c['irn'];

			$html .= "\n\t\t\t<tr>";
			$html .= "\n\t\t\t\t<td >";
			$html .= "\n\t\t\t\t\t<p class=\"part-loc\">$loc</p>";
			$html .= "\n\t\t\t\t\t$lbcode";
			$html .= "\n\t\t\t\t</td>";
			$html .= "\n\t\t\t\t<td><span  class=\"part-acc\">$accno</span> - <span class=\"part-title\">$title</span>";
			$html .= "\n\t\t\t\t\t$bcode";
			$html .= "\n\t\t\t\t</td>";
			$html .= "\n\t\t\t</tr>";
		}


		$html .= "\n\t\t</table>";

	}
	
	$html .= "\n\t</td>";

	//Specific Location
	$html .= "\n\t<td width=\"16%\">";

	$html .= "\n\t\t\t<p class=\"specLoc\">Building:<br>";
	$html .= "\n\t\t\tRoom / Gallery:<br>";
	$html .= "\n\t\t\tBin / Rack / Zone:<br>";
	$html .= "\n\t\t\tUnit / Cab. / Case:<br>";
	$html .= "\n\t\t\tShlf / Drwr / Rack:<br>";
	$html .= "\n\t\t\tOther:<br> </p>";

	$html .= "\n\t</td>";

	//Audit
	$html .= "\n\t<td width=\"16%\">";

	$html .= "\n\t\t<div class=\"audit\">";
	$html .= "\n\t\t\t<p>Moved By:</p>";
	$html .= "\n\t\t\t<p>Date:</p>";
	$html .= "\n\t\t\t<p>Rec. in EMu By:</p>";
	$html .= "\n\t\t\t<p>Date:</p>";
	$html .= "\n\t\t</div>";

	$html .= "\n\t</td>";



	//End
	$html .= "\n</tr>";
	/*
	$html .= "\n<tr class='pb'>";
	$html .= "\n\t<td class='pb'>";
	$html .= "\n\t\t<div class='pb'>";
	$html .= "\n\t\t</div>";
	$html .= "\n\t</td>";
	$html .= "\n</tr>";
	*/

	return $html;

}

function getCreators($irn)
{
	$irn = sqlSafe($irn);
	$query = "SELECT name, role FROM creators WHERE object_irn = $irn ORDER BY name";
	$result = readQuery($query);

	$creators = array();
	while($row = $result->fetch_assoc())
	{
		array_push($creators, $row);
	}


	return $creators;

}

function getMeasurements($irn)
{
	$irn = sqlSafe($irn);
	$query = "SELECT type, width, height, depth FROM measurements WHERE object_irn = $irn Order by type";
	$result = readQuery($query);

	$measure = array();
	while($row = $result->fetch_assoc())
	{
		array_push($measure, $row);
	}

	return $measure;
}
function getChildren($irn)
{
	$irn = sqlSafe($irn);
	$query = "SELECT c.irn, c.title, c.accession_no, c.location_barcode, c.location_name, c.barcode FROM children c LEFT JOIN objects o ON (o.irn = c.parent_irn) WHERE c.parent_irn = $irn AND (c.location_name != o.location_name OR o.location_name is null) order by c.accession_no";
	$result = readQuery($query);
	//var_dump($query);
	$child = array();
	while($row = $result->fetch_assoc())
	{
		array_push($child, $row);
	}

	return $child;
}

?>