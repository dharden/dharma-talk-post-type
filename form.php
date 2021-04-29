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
    <p class="meta-options dt_field">
        <label for="dt_series">Series Name</label>
        <input id="dt_series"
            type="text"
            name="dt_series"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dt_series', true ) ); ?>">
    </p>
    <p class="meta-options dt_field">
        <label for="dt_episode">Episode #</label>
        <input id="dt_episode"
            type="number"
            name="dt_episode"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dt_episode', true ) ); ?>">
    </p>
</div>