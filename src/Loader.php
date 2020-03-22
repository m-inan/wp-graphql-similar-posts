<?php

namespace WPGraphQL\Extensions\SimilarPosts;

use WPGraphQL\Connection\PostObjects;
use WPGraphQL\Data\Connection\PostObjectConnectionResolver;
use WPGraphQL\Data\DataSource;

define('GRAPHQL_DEBUG', true);

/**
 * Class Laoder
 *
 * This class allows you to see the similar posts in the 'post' type.
 *
 * @package SimilarPosts
 * @since   0.1.0
 */
class Loader
{
    public static function init()
    {
        define('WP_GRAPHQL_NEXT_PREVIOUS_POST', 'initialized');
        (new Loader())->bind_hooks();
    }

    public function bind_hooks()
    {
        add_action(
            'graphql_register_types',
            [$this, 'npp_action_register_types'],
            99
        );
    }

    public function npp_action_register_types()
    {

        $config = [
            'fromType' => 'Post',
            'toType' => 'Post',
            'queryClass' => 'WP_Query',
            'fromFieldName' => 'similar',
            'connectionArgs' => PostObjects::get_connection_args(),
            'resolve' => function ($id, $args, $context, $info) {

                // get all tags in current post
                $tags = wp_get_post_tags($id->ID);

                // first parameter null because the parent represents `$id'
                $resolver = new PostObjectConnectionResolver(null, $args, $context, $info, 'post');

                $resolver->setQueryArg('post__not_in', array($id->ID));
                $resolver->setQueryArg('tag__in', array_map(function ($tag) {return $tag->term_id;}, $tags));

                $connection = $resolver->get_connection();

                return $connection;
            },
            'resolveNode' => function ($id, $args, $context, $info) {
                return DataSource::resolve_post_object($id, $context);
            },
        ];
        register_graphql_connection($config);

    }
}
