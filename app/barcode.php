<?php
	require_once __DIR__ . "/../config.php";
	require_once filepath() . "plugins/tcpdf/tcpdf.php";

	function generateBarcode($text, $w=15, $h=20 )
	{
	/*	
		$barcodeobj = new TCPDFBarcode($text, 'C128');
		return $barcodeobj->getBarcodeHTML(2, 30, 'black'); */

		
		$barLoc = sitepath() . "resources/img/barcode";
		$text = strtoupper(strval ($text));

		if(strlen($text) >= 1)
		{
		$html = "<div class=\"barcode\">
		\t<div class=\"barcode-imgs\">
		\t\t\t<img src=\"$barLoc/!bookend.gif\">";
		for ($i=0; $i < strlen($text); $i++) 
		{
			$char = $text[$i];
			$html .= "
			\t\t\t<img src=\"$barLoc/$char.gif\">
			";

		}
		$html .= "
		\t\t\t<img src=\"$barLoc/!bookend.gif\">
		\t</div>
		\t<p class='barcode-text'>$text</p>\n</div>";

		return $html;
	}
	return null;
	
	
	


	}
?>