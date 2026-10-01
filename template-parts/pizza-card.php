<?php
/**
 * Riga di un prodotto nella lista del menu.
 *
 * @package Pizzumm
 *
 * @var WP_Post $pizzumm_product Prodotto corrente.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pizzumm_id     = $pizzumm_product->ID;
$pizzumm_prezzo = get_post_meta( $pizzumm_id, 'pizzumm_prezzo', true );
$pizzumm_ing    = get_post_meta( $pizzumm_id, 'pizzumm_ingredienti', true );
$pizzumm_all    = get_post_meta( $pizzumm_id, 'pizzumm_allergeni', true );
$pizzumm_badge  = get_post_meta( $pizzumm_id, 'pizzumm_badge', true );
$pizzumm_btype  = get_post_meta( $pizzumm_id, 'pizzumm_badge_tipo', true );
$pizzumm_fb     = get_post_meta( $pizzumm_id, '_pizzumm_fallback_image', true );
$pizzumm_fb     = $pizzumm_fb ? $pizzumm_fb : 'stock/menu-classiche.jpg';
$pizzumm_round  = str_replace( 'stock/', 'stock/round-', $pizzumm_fb );

if ( ! file_exists( PIZZUMM_DIR . '/assets/img/' . $pizzumm_round ) ) {
	$pizzumm_round = $pizzumm_fb;
}

$pizzumm_flag_class = 'dish__flag';

if ( 'veg' === $pizzumm_btype ) {
	$pizzumm_flag_class .= ' dish__flag--veg';
} elseif ( 'new' === $pizzumm_btype ) {
	$pizzumm_flag_class .= ' dish__flag--new';
}
?>

<li class="dish">
	<div class="dish__text">
		<h3 class="dish__name">
			<?php echo esc_html( get_the_title( $pizzumm_id ) ); ?>
			<?php if ( $pizzumm_badge ) : ?>
				<span class="<?php echo esc_attr( $pizzumm_flag_class ); ?>">
					<?php
					if ( 'veg' === $pizzumm_btype ) {
						pizzumm_the_icon( 'leaf' );
					} elseif ( 'new' === $pizzumm_btype ) {
						pizzumm_the_icon( 'sparkle' );
					} else {
						pizzumm_the_icon( 'flame' );
					}
					?>
					<?php echo esc_html( $pizzumm_badge ); ?>
				</span>
			<?php endif; ?>
		</h3>

		<?php if ( $pizzumm_ing ) : ?>
			<p class="dish__ing">
				<?php echo esc_html( $pizzumm_ing ); ?>
				<?php if ( $pizzumm_all ) : ?>
					<span class="dish__allerg"><?php esc_html_e( 'Allergeni:', 'pizzumm' ); ?> <?php echo esc_html( $pizzumm_all ); ?></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( $pizzumm_prezzo ) : ?>
		<span class="dish__price"><?php echo esc_html( $pizzumm_prezzo ); ?></span>
	<?php endif; ?>

	<?php
	if ( has_post_thumbnail( $pizzumm_id ) ) {
		echo get_the_post_thumbnail(
			$pizzumm_id,
			'thumbnail',
			array( 'class' => 'dish__thumb', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title( $pizzumm_id ) ) )
		);
	} else {
		printf(
			'<img class="dish__thumb" src="%1$s" alt="%2$s" width="300" height="300" loading="lazy" decoding="async" />',
			esc_url( pizzumm_asset( 'img/' . $pizzumm_round ) ),
			esc_attr( get_the_title( $pizzumm_id ) )
		);
	}
	?>
</li>
