<?php
/**
 * @version		2.1
 * @package		User Extended Fields for K2 (K2 plugin)
 * @author    JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

?>

<?php if($contactDetails || $socialProfiles): ?>
<div class="userExtendedFields">

	<?php if($contactDetails && ($address || $city || $stateOrProvince || $mobile)): ?>
	<div class="userExtendedFieldsContactDetails">
		<h3><?php echo JText::_('PLG_K2_UEF_CONTACT_DETAILS'); ?></h3>
		<ul>
			<?php if($address): ?>
			<li>
				<div class="userElementLabel"><?php echo JText::_('PLG_K2_UEF_ADDRESS'); ?></div>
				<div class="userElementValue"><?php echo $address; ?></div>
			</li>
			<?php endif; ?>

			<?php if($city): ?>
			<li>
				<div class="userElementLabel"><?php echo JText::_('PLG_K2_UEF_CITY'); ?></div>
				<div class="userElementValue"><?php echo $city; ?></div>
			</li>
			<?php endif; ?>

			<?php if($stateOrProvince): ?>
			<li>
				<div class="userElementLabel"><?php echo JText::_('PLG_K2_UEF_STATE_OR_PROVINCE'); ?></div>
				<div class="userElementValue"><?php echo $stateOrProvince; ?></div>
			</li>
			<?php endif; ?>

			<?php if($mobile): ?>
			<li>
				<div class="userElementLabel"><?php echo JText::_('PLG_K2_UEF_MOBILE'); ?></div>
				<div class="userElementValue"><?php echo $mobile; ?></div>
			</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="clr"></div>
	<?php endif; ?>

	<?php if($socialProfiles && ($facebook || $twitter || $google)): ?>
	<div class="userExtendedFieldsSocialProfiles">
		<h3><?php echo JText::_('PLG_K2_UEF_SOCIAL_PROFILES'); ?></h3>

		<?php if($facebook): ?>
		<a href="http://www.facebook.com/<?php echo $facebook; ?>" target="_blank" rel="nofollow" class="uefSocialLink facebook" title="<?php echo JText::_('PLG_K2_UEF_FACEBOOK'); ?>">
			<span><?php echo JText::_('PLG_K2_UEF_FACEBOOK'); ?></span>
		</a>
		<?php endif; ?>

		<?php if($twitter): ?>
		<a href="http://twitter.com/<?php echo $twitter; ?>" target="_blank" rel="nofollow" class="uefSocialLink twitter" title="<?php echo JText::_('PLG_K2_UEF_TWITTER'); ?>">
			<span><?php echo JText::_('PLG_K2_UEF_TWITTER'); ?></span>
		</a>
		<?php endif; ?>

		<?php if($google): ?>
		<a href="https://plus.google.com/<?php echo $google; ?>/" target="_blank" rel="nofollow" class="uefSocialLink google" title="<?php echo JText::_('PLG_K2_UEF_GOOGLE_PLUS'); ?>">
			<span><?php echo JText::_('PLG_K2_UEF_GOOGLE_PLUS'); ?></span>
		</a>
		<?php endif; ?>

		<div class="clr"></div>
	</div>
	<?php endif; ?>

</div>
<?php endif; ?>
