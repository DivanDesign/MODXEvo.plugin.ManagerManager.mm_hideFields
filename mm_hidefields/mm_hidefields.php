<?php
/**
 * mm_hideFields
 * @version 1.1.3 (2015-02-01)
 * 
 * @desc A widget for ManagerManager plugin that allows one or more of the default document fields or template variables to be hidden within the manager.
 * 
 * @uses MODXEvo >= 1.1.
 * @uses ManagerManager plugin 0.4.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) this should apply to. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied (when this parameter is empty then widget is applied to the all templates). Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_hidefields/1.1.3
 * 
 * @copyright 2015
 */

function mm_hideFields($fields, $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	//if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		global $mm_fields;
		
		// if we've been supplied with a string, convert it into an array
		$fields = makeArray($fields);
		
		$output = '//---------- mm_hideFields :: Begin -----'.PHP_EOL;
		
		foreach ($fields as $field){
			switch ($field){
				//Exceptions
				case 'keywords':
					$output .= '$j("select[name*=\'keywords\']").parent("td").hide();'.PHP_EOL;
				break;
				
				case 'metatags':
					$output .= '$j("select[name*=\'metatags\']").parent("td").hide()'.PHP_EOL;
				break;
				
				case 'which_editor':
					$output .= '$j("select#which_editor").prev("span.warning").hide();'.PHP_EOL;
					$output .= '$j("select#which_editor").hide();'.PHP_EOL;
				break;
				
				case 'content':
					//For 1.0.0
					$output .= '$j("#sectionContentHeader, #sectionContentBody").hide();'.PHP_EOL;
					//For 1.0.1
					$output .= '$j.ddMM.fields.content.$elem.parent("div").parent("div").hide().prev("div").hide();'.PHP_EOL;
				break;
				
				case 'pub_date':
					$output .= '$j.ddMM.fields.'.$field.'.$elem.parents("tr").next("tr").hide();'.PHP_EOL;
					$output .= '$j.ddMM.fields.'.$field.'.$elem.parents("tr").hide();'.PHP_EOL;
				break;
				
				case 'unpub_date':
					$output .= '$j.ddMM.fields.'.$field.'.$elem.parents("tr").next("tr").hide();'.PHP_EOL;
					$output .= '$j.ddMM.fields.'.$field.'.$elem.parents("tr").hide();'.PHP_EOL;
				break;
				
				//Ones that follow the regular pattern
				default:
					//Check the fields exist,  so we're not writing JS for elements that don't exist
					if (isset($mm_fields[$field])){
						$output .= '$j.ddMM.fields.'.$field.'.$elem.parents("tr").hide().next("tr").find("td[colspan=2]").parent("tr").hide();'.PHP_EOL;
					}
				break;
			}
		}
		
		$output .= '//---------- mm_hideFields :: End -----'.PHP_EOL;
		
		$e->output($output);
	}
}
?>