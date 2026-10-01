<?php
/**
 * Modulo di ricerca.
 *
 * @package Pizzumm
 */

?>
<form role="search" method="get" class="form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<p class="field" style="margin:0">
		<label for="pizzumm-search"><?php esc_html_e( 'Cerca nel sito', 'pizzumm' ); ?></label>
		<input type="search" id="pizzumm-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
	</p>
	<p style="margin:0">
		<button type="submit" class="btn btn--sm"><?php esc_html_e( 'Cerca', 'pizzumm' ); ?></button>
	</p>
</form>
