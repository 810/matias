<?php
/**
 * Kunena Component
 * @package Kunena.Site
 * @subpackage Views
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Statistics View
 */
class KunenaViewStatistics extends KunenaView {
	function displayDefault($tpl = null) {
		$kunena_stats = KunenaForumStatistics::getInstance ( );
		$kunena_stats->loadAll();

		$this->assign($kunena_stats);
		$this->latestMemberLink = KunenaFactory::getUser(intval($this->lastUserId))->getLink();
		$this->userlist = $this->_getUserListLink('', intval($this->get('memberCount')));

		$this->_prepareDocument();

		$this->render('Statistics/General', $tpl);
	}

	protected function _getUserListLink($action, $name, $title = null, $rel = 'nofollow'){
		$profile = KunenaFactory::getProfile ();
		$link = $profile->getUserListURL ( $action, true );

		return "<a href=\"{$link}\" title=\"{$title}\" rel=\"{$rel}\">{$name}</a>";
	}

	protected function _prepareDocument(){
		$this->setTitle(JText::_('COM_KUNENA_STAT_FORUMSTATS'));

		// TODO: set keywords and description
	}
}
