<?php
$message = '';
$massages = array();
$messages[1] = __( 'Options saved', 'otw_fc' );

if( isset( $_GET['message'] ) && isset( $messages[ $_GET['message'] ] ) ){
	$message .= $messages[ $_GET['message'] ];
}
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php _e('Plugin Options', 'otw_pc') ?>
	</h2>
	<div class="form-wrap otw-options" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_action" value="manage_otw_fc_options" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-fc-options'); ?>
			<div id="post-body">
				<div id="post-body-content">
					<?php include_once( 'otw_fc_help.php' );?>
					<?php
					echo $otw_fc_shortcode_object->shortcodes['facebook_comments']['object']->build_shortcode_editor_options();
					?>
					<div class="otw-form-control">
						<label for="otw-fc-page"><?php _e( 'Enable Facebook Comments for', 'otw_fc' )?></label>
						<div class="otw-form-control-checkbox-group">
							<div class="otw-form-control">
								<?php echo OTW_Form::checkbox( array( 'id' => 'otw-fc-page', 'name' => 'otw-fc-page', 'label' => __( 'Pages', 'otw_fc' ), 'value' => 'yes', 'parse' => $values ) );?>
							</div>
							<div class="otw-form-control">
								<?php echo OTW_Form::checkbox( array( 'id' => 'otw-fc-post', 'name' => 'otw-fc-post', 'label' => __( 'Posts', 'otw_fc' ), 'value' => 'yes', 'parse' => $values ) );?>
							</div>
							<?php if( isset( $custom_post_types ) && is_array( $custom_post_types ) && count( $custom_post_types ) ){?>
								
								<?php foreach( $custom_post_types as $cpt_type ){?>
									
									<div class="otw-form-control">
										<?php echo OTW_Form::checkbox( array( 'id' => 'otw-fc-'.$cpt_type->name, 'name' => 'otw-fc-'.$cpt_type->name, 'label' => $cpt_type->label, 'value' => 'yes', 'parse' => $values ) );?>
									</div>
									
								<?php }?>
								
							<?php }?>
						</div>
						<span class="otw-form-hint"><?php _e( 'Choose where to enable Facebook Comments.', 'otw_fc' )?> </span>
					</div>
					<p class="submit">
						<input type="submit" value="<?php _e( 'Save Options', 'otw_fc') ?>" name="submit" class="button"/>
					</p>
				</div>
			</div>
		</form>
	</div>
</div>
