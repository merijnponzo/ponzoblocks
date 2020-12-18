<?php
namespace Ponzoblocks\core;

class Blocks{

    static function getBlocks(){
        return  array(
            array(
                'name' => 'textvisual',
                'slug' => 'textvisual',
                'title' => 'Text & Visual',
                'description' => 'Create text & visuals',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5c49bd6e96248'
            ),
            array(
                'name' => 'contentcolumns',
                'slug' => 'contentcolumns',
                'title' => 'Content columns',
                'description' => 'Create contentcolumns',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5c5935e1bc225'
            ),
            array(
                'name' => 'banner',
                'slug' => 'banner',
                'title' => 'Visual Banner',
                'description' => 'Create a banner',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5f291c0aaefee'
            ),
            array(
                'name' => 'categories',
                'slug' => 'categories',
                'title' => 'Categories',
                'description' => 'Create categories',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5f3119ce45b1c'
            ),
            array(
                'name' => 'textblock',
                'slug' => 'textblock',
                'title' => 'Text block',
                'description' => 'Create textblocks',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5f46350d382a9'
            ),
            array(
                'name' => 'articleblock',
                'slug' => 'articleblock',
                'title' => 'Article block',
                'description' => 'Create articleblocks',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5f51f418218f6'
            ),
            array(
                'name' => 'gallery',
                'slug' => 'gallery',
                'title' => 'Gallery block',
                'description' => 'Create gallery',
                'icon' => 'columns',
                'script' => false,
                'group' => 'group_5c89120c750b8'
            ),
            array(
                'name' => 'testimonials',
                'slug' => 'testimonials',
                'title' => 'Testimonials',
                'description' => 'Create testimonials',
                'icon' => 'columns',
                'script' => false,
                'group'=> 'group_5e2ad86b64c9f'
            ),
        );
    }
}