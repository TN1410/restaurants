<?php
/*
Template Name: Home
*/
?>
<style>
	.banner-section {
		padding: 20px;
		background-color: #f9f9f9;
	}
	.title-wrap {
		display: flex;
		flex-wrap: wrap;
		gap: 20px;
		justify-content: center;
	}
	.restaurants-wrapper {
		flex: 1 1 300px;
		background-color: #fff;
		padding: 20px;
		border: 1px solid #ddd;
		border-radius: 8px;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	}
	.restaurants-title {
		text-align: center;
	}
	.restaurants-title h3 {
		margin-bottom: 10px;
		font-size: 1.5em;
		color: #333;
	}
	.restaurants-title img {
		max-width: 100%;
		height: auto;
		display: block;
		margin: 0 auto;
		border-radius: 8px;
	}
	#overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.5);
		z-index: 999;
	}
	#popupDialog {
		position: fixed;
		display: none;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: white;
		padding: 20px;
		box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
		z-index: 1000;
		width: 300px;
		/ Adjust as needed /
		border-radius: 10px;
		text-align: center;
	}
	button {
		padding: 10px 15px;
		background-color: #007BFF;
		color: white;
		border: none;
		border-radius: 5px;
		cursor: pointer;
	}
	button:hover {
		background-color: #0056b3;
	}

	.overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		z-index: 1000;
	}
	.popup-dialog {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background-color: white;
		padding: 20px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		z-index: 1001;
	}
</style>
<section class="banner-section">
	<div class="title-wrap">
		<?php
		$args = array(
			'post_type' => 'restaurants',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		$posts = new WP_Query($args);
		if ($posts->have_posts()) {
			while ($posts->have_posts()) {
				$posts->the_post(); // Set up post data
				$textarea_value = get_post_meta(get_the_ID(), '_restaurants_textarea', true);
				$price_value = get_post_meta(get_the_ID(), '_restaurants_price', true);
				$link_value = get_post_meta(get_the_ID(), '_restaurants_link', true);
				?>
				<div class="restaurants-wrapper">
					<div class="restaurants-title">
						<a href="<?php the_permalink(); ?>">
							<h3><?php the_title(); ?></h3>
						</a>
						<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
					</div>
					<p><?php echo get_the_excerpt(); ?></p>
					<div class="restaurant-details">
						<?php if (!empty($price_value)) : ?>
							<div class="restaurant-price">
								<h3>Price: <?php echo esc_html($price_value); ?></h3>
							</div>
						<?php endif; ?>
						<?php if (!empty($textarea_value)) : ?>
							<div class="restaurant-textarea">
								<p><strong>Description:</strong> <?php echo nl2br(esc_html($textarea_value)); ?></p>
							</div>
						<?php endif; ?>
					</div>
					<button onclick="popupFn(<?php echo get_the_ID(); ?>)">Read More</button>
					<div id="overlay-<?php echo get_the_ID(); ?>" class="overlay"></div>
					<div id="popupDialog-<?php echo get_the_ID(); ?>" class="popup-dialog">
						<button onclick="closeFn(<?php echo get_the_ID(); ?>)">Close</button>
						<div class="qr-code">
							<?php echo $textarea_value;?>
							<h2>QR Codes for this Restaurant</h2>
							<div class="uk-container">
								<div class="uk-text-center">
									<?php
									if (!empty($link_value)) {
										$links = preg_split('/[\r\n,]+/', trim($link_value));
										foreach ($links as $link) {
											$permalink = trim($link);
											$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($permalink) . '&size=100x100';
											echo '<img src="' . esc_url($qr_code_url) . '" alt="QR Code for ' . esc_attr(get_the_title()) . '" title="QR Code" width="100" height="100" style="margin: 5px;" />';
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		wp_reset_postdata();
		?>
	</div>
</section>
<script>
	function popupFn(postId) {
		document.getElementById("overlay-" + postId).style.display = "block";
		document.getElementById("popupDialog-" + postId).style.display = "block";
	}
	function closeFn(postId) {
		document.getElementById("overlay-" + postId).style.display = "none";
		document.getElementById("popupDialog-" + postId).style.display = "none";
	}
</script>