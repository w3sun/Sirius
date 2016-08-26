<?php

define( 'SIRIUS_VERSION' , '1.0' );

/**
 * 主题更新
 * @version 1.0
 * @package Vtrois
 */
require_once( get_template_directory() . '/inc/version.php' );
$sirius_update_checker = new ThemeUpdateChecker(
	'Sirius', 
	'http://soft.vtrois.com/wordpress/theme/sirius/upgrade.json'
);

/**
 * 替换Gravatar服务器
 * @version 1.0
 * @package Vtrois
 */
function sirius_get_avatar( $avatar ) {
$avatar = preg_replace( "/http:\/\/(www|\d).gravatar.com/", "http://cn.gravatar.com",$avatar );
return $avatar;
}
add_filter( 'get_avatar', 'sirius_get_avatar' );

/**
 * 加载脚本
 * @version 1.0
 * @package Vtrois
 */  
function sirius_theme_scripts() {  
	$dir = get_template_directory_uri(); 
    if ( !is_admin() ) {  
        wp_enqueue_style( 'awesome-style', $dir . '/css/font-awesome.css', array(), '4.6.3');
        wp_enqueue_style( 'sirius-style', get_stylesheet_uri(), array(), SIRIUS_VERSION); 
        wp_enqueue_script( 'jquerys', $dir . '/js/jquery.min.js' , array(), '2.1.4');
        wp_enqueue_script( 'scrolly', $dir . '/js/jquery.scrolly.min.js', array(), '0.2');
        wp_enqueue_script( 'skel', $dir . '/js/skel.min.js', array(), '3.0.1');
        wp_enqueue_script( 'util', $dir . '/js/util.min.js', array(),  SIRIUS_VERSION);
        wp_enqueue_script( 'sirius', $dir . '/js/sirius.js', array(),  SIRIUS_VERSION);
    }  
}  
add_action('wp_enqueue_scripts', 'sirius_theme_scripts');

/**
 * 移除头部代码
 * @version 1.0
 * @package Vtrois
 */
remove_action( 'wp_head', 'feed_links', 2 );   
remove_action( 'wp_head', 'feed_links_extra', 3 );   
remove_action( 'wp_head', 'rsd_link' );   
remove_action( 'wp_head', 'wlwmanifest_link' );   
remove_action( 'wp_head', 'index_rel_link' );   
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );   
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );   
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );   
remove_action( 'wp_head', 'locale_stylesheet' );   
remove_action( 'publish_future_post', 'check_and_publish_future_post', 10, 1 );   
remove_action( 'wp_head', 'noindex', 1 );   
remove_action( 'wp_head', 'wp_print_head_scripts', 9 );   
remove_action( 'wp_head', 'wp_generator' );   
remove_action( 'wp_head', 'rel_canonical' );   
remove_action( 'wp_footer', 'wp_print_footer_scripts' );   
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );   
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 ); 

function disable_emojis() {
    global $wp_version;
    if ($wp_version >= 4.2) {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    }
}
add_action( 'init', 'disable_emojis' );

function disable_open_sans( $translations, $text, $context, $domain )
{
    if ( 'Open Sans font: on or off' == $context && 'on' == $text ) {
        $translations = 'off';
    }
    return $translations;
}
add_filter('gettext_with_context', 'disable_open_sans', 888, 4 );

/**
 * 禁止字符转义
 * @version 1.0
 * @package Vtrois
 */
$qmr_work_tags = array('the_title','the_content','the_excerpt','single_post_title','comment_author','comment_text','link_description','bloginfo','wp_title', 'term_description','category_description','widget_title','widget_text');
foreach ( $qmr_work_tags as $qmr_work_tag ) {
  remove_filter ($qmr_work_tag, 'wptexturize');
}

/**
 * 移除小工具
 * @version 1.0
 * @package Vtrois
 */
function remove_default_widget() {
        unregister_widget('WP_Widget_Recent_Posts');
        unregister_widget('WP_Widget_Recent_Comments');
        unregister_widget('WP_Widget_Meta');
        unregister_widget('WP_Widget_Tag_Cloud');
        unregister_widget('WP_Widget_Text');
        unregister_widget('WP_Widget_Archives');
        unregister_widget('WP_Widget_RSS');
        unregister_widget('WP_Nav_Menu_Widget');
        unregister_widget('WP_Widget_Pages');
        unregister_widget('WP_Widget_Calendar');
        unregister_widget('WP_Widget_Categories');
        unregister_widget('WP_Widget_Search');
}
add_action( 'widgets_init', 'remove_default_widget' );

/**
 * 移除自动保存
 * @version 1.0
 * @package Vtrois
 */
wp_deregister_script('autosave');

/**
 * 移除修订版本
 * @version 1.0
 * @package Vtrois
 */
remove_action('post_updated','wp_save_post_revision' );

/**
 * 移除wordpress工具条
 * @version 1.0
 * @package Vtrois
 */
add_filter('show_admin_bar', '__return_false');

