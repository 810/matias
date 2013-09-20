<?php
/**
 * Kunena Component
 * @package Kunena.Template.Crypsis
 * @subpackage Topic
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>

<div class="bubble me" style="width:100%">
		<div class="kheader">
				<div class="kheader-padding">
						<h5><span class="visible-phone span1"><?php echo $this->user->getAvatarImage('img-polaroid'); ?> </span> <?php echo $this->profile->getLink() ?> <small  class="hidden-phone">
								<?php if($this->topic->first_post_id  == $this->message->id){
					echo 'Created a new topic.';
				} else {
					echo 'Replied the topic.';
				}
				?>
        </small> <small><span class="pull-right" title="<?php echo KunenaDate::getInstance($this->message->time)->toKunena('config_post_dateformat_hover') ?>"><?php echo KunenaDate::getInstance($this->message->time)->toKunena('config_post_dateformat') ?> <?php echo $this->numLink ?> </span></small> </h5>
      <h4><?php echo $this->message->displayField('subject') ?></h4>
    </div>
    <div class="kmsg"> <?php echo KunenaHtmlParser::parseBBCode ($this->message->message, $this->view) ?></div>
    <h5>
      <?php if ($this->signatureHtml) : ?>
      <span class="pull-left"><small> <?php echo $this->signatureHtml ?></small> </span>
      <?php endif ?>
      <?php if (!empty($this->reportMessageLink)) :?>
      <span class="pull-right"><small><?php echo $this->reportMessageLink ?></small>
      <?php if (!empty($this->ipLink)) : ?>
      <br />
      <small><?php echo $this->ipLink ?></small>
      <?php endif ?>
      </span>
      <div class="clearfix"></div>
      <?php endif ?>
    </h5>
  </div>
</div>
<?php if (!empty($this->attachments)) : ?>
<div class="span12"> <?php echo JText::_('COM_KUNENA_ATTACHMENTS');?>
  <ul>
    <?php foreach($this->attachments as $attachment) : ?>
    <li style="list-style:none; margin-bottom:5px;"> <span> <?php echo $attachment->getThumbnailLink(); ?> </span> <span> <?php echo $attachment->getTextLink(); ?> </span> </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>