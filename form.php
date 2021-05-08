<div class="dt_box">
	<style scoped>
		.dt_box{
			display: grid;
			grid-template-columns: max-content 1fr;
			grid-row-gap: 10px;
			grid-column-gap: 20px;
		}
		.dt_field{
			display: contents;
		}
	</style>
	<p class="meta-options dt_field">
		<label for="dt_youtube_link">YouTube Link</label>
		<input id="dt_youtube_link"
			type="text"
			name="dt_youtube_link"
			value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dt_youtube_link', true ) ); ?>">
	</p>
</div>