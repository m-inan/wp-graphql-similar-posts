<?php
/**
 * Plugin Name: WPGraphQL Similar Posts
 * Plugin URI: https://github.com/m-inan/wp-graphql-similar-posts
 * Description: Adds post type for similar posts
 * Author: Minan
 * Version: 0.1.0
 *
 */

// To make this plugin work properly for both Composer users and non-composer
// users we must detect whether the project is using a global autolaoder. We
// can do that by checking whether our autoloadable classes will autoload with
// class_exists(). If not it means there's no global autoloader in place and
// the user is not using composer. In that case we can safely require the
// bundled autoloader code.
if (!\class_exists('\WPGraphQL\Extensions\SimilarPosts')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load the actual plugin code
\WPGraphQL\Extensions\SimilarPosts\Loader::init();
