<?php
/**
 *
 * Admoin setting Template.
 *
 * @package cb-invoice
 */

$cb_api_key      = ( isset( $_REQUEST['cb_invoice']['cb_api_key'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['cb_invoice']['cb_api_key'] ) ) : $config['cb_api_key'];
$cb_site = ( isset( $_REQUEST['cb_invoice']['cb_site'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['cb_invoice']['cb_site'] ) ) : $config['cb_site'];
 


?>
<div class="wrap">
  <h2><?php echo esc_html__( 'Settings' ); ?></h2>
  <div class="">
	<form method="post" class="dis-file-uploader" enctype="multipart/form-data" >
	  <?php wp_nonce_field( 'cb_invoice_action', 'cb_invoice_action' ); ?>
	  <table class="form-table" role="presentation">
		<tbody>
		  <tr>
			<th scope="row"> <label><?php echo esc_html__( 'API Key' ); ?></label>
			</th>
			<td><input type="text" class="regular-text" id="api_key" name="cb_invoice[cb_api_key]" value="<?php echo wp_kses_post( $cb_api_key ); ?>" required /></td>
		  </tr>
		  <tr>
			<th scope="row"> <label><?php echo esc_html__( 'Site' ); ?></label>
			</th>
			<td><input type="text" class="regular-text" id="site" name="cb_invoice[cb_site]" value="<?php echo wp_kses_post( $cb_site ); ?>" required /></td>
		  </tr>
		  
		  <tr>
			<th scope="row"> <input name="settings-updated" type="hidden" value="1" />
			  <input type="submit"  class="button button-primary"  value="<?php echo esc_html__( 'Save Changes' ); ?>">
			</th>
		  </tr>
		</tbody>
	  </table>
	</form>
  </div>
</div>
