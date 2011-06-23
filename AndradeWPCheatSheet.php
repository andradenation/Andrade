Install WP from SSH
wget http://wordpress.org/latest.tar.gz
tar xvzf latest.tar.gz


Editing the Options from Dash
/wp-admin/options.php


Common
<?php bloginfo('url'); ?>
<?php bloginfo('template_directory'); ?>
<?php bloginfo('stylesheet_url'); ?>
<?php bloginfo('name'); ?>
<?php bloginfo('description'); ?>


Standard Template File Includes
<?php wp_head(); ?>
<?php wp_footer(); ?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>


Manual Template Definition
<?php
/*
Template Name: Something
*/
?>


Custom Template File Include
<?php include(TEMPLATEPATH.'/sidebar-left.php'); ?>


Function Include
<?php include_once(TEMPLATEPATH . '/include/slideshow.php'); ?>


Single Use Query
<?php $recent = new WP_Query('cat=1&posts_per_page=3'); while($recent->have_posts()) : $recent->the_post(); ?>
<?php endwhile; ?>


Single Use Query - If Custom Post Type
<?php $recent = new WP_Query('post_type=customtype&cat=5&orderby=menu_order&order=ASC&posts_per_page=-1'); while($recent->have_posts()) : $recent->the_post(); ?>
<?php endwhile; ?>


Reset Original Query - When bad behaviors arise after multiple custom single use loops
<?php wp_reset_query(); ?>


Get a post meta value
<?php echo get_post_meta($post->ID, 'meta-value', true); ?>
or
<?php meta('meta-value'); ?>
or outside the loop
<?php
global $wp_query;
$postid = $wp_query->post->ID;
echo get_post_meta($postid, 'meta-value', true);
wp_reset_query();
?>


If a post meta value has been set
<?php if( get_post_meta($post->ID, 'meta-value', true) ): ?>
<?php else: ?>
<?php endif; ?>


Pull in a theme option panel setting
<?php $id = get_option('shortname_id'); echo stripslashes($id); ?>
or
<?php echo get_option('option'); ?>


Dynamic Sidebar Template Code
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Main Sidebar') ) : ?>
<?php endif; ?>


If post has a thumbnail
<?php if ( has_post_thumbnail() ): ?>
	<?php the_post_thumbnail('custom-size-if-available'); ?>
<?php else: ?>
	<img class="postthumb" src="<?php bloginfo('template_url'); ?>/images/blankpostthumb.jpg" alt="<?php the_title(); ?>" />
<?php endif; ?>	


Get Thumbnail URL
<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'custom-size-if-available' ); $url = $thumb['0']; ?>
<?php echo $url ?>


Sidebar - If the page has child pages, show the subnav (including on child pages themselves). Won't show if no child pages.
<?php
if($post->post_parent)
	$children = wp_list_pages('sort_column=menu_order&child_of='.$post->post_parent.'&title_li=&echo=0');
else
	$children = wp_list_pages('sort_column=menu_order&child_of='.$post->ID.'&title_li=&echo=0');
if ($children) { ?>
<?php if($post->post_parent) : ?>
	<h4><?php $parent_title = get_the_title($post->post_parent); echo $parent_title; ?></h4>
<?php else : ?>
	<h4><?php $parent_title = get_the_title($post); echo $parent_title; ?></h4>
<?php endif; ?>
<ul>
	<?php echo $children; ?>
</ul>
<?php } ?>


Make Gravity Forms post to Custom Post Type
<?php
add_filter("gform_post_data", "update_post_type", 10, 2);
function update_post_type($post_data, $form){

    if($form["id"] == 'post ID #'){
        $post_data["post_type"] = "custom-post-type";
    }
    return $post_data;
}
?>


Display the current custom taxonomy term name in template
<?php $tax = $wp_query->get_queried_object(); ?>
<?php echo $tax->name; ?>


Display a post's Custom Taxonomy terms without links
<?php $terms_as_text = strip_tags( get_the_term_list( $wp_query->post->ID, 'customtaxonomy', '', ', ', '' ) ); ?>
<?php echo $terms_as_text; ?>


Show a category ID drop down in Theme Options
<?php
$cats = get_categories('hide_empty=0');
$option = array();
foreach($cats as $cat) {
    $option[$cat->name] = $cat->term_id;
}
$options->addDropdown(array(
    'id' => 'cats',
    'options' => $option,
));
?>