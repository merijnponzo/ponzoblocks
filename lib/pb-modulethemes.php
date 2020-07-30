<?php

/**
 * Adds AcfGroup ModuleThemes to the design dropdown
 *
 * @return string The PHP code
 */
function getThemeModules($module)
{
    $options = get_field($module, 'option');
    $selectvalues = array();
    foreach ((array) $options as $option) {
        array_push($selectvalues, $option['name']);
    }
    return $selectvalues;
}
/**
 * Adds AcfGroup ModuleThemes to the design dropdown
 *
 * @return string The PHP code
 */
function setModulesThemes()
{

    if (is_user_logged_in()) {
        $modules = array(
            'priceblocks',
            'textvisual',
            'tiles',
            'contentcolumns',
            'banner',
            'quote',
            'longread',
            'specialfeatures',
            'testimonials',
            'shortcode',
            'gallery',
            'visualgallery',
        );
        foreach ((array) $modules as $module) {
            add_filter('acf/load_field/name=' . $module . 'theme', function ($field) {
                $module = str_replace('theme', '', $field['name']);
                $field['choices'] = getThemeModules($module);
                return $field;
            });
        }
        ;
    }
}
add_action('acf/init', 'setModulesThemes');
