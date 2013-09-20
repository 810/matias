<?php
/**
 * Kunena Component
 * @package Kunena.Template.Crypsis
 * @subpackage Topics
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined('_JEXEC') or die();

/** @var KunenaLayout $this */
/** @var bool $this->checkbox */

/** @var KunenaForumTopic $topic */
$topic = $this->topic;
$avatar = $topic->getAuthor()->getAvatarImage();

$cols = empty($this->checkbox) ? 5 : 6;
if ($this->spacing) : ?>
<tr>
	<td class="kcontenttablespacer" colspan="<?php echo $cols; ?>">&nbsp;</td>
</tr>
<?php endif; ?>

<tr>
	<td class="hidden-phone span1 center" style="width:3%">
		<?php echo $this->getTopicLink($topic, 'unread', $topic->getIcon()); ?>
	</td>
	<td class="span6">
		<div>
			<?php
			echo $this->getTopicLink($topic, null, null, null, 'hasTooltip' ) ;
			if ($topic->getUserTopic()->favorite) : ?>
				<i class="icon-star hasTooltip"><?php JText::_('COM_KUNENA_FAVORITE') ?></i>
			<?php
			endif;
			if ($topic->getUserTopic()->posts) : ?>
				<i class="icon-flag hasTooltip" ><?php JText::_('COM_KUNENA_MYPOSTS') ?></i>
			<?php endif;
			if ($topic->unread) :
				echo $this->getTopicLink($topic, 'unread',
					'<sup dir="ltr">(' . $topic->unread . ' ' . JText::_('COM_KUNENA_A_GEN_NEWCHAR') . ')</sup>');
			endif;
			if ($topic->attachments) :?>
				 <i class="icon-flag-2 hasTooltip"><?php JText::_('COM_KUNENA_ATTACH') ?></i>
			<?php endif;
			if ($topic->poll_id) :?>
				 <i class="icon-pencil hasTooltip"><?php JText::_('COM_KUNENA_ADMIN_POLLS') ?></i>
			<?php endif;?>
		</div>
		<div>
			<span class="label label-info"><?php echo JText::_('COM_KUNENA_TOPIC_ROW_TABLE_LABEL_QUESTION') ?></span>
			<?php if ($topic->locked != 0) : ?>
			<span class="label label-important">
				<i class="icon-locked"><?php JText::_('COM_KUNENA_LOCKED') ?></i>
			</span>
			<?php endif; ?>
			in <?php echo $this->getCategoryLink($topic->getCategory(), null, null, 'hasTooltip' ) ?>
		</div>
	</td>
	<td class="span1 hidden-phone center">
		<span>
			<?php echo JText::_('COM_KUNENA_GEN_HITS').':'.$this->formatLargeNumber($topic->hits); ?>
		</span>
		<span>
			<?php echo JText::_('COM_KUNENA_GEN_REPLIES').':'.$this->formatLargeNumber(max(0,$topic->getTotal()-1));?>
		</span>
	</td>
	<td class="span1 center hidden-phone" style="width:3%">
		<?php if ($avatar) : ?>
		<span>
			<?php echo $topic->getLastPostAuthor()->getLink($avatar); ?>
		</span>
		<?php endif; ?>
	</td>
	<td class="span2">
		<span class="hasTooltip" title="<?php echo KunenaDate::getInstance($topic->last_post_time)
			->toKunena('config_post_dateformat_hover'); ?>">
			<?php echo $this->getTopicLink($topic, 'last',  KunenaDate::getInstance($topic->last_post_time)->toKunena('config_post_dateformat') ); ?>
		</span>
		<br />
		<span class="hasTooltip" title="<?php echo $topic->getLastPostAuthor()->getName(); ?>">
			<?php echo $topic->getLastPostAuthor()->getLink(); ?>
		</span>
	</td>
	<?php if (!empty($this->checkbox)) : ?>
	<td class="span1">
		<input class="kcheck" type="checkbox" name="topics[<?php echo $topic->id?>]" value="1" />
	</td>
	<?php endif; ?>
	<?php
	if (!empty($this->position))
		echo $this->subLayout('Page/Module')
			->set('position', $this->position)
			->set('cols', $cols)
			->setLayout('table_row');
	?>
</tr>
