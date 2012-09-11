<?php
$purchases = edd_get_users_purchases();	
if($purchases) :
	do_action( 'edd_before_download_history' );
	?>
	<table id="edd_user_history">
		<thead>
			<tr class="edd_download_history_row">
				<?php do_action('edd_user_history_header_before'); ?>
				<th class="edd_download_download_name_header"><?php _e('Download Name', 'edd'); ?></th>
				<?php if( ! edd_no_redownload() ) { ?>
					<th class="edd_download_download_files_header"><?php _e('Files', 'edd'); ?></th>
				<?php } ?>							
				<?php do_action('edd_user_history_header_after'); ?>
			</tr>
		</thead>
		<?php 
		foreach($purchases as $post) {

			setup_postdata( $post );

			$downloads = edd_get_downloads_of_post( $post->ID );
			$payment_meta = edd_get_payment_meta( $post->ID );

			if($downloads) {

				foreach( $downloads as $download ) {

					echo '<tr class="edd_download_history_row">';

						$id = isset( $payment_meta['cart_details'] ) ? $download['id'] : $download;

						$price_id = isset($download['options']['price_id']) ? $download['options']['price_id'] : null;
						
						$download_files = edd_get_download_files( $id, $price_id );

						do_action( 'edd_user_history_table_begin', $post->ID );

						echo '<td>' . get_the_title( $id ) . '</td>';

						if( ! edd_no_redownload() ) {		

							echo '<td>';

							if($download_files) {
								foreach($download_files as $filekey => $file) {

									$download_url = edd_get_download_file_url( $payment_meta['key'], $payment_meta['email'], $filekey, $id );
									echo'<div class="edd_download_file"><a href="' . esc_url( $download_url ) . '" class="edd_download_file_link">' . esc_html( $file['name'] ) . '</a></div>';
								
								} 
							} else {
								_e('No downloadable files found.', 'edd');
							}

							echo '</td>';

						}

						do_action('edd_user_history_table_end', $post->ID);

					echo '</tr>';

				}
				wp_reset_postdata();
			}
		}
	?>
	</table>
	<?php 
	do_action( 'edd_after_download_history' );
else : ?>
	<p class="edd-no-downloads"><?php _e('You have not purchased any downloads', 'edd'); ?></p>
<?php endif; ?>