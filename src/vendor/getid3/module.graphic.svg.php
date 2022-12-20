<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <info@getid3.org>               //
//  available at http://getid3.sourceforge.net                 //
//            or http://www.getid3.org                         //
/////////////////////////////////////////////////////////////////
// See readme.txt for more details                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// module.graphic.svg.php                                      //
// module for analyzing SVG Image files                        //
// dependencies: extension.xml.xpath.php                       //
// author: Bryce Harrington <bryceØbryceharrington*org>        //
//         Roan Horning <roanØhorning*us>                      //
/////////////////////////////////////////////////////////////////

include ("extension.parse.xml.php");
class getid3_svg
{
	function getid3_svg(&$fd, &$ThisFileInfo) {
		//Determine if it is an xml file by reading in first few bytes and then
		//checking that the characters x m l make up the third through fifth bytes.
		//This is based on w3.org xml specification version 1.0 recommendation see
		//http://www.w3.org/TR/2004/REC-xml-20040204/#sec-prolog-dtd
		fseek($fd, $ThisFileInfo['avdataoffset'], SEEK_SET);
		$XMLheader = fread($fd, 16);
		$XMLheader = substr($XMLheader, 2, 3);
		//Do not use XPath unless it is an xml file
		if ($XMLheader == 'xml') {
			$xmlOpt = array(XML_OPTION_CASE_FOLDING => FALSE, XML_OPTION_SKIP_WHITE => TRUE);
			$objXML = new XPath();
			$objXML->setVerbose('0');
			$objXML->importFromFile($ThisFileInfo['filenamepath']);
			
			//Query asks for the root <svg> tag. Assumes the svg namespace is default 
			//(i.e tags look like <svg>, <g> and not <svg:svg> <svg:g>
			$svgQuery = '/svg';
            	$ThisFileInfo['svg']['magic'] = $objXML->nodeName($svgQuery);
		
		  if ($ThisFileInfo['svg']['magic'] == 'svg') {
			
			$ThisFileInfo['fileformat']           = 'xml';
			$ThisFileInfo['image']['dataformat']  = 'svg';
			$ThisFileInfo['image']['lossless']    = true;

			//Retrieve attributes of the root svg tag
			$svgAttrs2Query = array ('height', 'width', 'version');
			foreach ($svgAttrs2Query as $svgAttr) {
 				$ThisFileInfo['svg'][$svgAttr] = $objXML->getAttributes($svgQuery, $svgAttr);
			};

			//The svg tag should contain one tag <title> and one tag <desc>. svg 
			//spec. allows for multiples of these tags, but suggests handling  
			//only the first instance of each. 
				
			//Query asks for the first instance of <title> tag
			$svgTitleQuery = '/svg/title[1]'; 
			//Query asks for the first instance of <desc> tag
			$svgDescQuery = '/svg/desc[1]'; 
			//Check for existence of <title> tag and, if so, retrieve its data
			if ($objXML->nodeName($svgTitleQuery)) {
 				$ThisFileInfo['svg']['title'] = $objXML->getData($svgTitleQuery);
 			};
 			//Check for existence of <desc> tag and, if so, retrieve its data
			if ($objXML->nodeName($svgDescQuery)) {
 				$ThisFileInfo['svg']['description'] = $objXML->getData($svgDescQuery);
 			};


//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Creative commons code which will need extracted to its own module		

//find Creative Commons namespace alias

//Currently the Creative Commons namespace uri is located at http://web.resource.org/cc/
//This may change in the future. Add new uri's to $ccURIrefs array
$ccURIrefs = array('http://web.resource.org/cc/');

//$rdfURIref currently not used, but included commented out for possible future use. Currently
//it is assumed that the rdf namespace alias is rdf so tags look like <rdf:RDF> etc.
//$rdfURIref = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';

//The following loop construction searches for all instances where Creative Commons 
//namespace aliases are defined and pushes the namespace aliases onto an array. If 
//more than one is found, priority is given to the alias defined within the first
//found RDF tag
$nsAliasKeys = array();
foreach ($ccURIrefs as $ccURI){
	$ccURIquery='//*[@*="'.$ccURI.'"]';         //This query returns tags which have an
	                                            //attribute which contains a CC namespace URI
	if ($objXML->nodeName($ccURIquery)) {
		$ccURIPathArr = $objXML->match($ccURIquery);
		foreach ($ccURIPathArr as $ccURIPath) {
			$ccURINodeName=$objXML->nodeName($ccURIPath);
			switch (substr($ccURINodeName,-3,3)) {
			case 'RDF':
				$nsAttrArr = $objXML->getAttributes($ccURIPath);
				$nsAttrArrKeys = array_keys($nsAttrArr);
				foreach ($nsAttrArrKeys as $nsAttrKey) {
					if ($objXML->getAttributes($ccURIPath,$nsAttrKey)==$ccURI){
						$nsAliasKeys[0]=substr($nsAttrKey, 6);
					}
				}
			}
		}
		$nsAttrArr = $objXML->getAttributes($ccURIquery) ;
		$nsAttrArrKeys = array_keys($nsAttrArr);
		foreach ($nsAttrArrKeys as $nsAttrKey) {
			if ($objXML->getAttributes($ccURIquery,$nsAttrKey)==$ccURI) {
				array_push($nsAliasKeys, substr($nsAttrKey, 6));
			};
		}
	}
}

//From the CC namespace array grab the 0 index element. I'm assuming 
//that the alias is defined by the first rdf tag with a CC namespace
//alias definition or, if not found, by the first tag with a CC namespace
//alias definition. If no definition is found, no CC information will be
//returned
$nsAlias=array_shift($nsAliasKeys).':';
if ($nsAlias==':') { $nsAlias='';}
$nsAliasCC=$nsAlias;

//Currently I assume that the namespace aliases for RDF and Dublin Core
//tags are rdf and dc respectively. If this is not true, either none or 
//almost no information will be returned
$nsAliasRDF='rdf:';
$nsAliasDC='dc:';

$ccPathQuery = '//'.$nsAliasRDF.'RDF[1]/'.$nsAliasCC.'Work[1]/';

$ccWorkNodesArr=$objXML->match($ccPathQuery.'*');
foreach ($ccWorkNodesArr as $ccWorkNode) {
 	$ccWorkNodeName=$objXML->nodeName($ccWorkNode);
 	switch ($ccWorkNodeName) {
 	  case $nsAliasDC.'publisher':
 	  case $nsAliasDC.'creator':
 	  case $nsAliasDC.'rights':
 	  	 $ThisFileInfo['cc']['Work'][$ccWorkNodeName][]=$objXML->getData($ccWorkNode.'/'.$nsAliasCC.'Agent/'.$nsAliasDC.'title');
 	  	 break;
 	  case $nsAliasDC.'subject':
 	  	 $rdfSubjectPathQuery=$ccWorkNode.'/'.$nsAliasRDF.'Bag/';
 	  	 if ($objXML->nodeName($rdfSubjectPathQuery.'*')) {
 	  	   $keywordsPathArr=($objXML->match($rdfSubjectPathQuery.'*'));
 	  	   $keywordCount= count($keywordsArr);
 	  	   switch ($keywordCount) {
 	  	     case 1:
 	  	        $ThisFileInfo['cc']['Work'][$ccWorkNodeName]= $objXML->getData($keywordsPathArr);
 	  	       break;
 	  	     default:
 	  	       $kwindex=0;
 	  	       foreach ($keywordsPathArr as $keywordPath) {
	  	   	    $ThisFileInfo['cc']['Work'][$ccWorkNodeName][$kwindex]= $objXML->getData($keywordPath).', ';
 	  	 	   $kwindex++;
 	  	 	   };
 	  	    }
 	         }
 	       break;
 	  default:
 	       if ($objXML->getData($ccWorkNode)) {
 	         $ThisFileInfo['cc']['Work'][$ccWorkNodeName][]=$objXML->getData($ccWorkNode);
 	         }
 	       if ($objXML->getAttributes($ccWorkNode)) {
 	         $ThisFileInfo['cc']['Work'][$ccWorkNodeName][]=$objXML->getAttributes($ccWorkNode);
 	         }
 	};
};
//Get data in <License> tag
$ccPathQuery = '//rdf:RDF[1]/'.$nsAliasCC.'License';
$ccLicensesNodes = $objXML->match($ccPathQuery);
$licenseIndex = 0;
foreach ($ccLicensesNodes as $ccLicensesNode) {
	$ccLicenseType=$objXML->getAttributes($ccLicensesNode, 'rdf:about');
//	$ThisFileInfo['cc'][$ccLicenseNodeName][$licenseIndex]=$objXML->getAttributes($ccLicensesNode, 'rdf:about');
	$ccLicenseNodes = $objXML->match($ccLicensesNode.'/*');
	foreach ($ccLicenseNodes as $ccLicenseNode) {
		$ThisFileInfo['cc']['Licenses'][$ccLicenseType][$objXML->nodeName($ccLicenseNode)][]=$objXML->getAttributes($ccLicenseNode, 'rdf:resource');
	};
	$licenseIndex++;
}


//end creative commons code
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			return true;
		}
		$ThisFileInfo['error'][] = $objXML->getLastError();
		unset($ThisFileInfo['fileformat']);
		return false;
	}
	}
}

?>