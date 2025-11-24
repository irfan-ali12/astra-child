<?php
/**
 * Single Post Template
 * Blog details page for KachoTech.
 *
 * @package KachoTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Custom callback for KachoTech comments
 */
if ( ! function_exists( 'kachotech_comment_callback' ) ) {
	function kachotech_comment_callback( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class( 'kt-comment' ); ?> id="comment-<?php comment_ID(); ?>">
			<div class="kt-comment-inner">
				<div class="kt-comment-avatar-block">
					<div class="kt-comment-avatar">
						<?php echo get_avatar( $comment, 56 ); ?>
					</div>
					<div class="kt-comment-author">
						<?php echo get_comment_author_link(); ?>
					</div>
					<div class="kt-comment-meta">
						<?php
						printf(
							esc_html__( '%1$s at %2$s', 'kachotech-child' ),
							get_comment_date(),
							get_comment_time()
						);
						?>
					</div>
				</div>

				<div class="kt-comment-main">
					<div class="kt-comment-text">
						<?php if ( '0' === $comment->comment_approved ) : ?>
							<p class="kt-comment-awaiting">
								<?php esc_html_e( 'Your comment is awaiting moderation.', 'kachotech-child' ); ?>
							</p>
						<?php endif; ?>

						<?php comment_text(); ?>
					</div>

					<div class="kt-comment-actions">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
									'reply_text' => esc_html__( 'Reply', 'kachotech-child' ),
									'class'      => 'kt-comment-reply-link',
								)
							)
						);
						?>
					</div>
				</div>
			</div>
		</li>
		<?php
	}
}


get_header();
?>


