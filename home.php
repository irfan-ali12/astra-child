<?php
/**
 * Blog Index Template
 * Main template for the KachoTech blog posts page (/blog).
 *
 * @package KachoTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<style>
  .kt-blog-archive {
    /* Scoped design tokens – no :root so no global conflicts */
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
    margin-top: 40px;
  }

  .kt-blog-archive .kt-wrap {
    max-width: 1180px;
    margin: 0 auto;
    padding: 0 16px;
  }

  /* Header */
  .kt-blog-archive .kt-blog-breadcrumb {
    font-size: 13px;
    color: var(--kt-muted);
    margin-bottom: 4px;
  }

  .kt-blog-archive .kt-blog-breadcrumb a {
    color: inherit;
    text-decoration: none;
  }

  .kt-blog-archive .kt-blog-breadcrumb a:hover {
    color: var(--kt-primary);
  }

  .kt-blog-archive .kt-blog-title {
    font-size: 30px;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 4px;
  }

  .kt-blog-archive .kt-blog-subtitle {
    font-size: 15px;
    color: var(--kt-muted);
    margin: 0 0 24px;
  }

  /* Layout: content + sidebar */
  .kt-blog-archive .kt-blog-layout {
    display: grid;
    grid-template-columns: minmax(0, 2.1fr) minmax(260px, 0.9fr);
    gap: 32px;
    align-items: flex-start;
  }

  @media (max-width: 980px) {
    .kt-blog-archive .kt-blog-layout {
      grid-template-columns: minmax(0, 1fr);
    }
  }

  /* Main post list */
  .kt-blog-archive .kt-post-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  .kt-blog-archive .kt-post-card {
    background: var(--kt-card-bg);
    border-radius: 15px;
    border: 1px solid var(--kt-border);
    overflow: hidden;
  }

  .kt-blog-archive .kt-post-thumb {
    display: block;
    width: 100%;
    max-height: 320px;
    overflow: hidden;
    border-radius: 15px;
  }

  .kt-blog-archive .kt-post-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.25s ease;
  }

  .kt-blog-archive .kt-post-card:hover .kt-post-thumb img {
    transform: scale(1.02);
  }

  .kt-blog-archive .kt-post-body {
    padding: 18px 18px 16px;
  }

  .kt-blog-archive .kt-post-meta {
    font-size: 12px;
    color: var(--kt-muted);
    margin-bottom: 4px;
  }

  .kt-blog-archive .kt-post-meta span + span::before {
    content: "•";
    margin: 0 6px;
  }

  .kt-blog-archive .kt-post-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 6px;
  }

  .kt-blog-archive .kt-post-title a {
    color: var(--kt-dark);
    text-decoration: none;
  }

  .kt-blog-archive .kt-post-title a:hover {
    color: var(--kt-primary);
  }

  .kt-blog-archive .kt-post-excerpt {
    font-size: 14px;
    color: var(--kt-muted);
    margin: 0 0 10px;
  }

  .kt-blog-archive .kt-post-readmore {
    font-size: 14px;
    font-weight: 500;
    color: var(--kt-primary);
    text-decoration: none;
  }

  .kt-blog-archive .kt-post-readmore:hover {
    text-decoration: underline;
  }

  /* Pagination */
  .kt-blog-archive .kt-blog-pagination {
    margin-top: 28px;
    display: flex;
    justify-content: center;
  }

  .kt-blog-archive .kt-blog-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    min-width: 30px;
    height: 30px;
    padding: 0 10px;
    margin: 0 3px;
    font-size: 13px;
    border: 1px solid transparent;
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-blog-archive .kt-blog-pagination .page-numbers.current {
    background: var(--kt-primary);
    color: #ffffff;
    border-color: var(--kt-primary);
  }

  .kt-blog-archive .kt-blog-pagination .page-numbers:hover:not(.current) {
    border-color: var(--kt-primary-soft);
    background: var(--kt-primary-soft);
    color: var(--kt-dark);
  }

  /* Sidebar */
  .kt-blog-archive .kt-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .kt-blog-archive .kt-widget {
    background: var(--kt-card-bg);
    border-radius: 12px;
    border: 1px solid var(--kt-border);
    padding: 14px 14px 16px;
  }

  .kt-blog-archive .kt-widget-title {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 8px;
    color: var(--kt-dark);
  }

  /* Search */
  .kt-blog-archive .kt-search-form {
    position: relative;
  }

  .kt-blog-archive .kt-search-form input[type="search"] {
    width: 100%;
    border-radius: 999px;
    border: 1px solid var(--kt-border);
    padding: 8px 32px 8px 10px;
    font-size: 14px;
    outline: none;
    background-color: #f9fafb;
    transition: border-color 0.16s ease, box-shadow 0.16s ease;
  }

  .kt-blog-archive .kt-search-form input[type="search"]:focus {
    border-color: var(--kt-primary);
    box-shadow: 0 0 0 1px var(--kt-primary-soft);
    background-color: #ffffff;
  }

  .kt-blog-archive .kt-search-form button {
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
  .kt-blog-archive .kt-recent-list {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .kt-blog-archive .kt-recent-item {
    display: grid;
    grid-template-columns: 60px minmax(0, 1fr);
    gap: 8px;
    padding: 7px 0;
    border-bottom: 1px solid var(--kt-border);
  }

  .kt-blog-archive .kt-recent-item:last-child {
    border-bottom: none;
  }

  .kt-blog-archive .kt-recent-thumb {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    background: #e5e7eb;
  }

  .kt-blog-archive .kt-recent-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .kt-blog-archive .kt-recent-meta {
    font-size: 11px;
    color: var(--kt-muted);
    margin-bottom: 1px;
  }

  .kt-blog-archive .kt-recent-title {
    font-size: 13px;
    font-weight: 500;
    margin: 0;
  }

  .kt-blog-archive .kt-recent-title a {
    color: var(--kt-dark);
    text-decoration: none;
  }

  .kt-blog-archive .kt-recent-title a:hover {
    color: var(--kt-primary);
  }

  /* Categories */
  .kt-blog-archive .kt-cat-list {
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 14px;
  }

  .kt-blog-archive .kt-cat-list li {
    padding: 3px 0;
  }

  .kt-blog-archive .kt-cat-list a {
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-blog-archive .kt-cat-list a:hover {
    color: var(--kt-primary);
  }

  /* Tags */
  .kt-blog-archive .kt-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }

  .kt-blog-archive .kt-tag-chip {
    border-radius: 999px;
    background: #f9fafb;
    border: 1px solid var(--kt-border);
    padding: 3px 9px;
    font-size: 12px;
    color: var(--kt-muted);
    text-decoration: none;
  }

  .kt-blog-archive .kt-tag-chip:hover {
    border-color: var(--kt-primary);
    color: var(--kt-primary);
    background: var(--kt-primary-soft);
  }

  /* Sidebar banner – kept minimal */
  .kt-blog-archive .kt-banner {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--kt-border);
    background: #f9fafb;
  }

  .kt-blog-archive .kt-banner img {
    width: 100%;
    height: auto;
    display: block;
  }

  .kt-blog-archive .kt-banner-content {
    padding: 10px 12px 12px;
    font-size: 13px;
  }

  .kt-blog-archive .kt-banner-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--kt-muted);
    margin-bottom: 2px;
  }

  .kt-blog-archive .kt-banner-heading {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
    color: var(--kt-dark);
  }

  .kt-blog-archive .kt-banner-cta {
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

  .kt-blog-archive .kt-banner-cta span {
    margin-left: 4px;
  }

  .kt-blog-archive .kt-banner-cta:hover {
    background: var(--kt-primary);
    color: #ffffff;
  }

  /* ======================
     Responsive tweaks
  ======================= */

  @media (max-width: 980px) {
    .kt-blog-archive {
      padding: 28px 0 44px;
    }

    .kt-blog-archive .kt-post-card {
      border-radius: 10px;
    }

    .kt-blog-archive .kt-post-thumb {
      max-height: 260px;
      border-radius: 10px;
    }

    .kt-blog-archive .kt-recent-thumb {
      border-radius: 10px;
    }
  }

  @media (max-width: 640px) {
    .kt-blog-archive {
      padding: 24px 0 40px;
    }

    .kt-blog-archive .kt-blog-title {
      font-size: 24px;
    }

    .kt-blog-archive .kt-blog-subtitle {
      font-size: 14px;
    }

    .kt-blog-archive .kt-post-card {
      border-radius: 8px;
    }

    .kt-blog-archive .kt-post-thumb {
      max-height: 220px;
      border-radius: 8px;
    }

    .kt-blog-archive .kt-post-body {
      padding: 14px 14px 14px;
    }

    .kt-blog-archive .kt-post-title {
      font-size: 16px;
    }

    .kt-blog-archive .kt-post-excerpt {
      font-size: 13px;
    }

    .kt-blog-archive .kt-recent-item {
      grid-template-columns: 52px minmax(0, 1fr);
    }

    .kt-blog-archive .kt-recent-thumb {
      width: 52px;
      height: 52px;
      border-radius: 8px;
    }
  }
</style>

<div class="kt-blog-archive">
  <div class="kt-wrap">

    <header class="kt-blog-header">
      <div class="kt-blog-breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <span>Blog</span>
      </div>
      <h1 class="kt-blog-title">
        <?php echo esc_html( get_the_archive_title() ?: 'Blogs' ); ?>
      </h1>
      <p class="kt-blog-subtitle">
        Insights, tips and updates from the KachoTech team on heaters, smart cosmetics and home electronics.
      </p>
    </header>

    <div class="kt-blog-layout">

      <!-- MAIN POSTS COLUMN -->
      <main class="kt-post-list">
        <?php if ( have_posts() ) : ?>
          <?php
          while ( have_posts() ) :
            the_post();

            // Fallback image for posts without thumbnail
            $fallback_thumb = 'https://images.pexels.com/photos/373543/pexels-photo-373543.jpeg?auto=compress&cs=tinysrgb&w=1200';
            if ( has_post_thumbnail() ) {
              $thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            } else {
              $thumb_url = $fallback_thumb;
            }
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'kt-post-card' ); ?>>

              <a href="<?php the_permalink(); ?>" class="kt-post-thumb">
                <img src="<?php echo esc_url( $thumb_url ); ?>"
                     alt="<?php echo esc_attr( get_the_title() ); ?>">
              </a>

              <div class="kt-post-body">
                <div class="kt-post-meta">
                  <span><?php echo get_the_date(); ?></span>
                  <?php $comments_count = get_comments_number(); ?>
                  <span>
                    <?php echo esc_html( $comments_count ); ?>
                    <?php echo ( 1 === $comments_count ) ? 'Comment' : 'Comments'; ?>
                  </span>
                </div>

                <h2 class="kt-post-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>

                <p class="kt-post-excerpt">
                  <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 28, '…' ) ); ?>
                </p>

                <a href="<?php the_permalink(); ?>" class="kt-post-readmore">Read More</a>
              </div>
            </article>
          <?php endwhile; ?>

          <div class="kt-blog-pagination">
            <?php
            the_posts_pagination(
              array(
                'mid_size'  => 2,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
              )
            );
            ?>
          </div>

        <?php else : ?>
          <p><?php esc_html_e( 'No posts found.', 'kachotech-child' ); ?></p>
        <?php endif; ?>
      </main>

      <!-- SIDEBAR -->
      <aside class="kt-sidebar">

        <!-- Search -->
        <section class="kt-widget">
          <h3 class="kt-widget-title">Search</h3>
          <form role="search" method="get" class="kt-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="kt-blog-search">Search for:</label>
            <input type="search" id="kt-blog-search" name="s" placeholder="Search keyword">
            <button type="submit"><span class="kt-search-button-icon dashicons dashicons-search blog-search-icon" ></span></button>
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

            // Example placeholder images for sidebar recent posts
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

        <!-- Tags -->
        <section class="kt-widget">
          <h3 class="kt-widget-title">Tags</h3>
          <div class="kt-tags">
            <?php
            $tags = get_tags();
            if ( $tags ) :
              foreach ( $tags as $tag ) :
                ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                   class="kt-tag-chip">
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

        <!-- Promo banner (example image and text) -->
        <section class="kt-banner">
          <!-- Example promo image: warm lifestyle / ecommerce style -->
          <img src="https://images.pexels.com/photos/2950003/pexels-photo-2950003.jpeg?auto=compress&cs=tinysrgb&w=800"
               alt="KachoTech winter sale example banner">
          <div class="kt-banner-content">
            <div class="kt-banner-label">Winter Offer</div>
            <div class="kt-banner-heading">Up to 50% OFF on<br>Selected Heaters</div>
            <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="kt-banner-cta">
              Shop Now <span>➜</span>
            </a>
          </div>
        </section>

      </aside>

    </div><!-- .kt-blog-layout -->
  </div><!-- .kt-wrap -->
</div><!-- .kt-blog-archive -->

<?php
get_footer();
