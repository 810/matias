<?php
/**
* @version $Id$
* Kunena Component - Kunena Factory
* @package Kunena
*
* @Copyright (C) 2009 www.kunena.org All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.org
**/
defined( '_JEXEC' ) or die();

kimport('kunena.bbcode');

abstract class KunenaHtmlParser {
	static $emoticons = null;

	function getEmoticons($grayscale, $emoticonbar = 0) {
		$kunena_db = &JFactory::getDBO ();
		$grayscale == 1 ? $column = "greylocation" : $column = "location";
		$sql = "SELECT code, `$column` FROM #__kunena_smileys";

		if ($emoticonbar == 1)
			$sql .= " WHERE emoticonbar='1'";

		$kunena_db->setQuery ( $sql );
		$smilies = $kunena_db->loadObjectList ();
		KunenaError::checkDatabaseError();

		$smileyArray = array ();
		foreach ( $smilies as $smiley ) { // We load all smileys in array, so we can sort them
			$iconurl = JURI::Root() . CKunenaTools::getTemplateImage("emoticons/{$smiley->$column}");
			$smileyArray [$smiley->code] = '' . $iconurl; // This makes sure that for example :pinch: gets translated before :p
		}

		if ($emoticonbar == 0) { // don't sort when it's only for use in the emoticonbar
			array_multisort ( array_keys ( $smileyArray ), SORT_DESC, $smileyArray );
			reset ( $smileyArray );
		}

		return $smileyArray;
	}

	function JSText($txt) {
		$txt = JText::_($txt);
		$txt = preg_replace('`\'`','\\\\\'', $txt);
		return $txt;
	}

	function parseText($txt, $len=0) {
		if (!$txt) return;

		if ($len && JString::strlen($txt) > $len) $txt = JString::substr ( $txt, 0, $len ) . ' ...';
		$txt = self::escape ( $txt );
		$txt = preg_replace('/(\S{30})/u', '\1&#8203;', $txt);
		$txt = self::prepareContent ( $txt );
		return $txt;
	}

	function parseBBCode($txt, $parent=null, $len=0) {
		if (!$txt) return;

		$bbcode = KunenaBBcode::getInstance();
		$bbcode->parent = $parent;
		$bbcode->SetLimit($len);
		$bbcode->SetPlainMode(false);
		$bbcode->SetDetectURLs(true);
		$bbcode->SetURLPattern('<a href="{$url/h}" target="_blank" rel="nofollow">{$text/h}</a>');
		$bbcode->SetURLTarget('_blank');
		$txt = $bbcode->Parse($txt);
		$txt = self::prepareContent ( $txt );
		return $txt;
	}

	function plainBBCode($txt, $len=0) {
		if (!$txt) return;

		// Strip content not allowed for guests
		// TODO: do this in a better way inside BBCode parser..
		$txt = preg_replace ( '/\[hide\](.*?)\[\/hide\]/s', '', $txt );
		$txt = preg_replace ( '/\[confidential\](.*?)\[\/confidential\]/s', '', $txt );
		$txt = preg_replace ( '/\[spoiler\]/s', '[spoilerlight]', $txt );
		$txt = preg_replace ( '/\[\/spoiler\]/s', '[/spoilerlight]', $txt );
		$txt = preg_replace ( '/\[attachment(.*?)\](.*?)\[\/attachment\]/s', '', $txt );
		$txt = preg_replace ( '/\[code\](.*?)\[\/code]/s', '', $txt );

		$bbcode = KunenaBBCode::getInstance();
		$bbcode->SetLimit($len);
		$bbcode->SetPlainMode(true);
		$bbcode->SetDetectURLs(true);
		$bbcode->SetURLPattern('<a href="{$url/h}" target="_blank" rel="nofollow">{$text/h}</a>');
		$bbcode->SetURLTarget('_blank');
		$txt = $bbcode->Parse($txt);
		$txt = self::prepareContent ( $txt );
		return $txt;
	}

	function stripBBCode($txt, $len=0) {
		if (!$txt) return;

		$bbcode = KunenaBBCode::getInstance();
		$bbcode->SetLimit($len);
		$bbcode->SetPlainMode(true);
		$bbcode->SetDetectURLs(true);
		$bbcode->SetURLPattern('<a href="{$url/h}" target="_blank" rel="nofollow">{$text/h}</a>');
		$bbcode->SetURLTarget('_blank');
		$txt = strip_tags($bbcode->Parse($txt));
		$txt = self::prepareContent ( $txt );
		return $txt;
	}

	function &prepareContent(&$content)
	{
		$config = KunenaFactory::getConfig();

		if ($config->jmambot) {
			$row = new stdClass();
			$row->text =& $content;
			$params = new JParameter( '' );
			$params->set('ksource', 'kunena');

			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$results = $dispatcher->trigger('onPrepareContent', array (&$row, &$params, 0));
			$content =& $row->text;
		}
		return $content;
	}


	function escape($string) {
		return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
	}
}