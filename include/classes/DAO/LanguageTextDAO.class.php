<?php
/************************************************************************/
/* AContent                                                             */
/************************************************************************/
/* Copyright (c) 2010                                                   */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/

/**
* DAO for "language_text" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('TR_INCLUDE_PATH')) exit;

require_once(TR_INCLUDE_PATH. 'classes/DAO/DAO.class.php');

class LanguageTextDAO extends DAO {

	/**
	* Create a new entry
	* @access  public
	* @param   $language_code : language code
	*          $variable: '_msgs', '_template', '_check', '_guideline', '_test'
	*          $term
	*          $text
	*          $context
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function Create($language_code, $variable, $term, $text, $context)
	{
		global $addslashes;
		
		$sql = "INSERT INTO ".TABLE_PREFIX."language_text
		        (`language_code`, `variable`, `term`, `text`, `revised_date`, `context`)
		        VALUES
		        ('".$addslashes($language_code)."', 
		         '".$addslashes($variable)."', 
		         '".$addslashes($term)."', 
		         '".$addslashes($text)."', 
		         now(), 
		         '".$addslashes($context)."')";

		return $this->execute($sql);
	}

	/**
	* Insert new record if not exists, replace the existing one if already exists. 
	* Record is identified by primary key: $language_code, variable, $term
	* @access  public
	* @param   $language_code : language code
	*          $variable: '_msgs', '_template', '_check', '_guideline', '_test'
	*          $term
	*          $text
	*          $context
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function Replace($language_code, $variable, $term, $text, $context)
	{
		global $addslashes;
		
		$sql = "REPLACE INTO ".TABLE_PREFIX."language_text
		        (`language_code`, `variable`, `term`, `text`, `revised_date`, `context`)
		        VALUES
		        ('".$addslashes($language_code)."', 
		         '".$addslashes($variable)."', 
		         '".$addslashes($term)."', 
		         '".$addslashes($text)."', 
		         now(), 
		         '".$addslashes($context)."')";
		        
		return $this->execute($sql);
	}
	
	/**
	* Delete a record by $variable and $term
	* @access  public
	* @param   $language_code : language code
	*          $variable: '_msgs', '_template', '_check', '_guideline', '_test'
	*          $term
	* @return  true / false
	* @author  Cindy Qi Li
	*/
	function DeleteByVarAndTerm($variable, $term)
	{
		global $addslashes;
		
		$sql = "DELETE FROM ".TABLE_PREFIX."language_text
		        WHERE `variable` = '".$addslashes($variable)."'
		          AND `term` = '".$addslashes($term)."'";
		        
		return $this->execute($sql);
	}
	
	/**
	* Return message text of given term and language
	* @access  public
	* @param   term : language term
	*          lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getMsgByTermAndLang($term, $lang)
	{
		$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text 
						WHERE term="' . $term . '" 
						AND variable="_msgs" 
						AND language_code="'.$lang.'" 
						ORDER BY variable';

    return $this->execute($sql);
  }

	/**
	* Return text of given term and language
	* @access  public
	* @param   term : language term
	*          lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getByTermAndLang($term, $lang)
	{
		$sql	= 'SELECT * FROM '.TABLE_PREFIX.'language_text 
						WHERE term="' . $term . '" 
						AND language_code="'.$lang.'" 
						ORDER BY variable';

	    return $this->execute($sql);
  	}

	/**
	* Return rows of handbook rows by matching given text and language
	* @access  public
	* @param   term : language term
	*          lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getHelpByMatchingText($text, $lang)
	{
		$sql	= "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE term like 'TR_HELP_%'
						AND lower(cast(text as char)) like '%".strtolower($text)."%' 
						AND language_code='".$lang."' 
						ORDER BY variable";

	    return $this->execute($sql);
  	}

  	/**
	* Return all template info of given language
	* @access  public
	* @param   lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllByLang($lang)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE language_code='".$lang."' 
						ORDER BY variable, term ASC";

		return $this->execute($sql);
	}

  	/**
	* Return all template info of given language
	* @access  public
	* @param   lang : language code
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllTemplateByLang($lang)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."language_text 
						WHERE language_code='".$lang."' 
						AND variable='_template' 
						ORDER BY variable ASC";

    	return $this->execute($sql);
	}

	/**
	* Update text based on given primary key
	* @access  public
	* @param   $languageCode : language_text.language_code
	*          $variable : language_text.variable
	*          $term : language_text.term
	*          $text : text to update into language_text.text
	* @return  true : if successful
	*          false: if unsuccessful
	* @author  Cindy Qi Li
	*/
	function setText($languageCode, $variable, $term, $text)
	{
		global $addslashes;
		
		$sql = "UPDATE ".TABLE_PREFIX."language_text 
		           SET text='".$addslashes($text)."',
		               revised_date = now()
		         WHERE language_code = '".$_SESSION['lang']."' 
		           AND variable='".$variable."' 
		           AND term = '".$term."'";

    return $this->execute($sql);
  }
}
?>