<style>
  .kt-single-post {
    --kt-primary: #ff2446;
    --kt-primary-soft: rgba(255, 36, 70, 0.08);
    --kt-dark: #111827;
    --kt-text: #111827;
    --kt-muted: #6b7280;
    --kt-border: #e5e7eb;
    --kt-bg: #f9fafb;
    --kt-card-bg: #ffffff;

    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--kt-text);
    background: #ffffff;
    padding: 32px 0 56px;
    margin-top: 45px;
  }
  .kt-single-post .kt-widget .kt-single-search-button {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    border-radius: 999px;
    width: 26px;
    height: 26px;
    font-size: 13px;
    background: var(--kt-primary);
    color: #ffffff;
    cursor: pointer;
    padding-top: 5px;
    padding-right: 25px;
    padding-bottom: 15px;
    padding-left: 5px;
}

  .kt-single-post .kt-wrap {
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 16px;
  }

  /* Breadcrumb */
  .kt-single-post .kt-breadcrumb {
    font-size: 13px;
    color: var(--kt-muted);
    margin-bottom: 4px;
  }

  .kt-single-post .kt-breadcrumb a {
    color: inherit;
    text-decoration: none;
  }

  .kt-single-post .kt-breadcrumb a:hover {
    color: var(--kt-primary);
  }

  /* Layout */
  .kt-single-post .kt-layout {
    display: grid;
    grid-template-columns: minmax(0, 2.1fr) minmax(260px, 0.9fr);
    gap: 32px;
    align-items: flex-start;
    margin-top: 8px;
  }

  @media (max-width: 980px) {
    .kt-single-post .kt-layout {
      grid-template-columns: minmax(0, 1fr);
    }
  }

  /* MAIN COLUMN */
  .kt-single-post .kt-main {
    background: var(--kt-card-bg);
    border-radius: 15px;
    border: 1px solid var(--kt-border);
    padding: 18px 18px 20px;
  }

  .kt-single-post .kt-featured {
    margin-bottom: 10px;
  }

  .kt-single-post .kt-featured img {
    width: 100%;
    height: 300px;
    display: block;
    border-radius: 15px;
    object-fit: cover;
  }

  .kt-single-post .kt-meta-bar {
    font-size: 13px;
    color: var(--kt-muted);
    margin-bottom: 8px;
  }

  .kt-single-post .kt-meta-bar span + span::before {
    content: "•";
    margin: 0 6px;
  }

  .kt-single-post .kt-title {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 14px;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-content {
    font-size: 15px;
    line-height: 1.7;
    color: #111827;
  }

  .kt-single-post .kt-content p {
    margin: 0 0 14px;
  }

  .kt-single-post .kt-content h2,
  .kt-single-post .kt-content h3,
  .kt-single-post .kt-content h4 {
    margin: 16px 0 8px;
    font-weight: 600;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-content ul,
  .kt-single-post .kt-content ol {
    margin: 0 0 14px 20px;
  }

  /* Tags + share */
  .kt-single-post .kt-footer-meta {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 12px;
    margin-top: 22px;
    padding-top: 14px;
    border-top: 1px solid var(--kt-border);
  }

  .kt-single-post .kt-tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .kt-single-post .kt-tag-chip {
    border-radius: 999px;
    background: #f9fafb;
    border: 1px solid var(--kt-border);
    padding: 5px 12px;
    font-size: 12px;
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-single-post .kt-tag-chip:hover {
    border-color: var(--kt-primary);
    color: var(--kt-primary);
    background: var(--kt-primary-soft);
  }

  .kt-single-post .kt-share {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--kt-muted);
  }

  .kt-single-post .kt-share-icons {
    display: flex;
    gap: 8px;
  }

  .kt-single-post .kt-share-icon {
    width: 32px;
    height: 32px;
    border-radius: 999px;
    border: 1px solid var(--kt-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    text-decoration: none;
    color: var(--kt-muted);
    background: #ffffff;
  }

  .kt-single-post .kt-share-icon:hover {
    border-color: var(--kt-primary);
    color: var(--kt-primary);
  }

  /* Prev / Next navigation */
  .kt-single-post .kt-post-nav {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid var(--kt-border);
    font-size: 13px;
  }

  .kt-single-post .kt-post-nav-card {
    display: grid;
    grid-template-columns: 80px minmax(0, 1fr);
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--kt-border);
    background: #f9fafb;
    text-decoration: none;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-post-nav-card.right {
    grid-template-columns: minmax(0, 1fr) 80px;
    text-align: right;
  }

  .kt-single-post .kt-post-nav-thumb {
    width: 80px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    background: #e5e7eb;
  }

  .kt-single-post .kt-post-nav-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .kt-single-post .kt-post-nav-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--kt-muted);
    margin-bottom: 2px;
  }

  .kt-single-post .kt-post-nav-title {
    font-size: 13px;
    font-weight: 500;
  }

  .kt-single-post .kt-post-nav-card:hover {
    border-color: var(--kt-primary);
    background: var(--kt-primary-soft);
  }

  /* SIDEBAR – same style as archive */
  .kt-single-post .kt-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .kt-single-post .kt-widget {
    background: var(--kt-card-bg);
    border-radius: 12px;
    border: 1px solid var(--kt-border);
    padding: 14px 14px 16px;
  }

  .kt-single-post .kt-widget-title {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 8px;
    color: var(--kt-dark);
  }

  /* Search */
  .kt-single-post .kt-search-form {
    position: relative;
  }

  .kt-single-post .kt-search-form input[type="search"] {
    width: 100%;
    border-radius: 999px;
    border: 1px solid var(--kt-border);
    padding: 8px 32px 8px 10px;
    font-size: 14px;
    outline: none;
    background-color: #f9fafb;
    transition: border-color 0.16s ease, box-shadow 0.16s ease;
  }

  .kt-single-post .kt-search-form input[type="search"]:focus {
    border-color: var(--kt-primary);
    box-shadow: 0 0 0 1px var(--kt-primary-soft);
    background-color: #ffffff;
  }

  .kt-single-post .kt-search-form button {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    border-radius: 999px;
    width: 26px;
    height: 26px;
    font-size: 13px;
    background: var(--kt-primary);
    color: #ffffff;
    cursor: pointer;
  }

  /* Recent posts */
  .kt-single-post .kt-recent-list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .kt-single-post .kt-recent-item {
    display: grid;
    grid-template-columns: 60px minmax(0, 1fr);
    gap: 8px;
    padding: 7px 0;
    border-bottom: 1px solid var(--kt-border);
  }

  .kt-single-post .kt-recent-item:last-child {
    border-bottom: none;
  }

  .kt-single-post .kt-recent-thumb {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    overflow: hidden;
    background: #e5e7eb;
  }

  .kt-single-post .kt-recent-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .kt-single-post .kt-recent-meta {
    font-size: 11px;
    color: var(--kt-muted);
    margin-bottom: 1px;
  }

  .kt-single-post .kt-recent-title {
    font-size: 13px;
    font-weight: 500;
    margin: 0;
  }

  .kt-single-post .kt-recent-title a {
    color: var(--kt-dark);
    text-decoration: none;
  }

  .kt-single-post .kt-recent-title a:hover {
    color: var(--kt-primary);
  }

  /* Categories */
  .kt-single-post .kt-cat-list {
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 14px;
  }

  .kt-single-post .kt-cat-list li {
    padding: 3px 0;
  }

  .kt-single-post .kt-cat-list a {
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-single-post .kt-cat-list a:hover {
    color: var(--kt-primary);
  }

  /* Tags widget */
  .kt-single-post .kt-tags-widget {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }

  .kt-single-post .kt-tags-widget a {
    border-radius: 999px;
    background: #f9fafb;
    border: 1px solid var(--kt-border);
    padding: 3px 9px;
    font-size: 12px;
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-single-post .kt-tags-widget a:hover {
    border-color: var(--kt-primary);
    color: var(--kt-primary);
    background: var(--kt-primary-soft);
  }

  /* Sidebar banner */
  .kt-single-post .kt-banner {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--kt-border);
    background: #f9fafb;
  }

  .kt-single-post .kt-banner img {
    width: 100%;
    height: auto;
    display: block;
  }

  .kt-single-post .kt-banner-content {
    padding: 10px 12px 12px;
    font-size: 13px;
  }

  .kt-single-post .kt-banner-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--kt-muted);
    margin-bottom: 2px;
  }

  .kt-single-post .kt-banner-heading {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-banner-cta {
    display: inline-flex;
    align-items: center;
    margin-top: 4px;
    font-size: 12px;
    padding: 4px 9px;
    border-radius: 999px;
    border: 1px solid var(--kt-primary);
    background: #ffffff;
    color: var(--kt-primary);
    text-decoration: none;
  }

  .kt-single-post .kt-banner-cta span {
    margin-left: 4px;
  }

  .kt-single-post .kt-banner-cta:hover {
    background: var(--kt-primary);
    color: #ffffff;
  }

     /* COMMENTS SKIN */
  .kt-single-post .kt-comments {
    margin-top: 26px;
    padding-top: 18px;
    border-top: 1px solid var(--kt-border);
  }

  .kt-single-post .kt-comments-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 12px;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-comment-list {
    list-style: none;
    margin: 0 0 18px;
    padding: 0;
  }

  .kt-single-post .kt-comment {
    border-bottom: 1px solid var(--kt-border);
    padding: 14px 0;
  }

  .kt-single-post .kt-comment-inner {
    display: flex;
    gap: 16px;
    align-items: flex-start;
  }

  .kt-single-post .kt-comment-avatar-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    min-width: 80px;
  }

  .kt-single-post .kt-comment-avatar img {
    border-radius: 999px;
    display: block;
  }

  .kt-single-post .kt-comment-author {
    font-weight: 600;
    font-size: 14px;
    color: var(--kt-dark);
    text-align: center;
  }

  .kt-single-post .kt-comment-meta {
    font-size: 11px;
    color: var(--kt-muted);
    text-align: center;
  }

  .kt-single-post .kt-comment-main {
    flex: 1;
  }

  .kt-single-post .kt-comment-text {
    font-size: 14px;
    color: #111827;
    margin-bottom: 4px;
  }

  .kt-single-post .kt-comment-awaiting {
    font-size: 12px;
    color: var(--kt-muted);
    margin-bottom: 4px;
  }

  .kt-single-post .kt-comment-actions {
    font-size: 12px;
  }

  .kt-single-post .kt-comment-reply-link {
    color: var(--kt-primary);
    text-decoration: none;
  }

  .kt-single-post .kt-comment-reply-link:hover {
    text-decoration: underline;
  }

  /* Comment form */
  .kt-single-post .kt-comment-form-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 10px;
    color: var(--kt-dark);
  }

  .kt-single-post .kt-comment-form {
    margin-top: 8px;
  }

  .kt-single-post .kt-comment-form p {
    margin-bottom: 10px;
  }

  .kt-single-post .kt-comment-form input[type="text"],
  .kt-single-post .kt-comment-form input[type="email"],
  .kt-single-post .kt-comment-form textarea {
    width: 100%;
    border-radius: 10px;
    border: 1px solid var(--kt-border);
    padding: 8px 10px;
    font-size: 14px;
    background: #f9fafb;
    outline: none;
    transition: border-color .16s ease, box-shadow .16s ease;
  }

  .kt-single-post .kt-comment-form textarea {
    min-height: 120px;
  }

  .kt-single-post .kt-comment-form input:focus,
  .kt-single-post .kt-comment-form textarea:focus {
    border-color: var(--kt-primary);
    box-shadow: 0 0 0 1px var(--kt-primary-soft);
    background: #ffffff;
  }

  .kt-single-post .kt-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 18px;
    border-radius: 999px;
    border: none;
    background: var(--kt-primary);
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
  }

  .kt-single-post .kt-btn-submit:hover {
    background: #e01838;
  }

  @media (max-width: 640px) {
    .kt-single-post .kt-comment-inner {
      align-items: flex-start;
    }

    .kt-single-post .kt-comment-avatar-block {
      min-width: 64px;
    }
    .kt-single-post .kt-featured img {
        height: 200px;
  }

  
</style>

<div class="kt-single-post">
  <div class="kt-wrap">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <div class="kt-breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> /
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">Blog</a> /
        <span><?php the_title(); ?></span>
      </div>

      <div class="kt-layout">

        <!-- MAIN -->
        <article class="kt-main" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

          <?php
          $fallback_thumb = 'https://images.pexels.com/photos/373543/pexels-photo-373543.jpeg?auto=compress&cs=tinysrgb&w=1200';
          ?>
          <div class="kt-featured">
            <?php
            if ( has_post_thumbnail() ) :
              the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
            else :
              ?>
              <img src="<?php echo esc_url( $fallback_thumb ); ?>"
                   alt="<?php echo esc_attr( get_the_title() ); ?>">
            <?php endif; ?>
          </div>

          <div class="kt-meta-bar">
            <span><?php echo get_the_date(); ?></span>
            <?php
            $comments_count = get_comments_number();
            ?>
            <span>
              <?php echo esc_html( $comments_count ); ?>
              <?php echo ( 1 === $comments_count ) ? 'Comment' : 'Comments'; ?>
            </span>
          </div>

          <h1 class="kt-title"><?php the_title(); ?></h1>

          <div class="kt-content">
            <?php the_content(); ?>
          </div>

          <!-- Tags + Share -->
          <div class="kt-footer-meta">
            <div class="kt-tags-list">
              <?php
              $post_tags = get_the_tags();
              if ( $post_tags ) :
                foreach ( $post_tags as $tag ) :
                  ?>
                  <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="kt-tag-chip">
                    <?php echo esc_html( $tag->name ); ?>
                  </a>
                  <?php
                endforeach;
              endif;
              ?>
            </div>

            <div class="kt-share">
              <span>Share:</span>
              <div class="kt-share-icons">
                <?php
                $url   = urlencode( get_permalink() );
                $title = urlencode( get_the_title() );
                ?>
                <a class="kt-share-icon" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
                  <span class="dashicons dashicons-facebook"></span>
                </a>
                <a class="kt-share-icon" href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener" aria-label="Share on Twitter">
                  <span class="dashicons dashicons-twitter"></span>
                </a>
                <a class="kt-share-icon" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url; ?>&title=<?php echo $title; ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
                  <span class="dashicons dashicons-linkedin"></span>
                </a>
                <a class="kt-share-icon" href="https://www.instagram.com/?url=<?php echo $url; ?>" target="_blank" rel="noopener" aria-label="Share on Instagram">
                  <span class="dashicons dashicons-instagram"></span>
                </a>
              </div>
            </div>
          </div>

          <!-- Prev / Next cards -->
          <div class="kt-post-nav">
            <div>
              <?php
              $prev_post = get_previous_post();
              if ( $prev_post ) :
                $prev_thumb = get_the_post_thumbnail_url( $prev_post->ID, 'thumbnail' );
                if ( ! $prev_thumb ) {
                  $prev_thumb = $fallback_thumb;
                }
                ?>
                <a class="kt-post-nav-card" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
                  <div class="kt-post-nav-thumb">
                    <img src="<?php echo esc_url( $prev_thumb ); ?>"
                         alt="<?php echo esc_attr( get_the_title( $prev_post->ID ) ); ?>">
                  </div>
                  <div>
                    <div class="kt-post-nav-label">Previous Post</div>
                    <div class="kt-post-nav-title">
                      <?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
                    </div>
                  </div>
                </a>
              <?php endif; ?>
            </div>

            <div>
              <?php
              $next_post = get_next_post();
              if ( $next_post ) :
                $next_thumb = get_the_post_thumbnail_url( $next_post->ID, 'thumbnail' );
                if ( ! $next_thumb ) {
                  $next_thumb = $fallback_thumb;
                }
                ?>
                <a class="kt-post-nav-card right" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
                  <div>
                    <div class="kt-post-nav-label">Next Post</div>
                    <div class="kt-post-nav-title">
                      <?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
                    </div>
                  </div>
                  <div class="kt-post-nav-thumb">
                    <img src="<?php echo esc_url( $next_thumb ); ?>"
                         alt="<?php echo esc_attr( get_the_title( $next_post->ID ) ); ?>">
                  </div>
                </a>
              <?php endif; ?>
            </div>
          </div>

                    <!-- Comments -->
                    <!-- Comments -->
          <div class="kt-comments">
            <?php
            $comments_number = get_comments_number();
            ?>
            <h3 class="kt-comments-title">
              <?php
              if ( $comments_number > 0 ) {
                printf(
                  esc_html(
                    _nx( '1 Comment', '%1$s Comments', $comments_number, 'comments title', 'kachotech-child' )
                  ),
                  number_format_i18n( $comments_number )
                );
              } else {
                esc_html_e( 'Comments', 'kachotech-child' );
              }
              ?>
            </h3>

            <?php if ( $comments_number > 0 ) : ?>
              <ol class="kt-comment-list">
                <?php
                wp_list_comments(
                  array(
                    'style'       => 'ol',
                    'avatar_size' => 48,
                    'short_ping'  => true,
                    'callback'    => 'kachotech_comment_callback',
                  )
                );
                ?>
              </ol>

              <?php the_comments_pagination(); ?>
            <?php else : ?>
              <p style="font-size:13px;color:var(--kt-muted);margin-bottom:14px;">
                <?php esc_html_e( 'No comments yet. Be the first to share your thoughts.', 'kachotech-child' ); ?>
              </p>
            <?php endif; ?>

            <?php
            comment_form(
              array(
                'class_form'         => 'kt-comment-form',
                'title_reply_before' => '<h3 id="reply-title" class="kt-comment-form-title">',
                'title_reply_after'  => '</h3>',
                'class_submit'       => 'kt-btn-submit',
              )
            );
            ?>
          </div>



        </article>

        <!-- SIDEBAR -->
        <aside class="kt-sidebar">

          <!-- Search -->
          <section class="kt-widget">
            <h3 class="kt-widget-title">Search</h3>
            <form role="search" method="get" class="kt-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
              <label class="screen-reader-text" for="kt-single-search">Search for:</label>
              <input type="search" id="kt-single-search" name="s" placeholder="Search keyword">
              <button type="submit" class='kt-single-search-button'><span class="kt-search-button-icon dashicons dashicons-search single-search-icon" ></span></button>
            </form>
          </section>
          <!-- Recent posts -->
          <section class="kt-widget">
            <h3 class="kt-widget-title">Recent Posts</h3>
            <ul class="kt-recent-list">
              <?php
              $recent_args  = array(
                'post_type'           => 'post',
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => true,
              );
              $recent_query = new WP_Query( $recent_args );

              $recent_placeholders = array(
                'https://images.pexels.com/photos/3951628/pexels-photo-3951628.jpeg?auto=compress&cs=tinysrgb&w=600',
                'https://images.pexels.com/photos/3738739/pexels-photo-3738739.jpeg?auto=compress&cs=tinysrgb&w=600',
                'https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600',
              );
              $ph_index            = 0;

              if ( $recent_query->have_posts() ) :
                while ( $recent_query->have_posts() ) :
                  $recent_query->the_post();
                  if ( has_post_thumbnail() ) {
                    $thumb = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
                  } else {
                    $thumb = $recent_placeholders[ $ph_index % count( $recent_placeholders ) ];
                  }
                  $ph_index++;
                  ?>
                  <li class="kt-recent-item">
                    <a href="<?php the_permalink(); ?>" class="kt-recent-thumb">
                      <img src="<?php echo esc_url( $thumb ); ?>"
                           alt="<?php echo esc_attr( get_the_title() ); ?>">
                    </a>
                    <div>
                      <div class="kt-recent-meta"><?php echo get_the_date( 'M d, Y' ); ?></div>
                      <h4 class="kt-recent-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      </h4>
                    </div>
                  </li>
                  <?php
                endwhile;
                wp_reset_postdata();
              else :
                ?>
                <li>No recent posts</li>
              <?php endif; ?>
            </ul>
          </section>

          <!-- Categories -->
          <section class="kt-widget">
            <h3 class="kt-widget-title">Category</h3>
            <ul class="kt-cat-list">
              <?php
              wp_list_categories(
                array(
                  'title_li'   => '',
                  'show_count' => false,
                )
              );
              ?>
            </ul>
          </section>

          <!-- Tags widget -->
          <section class="kt-widget">
            <h3 class="kt-widget-title">Tags</h3>
            <div class="kt-tags-widget">
              <?php
              $all_tags = get_tags();
              if ( $all_tags ) :
                foreach ( $all_tags as $tag ) :
                  ?>
                  <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">
                    <?php echo esc_html( $tag->name ); ?>
                  </a>
                  <?php
                endforeach;
              else :
                ?>
                <span style="font-size:13px;color:var(--kt-muted);">No tags yet.</span>
              <?php endif; ?>
            </div>
          </section>

          <!-- Promo banner -->
          <section class="kt-banner">
            <img src="https://images.pexels.com/photos/2950003/pexels-photo-2950003.jpeg?auto=compress&cs=tinysrgb&w=800"
                 alt="KachoTech promo banner">
            <div class="kt-banner-content">
              <div class="kt-banner-label">Winter Offer</div>
              <div class="kt-banner-heading">Up to 50% OFF on<br>Selected Heaters</div>
              <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="kt-banner-cta">
                Shop Now <span>➜</span>
              </a>
            </div>
          </section>

        </aside>

      </div><!-- .kt-layout -->

    <?php endwhile; endif; ?>

  </div><!-- .kt-wrap -->
</div><!-- .kt-single-post -->

<?php
get_footer();