/**
 * 友情链接功能
 * @version 1.0
 * @package Vtrois
 */  
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * 关键词设置
 * @version 1.0
 * @package Vtrois
 */
function sirius_keywords(){
        if( is_home() || is_front_page() ){ echo sirius_option('site_keywords'); }
        elseif( is_category() ){ single_cat_title(); }
        elseif( is_single() ){
            echo trim(wp_title('',FALSE)).',';
            if ( has_tag() ) {foreach((get_the_tags()) as $tag ) { echo $tag->name.','; } }
            foreach((get_the_category()) as $category) { echo $category->cat_name.','; } 
        }
        elseif( is_search() ){ the_search_query(); }
        else{ echo trim(wp_title('',FALSE)); }
}

/**
 * 描述设置
 * @version 1.0
 * @package Vtrois
 */ 
function sirius_description(){
        if( is_home() || is_front_page() ){ echo trim(sirius_option('site_description')); }
        elseif( is_category() ){ $description = strip_tags(category_description());echo trim($description);}
        elseif( is_single() ){ 
		if(get_the_excerpt()){
			echo get_the_excerpt();
		}else{
			global $post;
                        $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $post->post_content ) ) ) );
                        echo mb_substr( $description, 0, 220, 'utf-8' );
		}
	}
        elseif( is_search() ){ echo '“';the_search_query();echo '”为您找到结果 ';global $wp_query;echo $wp_query->found_posts;echo ' 个'; }
        elseif( is_tag() ){  $description = strip_tags(tag_description());echo trim($description); }
        else{ $description = strip_tags(term_description());echo trim($description); }
    }

/**
 * 标题设置
 * @version 1.0
 * @package Vtrois
 */
function sirius_wp_title( $title, $sep ) {
    global $paged, $page;
    if ( is_feed() )
        return $title;
    $title .= get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";
    if ( $paged >= 2 || $page >= 2 )
       $title = "$title $sep " . sprintf( __( 'Page %s', 'sirius' ), max( $paged, $page ) );
    return $title;
}
add_filter( 'wp_title', 'sirius_wp_title', 10, 2 );

/**
 * 后台控制模块
 * @version 1.0
 * @package Vtrois
 */
if (!function_exists('optionsframework_init')) {
	define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/theme-options/');
	require_once dirname(__FILE__) . '/inc/theme-options/options-framework.php';
	$optionsfile = locate_template('options.php');
	load_template($optionsfile);
}
function sirius_options_menu_filter( $menu ) {
  $menu['mode'] = 'menu';
  $menu['page_title'] = '主题设置';
  $menu['menu_title'] = '主题设置';
  $menu['menu_slug'] = 'sirius';
  return $menu;
}
add_filter( 'optionsframework_menu', 'sirius_options_menu_filter' );

/**
 * 菜单导航注册
 * @version 1.0
 * @package Vtrois
 */
function sirius_register_nav_menu() {
		register_nav_menus(array('header_menu' => '菜单导航'));
	}
add_action('after_setup_theme', 'sirius_register_nav_menu');

/**
 * 移除菜单的多余CSS选择器
 * @version 1.0
 * @package Vtrois
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array_intersect($var, array('current-menu-item','current-post-ancestor','current-menu-ancestor','current-menu-parent')) : '';
}

/**
 * 文章缩略图
 * @version 1.0
 * @package Vtrois
 */
if ( function_exists( 'add_image_size' ) ){  
    add_image_size( 'sirius-thumb', 688);
}  
function sirius_blog_thumbnail() {    
    global $post;  
    $img_id = get_post_thumbnail_id();
    $img_url = wp_get_attachment_image_src($img_id,'sirius-thumb');
    $img_url = $img_url[0];
    if ( has_post_thumbnail() ) {
        echo '<a href="'.get_permalink().'" class="image"><img class="sirius-thumb" src="'.$img_url.'" /></a>';  
    } else {
        $content = $post->post_content;  
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
        $n = count($strResult[1]);  
        if($n > 0){ 
            echo '<a href="'.get_permalink().'" class="image"><img class="sirius-thumb" src="'.$strResult[1][0].'" /></a>';  
        }else {
            echo '<a href="'.get_permalink().'" class="image"><img class="sirius-thumb" src="'.get_bloginfo('template_url').'/images/default.jpg" /></a>';  
        }  
    }  
}  

add_theme_support( "post-thumbnails" );

/**
 * 摘要长度及后缀
 * @version 1.0
 * @package Vtrois
 */
function sirius_excerpt_length($length) {
    return 170;
}
add_filter('excerpt_length', 'sirius_excerpt_length');
function sirius_excerpt_more($more) {
    return '……';
}
add_filter('excerpt_more', 'sirius_excerpt_more');

/**
 * 文章阅读量统计
 * @version 1.0
 * @package Vtrois
 */
