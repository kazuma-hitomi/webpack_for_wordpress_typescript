<?php
// 不要なもの削除
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head','rest_output_link_wp_head');
remove_action('wp_head','feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
add_filter('show_admin_bar', '__return_false');

// dns-prefetchの削除
add_filter('wp_resource_hints', function($hints, $relation_type) {
  if ($relation_type === 'dns-prefetch') return array_diff(wp_dependencies_unique_hosts(), $hints);
  return $hints;
}, 10, 2);

// 画像のtitle class width heightを削除
function remove_img_attribute($html){
  $html = preg_replace('/(width|height)="\d*"\s/', '', $html);
  $html = preg_replace('/class=[\'"]([^\'"]+)[\'"]/i', '', $html);
  $html = preg_replace('/title=[\'"]([^\'"]+)[\'"]/i', '', $html);
  return $html;
}
add_filter('image_send_to_editor', 'remove_img_attribute', 10);
add_filter('post_thumbnail_html', 'remove_img_attribute', 10);
add_filter('get_image_tag', 'remove_img_attribute', 10);

// アイキャッチ画像の有効化
add_theme_support('post-thumbnails');

/**
 * キャッシュ対策
 * ファイルのバージョン更新
 * @param $file
 * @return string
 */
function add_latest_version($file) {
  return date_i18n('YmdHi', filemtime(get_template_directory() . $file));
}

// js & cssの読み込み
add_action('wp_enqueue_scripts', function() {
  if (!is_admin()) {
    // 管理画面以外で読み込み
    wp_enqueue_style('style', get_stylesheet_uri(), array(), add_latest_version('/style.css'), 'all');
    wp_enqueue_style('main_style', get_template_directory_uri() . '/assets/css/main.css', array(), add_latest_version('/assets/css/main.css'), 'all');
    wp_deregister_script('jquery');
    wp_enqueue_script('main_script', get_template_directory_uri() . '/assets/js/main.ts', array(), add_latest_version('/assets/js/main.ts'), true);
  }
});

// タイトルタグの設定
function switch_title() {
  $site_name = get_option('blogname');
  $site_desc = 'title description';
  $pipe = ' | ';
  if (is_front_page()):
    $title = $site_name . $pipe . $site_desc;
  elseif (is_single()):
    $title = $site_name . $pipe . strip_tags(get_the_title());
  elseif (is_category()):
    $category = get_queried_object();
    $category_name = $category -> name;
    $title = $site_name . $pipe . $category_name;
  elseif (is_tag()):
    $tag = get_queried_object();
    $tag_name = $tag -> name;
    $title = $site_name . $pipe . $tag_name;
  elseif (is_page()):
    $title = $site_name . $pipe . get_the_title();
  elseif (is_search()):
    $title = $site_name . $pipe . '検索内容';
  elseif (is_404()):
    $title = $site_name . $pipe . '404';
  else:
    $title = $site_name . $pipe . $site_desc;
  endif;
  return $title;
}

// descriptionの設定
function switch_desc() {
  if (is_single()):
    if (!empty(get_the_excerpt())) {
      $site_desc = get_the_excerpt();
    } else {
      $site_desc = get_option('blogdescription');
    }
  else:
    $site_desc = get_option('blogdescription');
  endif;
  return $site_desc;
}

// OGPの設定
function add_ogp() {
  global $post;
  $title = 'title';
  $type = 'website';
  $url = home_url('/');
  $img = content_url() . '/themes/???????????????????????????????/assets/images/ogp.jpg';
  $desc = get_bloginfo('description');
  $twitter_card = 'summary_large_image';
  $twitter_site = '';
  
  if(is_single()) {
    $type = 'article';
    setup_postdata($post);
    $title = $post->post_title;
    $url = get_permalink();
    $img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[0];
    $desc = mb_substr(get_the_excerpt(), 0, 100);
    wp_reset_postdata();
  } ?>
  <meta property="og:title" content="<?php echo esc_attr($title); ?>">
  <meta property="og:type" content="<?php echo $type; ?>"/>
  <meta property="og:url" content="<?php echo esc_url($url); ?>">
  <meta property="og:image" content="<?php echo esc_url($img); ?>">
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="600" />
  <meta property="og:description" content="<?php echo esc_attr($desc); ?>">
  <meta property="og:site_name" content="<?php echo $title; ?>">
  <meta property="og:locale" content="ja_JP">
  <meta property="fb:app_id" content="">
  <meta property="fb:admins" content="">
  <meta name="twitter:card" content="<?php echo $twitter_card; ?>">
  <meta name="twitter:site" content="<?php echo $twitter_site; ?>">
  <?php
}
add_action('wp_head', 'add_ogp');

// ログイン画面のカスタマイズ
add_action('login_head', function() {
  echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/login.css" />';
});
