<?php
/* Template Name: Contact Form */

if (isset($_POST['contact_submit'])) {
	//check_ajax_referer('contact_nonce', 'security');
	$nonce = $_POST['security'];
	if (!wp_verify_nonce($nonce, 'contact_nonce')) {
		die;
	}
	$contact = g7_contact_send();
	switch ($contact['status']) {
		case '1':
			$email_sent = 1;
			break;
		case '2':
			$email_not_sent = 1;
			break;
		case '3':
			$contact_error = $contact['error'];
			break;
	}
} else {
	$contact['name'] = '';
	$contact['email'] = '';
	$contact['message'] = '';
}

get_header();
?>

<?php get_template_part('wrapper', 'start'); ?>

<?php while (have_posts()) : the_post(); ?>

	<?php $content = get_the_content(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('box mb20'); ?>>

		<header class="entry-header mb20">
			<?php g7_breadcrumbs(); ?>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php edit_post_link(__('Edit', 'g7theme'), '<div class="entry-meta">(', ')</div>'); ?>
		</header>

		<?php if (!empty($content)) : ?>
			<div class="entry-content clearfix mb40">
				<?php the_content(); ?>
			</div>
			<?php wp_link_pages(array(
				'before'         => '<footer class="entry-footer mt20"><p><strong>' . __('Pages', 'g7theme') . ':</strong> ',
				'after'          => '</p></footer>',
				'next_or_number' => 'number',
			)); ?>
		<?php endif; ?>

		<?php if (isset($contact_error)) : ?>
			<div class="error msg">
				<h3><?php _e('Error', 'g7theme'); ?></h3>
				<?php echo implode('<br>', $contact_error); ?>
			</div>
		<?php endif; ?>

		<?php if (isset($email_sent)) : ?>
			<div class="success msg">
				<h3><?php _e('Success', 'g7theme'); ?></h3>
				<p><?php echo $contact['msg']; ?></p>
			</div>
		<?php endif; ?>

		<?php if (isset($email_not_sent)) : ?>
			<div class="error msg">
				<h3><?php _e('Error', 'g7theme'); ?></h3>
				<p><?php echo $contact['msg']; ?></p>
			</div>
		<?php endif; ?>

		<?php if (!isset($email_sent)) : ?>
		<form method="post" id="g7-contact-form">
			<input type="hidden" name="action" value="g7_contact_form">
			<input type="hidden" name="security" value="<?php echo wp_create_nonce('contact_nonce'); ?>">
			<p>
				<label for="g7-contact-name"><?php _e('Name', 'g7theme'); ?>:</label>
				<input id="g7-contact-name" name="contact_name" type="text" value="<?php echo $contact['name']; ?>">
			</p>
			<p>
				<label for="g7-contact-email"><?php _e('Email', 'g7theme'); ?>:</label>
				<input id="g7-contact-email" name="contact_email" type="text" value="<?php echo $contact['email']; ?>">
			</p>
			<p>
				<label for="g7-contact-message"><?php _e('Message', 'g7theme'); ?>:</label>
				<textarea id="g7-contact-message" name="contact_message" rows="8"><?php echo $contact['message']; ?></textarea>
			</p>
			<p class="contact_h">
				<label><?php _e('Please leave this field blank', 'g7theme'); ?>:</label>
				<input name="contact_body" type="text" value="">
			</p>
			<p>
				<button name="contact_submit" type="submit" id="g7-contact-submit" class="btn medium"><?php _e('Send', 'g7theme'); ?></button>
			</p>
		</form>
		<?php endif; ?>

	</article>

<?php endwhile; ?>

<?php get_template_part('wrapper', 'end'); ?>

<?php get_footer(); ?>