function sirius_set_post_views()
{
	if (is_singular())
	{
	  global $post;
	  $post_ID = $post->ID;
	  if($post_ID)
	  {
		  $post_views = (int)get_post_meta($post_ID, 'views', true);
		  if(!update_post_meta($post_ID, 'views', ($post_views+1)))
		  {
			add_post_meta($post_ID, 'views', 1, true);
		  }
	  }
	}
}
add_action('wp_head', 'sirius_set_post_views');
function sirius_get_post_views($before = '', $after = '', $echo = 1)
{
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  if ($echo) echo $before, number_format($views), $after;
  else return $views;
}

/**
 * 文章点赞功能
 * @version 1.0
 * @package Vtrois
 */
function sirius_love(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'love'){
        $sirius_raters = get_post_meta($id,'sirius_love',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('sirius_love_'.$id,$id,$expire,'/',$domain,false);
        if (!$sirius_raters || !is_numeric($sirius_raters)) {
            update_post_meta($id, 'sirius_love', 1);
        } 
        else {
            update_post_meta($id, 'sirius_love', ($sirius_raters + 1));
        }
        echo get_post_meta($id,'sirius_love',true);
    } 
    die;
}
add_action('wp_ajax_nopriv_sirius_love', 'sirius_love');
add_action('wp_ajax_sirius_love', 'sirius_love');

/**
 * 评论表情
 * @version 1.0
 * @package Vtrois
 */
add_filter('smilies_src','custom_smilies_src',1,10);
function custom_smilies_src ($img_src, $img, $siteurl){
    return get_bloginfo('template_directory').'/images/smilies/'.$img;
}
function disable_emojis_tinymce( $plugins ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
}
function smilies_reset() {
    global $wpsmiliestrans, $wp_smiliessearch, $wp_version;
    if ( !get_option( 'use_smilies' ) || $wp_version < 4.2)
        return;
    $wpsmiliestrans = array(
    ':mrgreen:' => 'icon_mrgreen.gif',
    ':exclaim:' => 'icon_exclaim.gif',
    ':neutral:' => 'icon_neutral.gif',
    ':twisted:' => 'icon_twisted.gif',
      ':arrow:' => 'icon_arrow.gif',
        ':eek:' => 'icon_eek.gif',
      ':smile:' => 'icon_smile.gif',
   ':confused:' => 'icon_confused.gif',
       ':cool:' => 'icon_cool.gif',
       ':evil:' => 'icon_evil.gif',
    ':biggrin:' => 'icon_biggrin.gif',
       ':idea:' => 'icon_idea.gif',
    ':redface:' => 'icon_redface.gif',
       ':razz:' => 'icon_razz.gif',
   ':rolleyes:' => 'icon_rolleyes.gif',
       ':wink:' => 'icon_wink.gif',
        ':cry:' => 'icon_cry.gif',
  ':surprised:' => 'icon_surprised.gif',
        ':lol:' => 'icon_lol.gif',
        ':mad:' => 'icon_mad.gif',
        ':sad:' => 'icon_sad.gif',
    );
}
smilies_reset();

/**
 * 分页
 * @version 1.0
 * @package Vtrois
 */
function sirius_pages($range = 5){
    global $paged, $wp_query;
    if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
    if($max_page > 1){if(!$paged){$paged = 1;}
	echo "<ul class='pagination'>";
        if($paged != 1){
            echo "<li><a href='" . get_pagenum_link(1) . "' class='extend' title='首页'>&laquo;</a></li>";
        }
        if($paged>1) echo '<li><a href="' . get_pagenum_link($paged-1) .'" class="prev" title="上一页">&lt;</a></li>';
        if($max_page > $range){
            if($paged < $range){
                for($i = 1; $i <= ($range + 1); $i++){
                    echo "<li"; if($i==$paged)echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= ($max_page - ceil(($range/2)))){
                for($i = $max_page - $range; $i <= $max_page; $i++){
                    echo "<li";
                    if($i==$paged)
                        echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
                for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
                    echo "<li";
                    if($i==$paged)echo " class='active'";
                    echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
        }
        else{
            for($i = 1; $i <= $max_page; $i++){
                echo "<li";
                if($i==$paged)echo " class='active'";
                echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
            }
        }
        if($paged<$max_page) echo '<li><a href="' . get_pagenum_link($paged+1) .'" class="next" title="下一页">&gt;</a></li>';
        if($paged != $max_page){
            echo "<li><a href='" . get_pagenum_link($max_page) . "' class='extend' title='尾页'>&raquo;</a></li>";
        }
        echo "</ul>";
	}
}

/**
 * 后台左侧页脚文字
 * @version 1.1
 * @package Vtrois
 */
function sirius_admin_footer_text($text) {
	   $text = '<span id="footer-thankyou">感谢使用 <a href=http://cn.wordpress.org/ target="_blank">WordPress</a>进行创作，并使用 <a href="http://www.vtrois.com/projects/theme-sirius.html" target="_blank">Sirius</a>主题样式。</span>';
	return $text;
}

add_filter('admin_footer_text', 'sirius_admin_footer_text');


