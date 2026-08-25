<?php

/*
 * ============================================================
 * REEMPLAZO SEGURO DE URLs DE WORDPRESS
 * ============================================================
 *
 * Cambiar solamente estas dos variables antes de ejecutar:
 *
 * $old = URL actual
 * $new = URL nueva
 *
 * El script intenta respetar datos serializados de WordPress.
 *
 * Incluye:
 * - wp_options
 * - wp_posts
 * - wp_postmeta (Elementor)
 * - wp_termmeta
 * - wp_usermeta
 * - wp_commentmeta
 * - Revolution Slider
 *
 * ============================================================
 */

require '/var/www/html/wp-load.php';

global $wpdb;


/*
 * ============================================================
 * CONFIGURACIÓN
 * ============================================================
 */

$old = 'http://localhost:8082';
$new = 'https://e26-atupedro-vy.pruebamiweb.com';


/*
 * ============================================================
 * FUNCIONES
 * ============================================================
 */

function replace_recursive($data, $old, $new) {

    if (is_string($data)) {
        return str_replace($old, $new, $data);
    }

    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = replace_recursive($value, $old, $new);
        }

        return $data;
    }

    if (is_object($data)) {
        foreach ($data as $key => $value) {
            $data->$key = replace_recursive($value, $old, $new);
        }

        return $data;
    }

    return $data;
}


function replace_serialized_value($value, $old, $new) {

    $unserialized = maybe_unserialize($value);

    /*
     * Si es un array u objeto, reemplazamos dentro
     * de toda la estructura y volvemos a serializar.
     */
    if (
        $unserialized !== $value ||
        is_array($unserialized) ||
        is_object($unserialized)
    ) {

        $unserialized = replace_recursive(
            $unserialized,
            $old,
            $new
        );

        return maybe_serialize($unserialized);
    }

    /*
     * Si es texto normal.
     */
    return str_replace($old, $new, $value);
}


function replace_in_table(
    $table,
    $id_column,
    $text_columns,
    $old,
    $new
) {

    global $wpdb;

    echo "\nProcesando: {$table}\n";

    foreach ($text_columns as $column) {

        $rows = $wpdb->get_results(
            "SELECT {$id_column}, {$column}
             FROM {$table}
             WHERE {$column} LIKE '%" .
             esc_sql($old) .
             "%'"
        );

        if (!$rows) {
            continue;
        }

        echo "  {$column}: " . count($rows) . " registros\n";

        foreach ($rows as $row) {

            $original = $row->$column;

            $replacement = replace_serialized_value(
                $original,
                $old,
                $new
            );

            if ($replacement !== $original) {

                $wpdb->update(
                    $table,
                    [
                        $column => $replacement
                    ],
                    [
                        $id_column => $row->$id_column
                    ]
                );
            }
        }
    }
}


/*
 * ============================================================
 * WORDPRESS
 * ============================================================
 */


/*
 * 1. OPTIONS
 */
replace_in_table(
    $wpdb->options,
    'option_id',
    [
        'option_value'
    ],
    $old,
    $new
);


/*
 * 2. POSTS
 */
replace_in_table(
    $wpdb->posts,
    'ID',
    [
        'post_content',
        'post_excerpt',
        'guid',
        'pinged',
        'to_ping'
    ],
    $old,
    $new
);


/*
 * 3. POSTMETA
 *
 * MUY IMPORTANTE PARA ELEMENTOR.
 */
replace_in_table(
    $wpdb->postmeta,
    'meta_id',
    [
        'meta_value'
    ],
    $old,
    $new
);


/*
 * 4. TERMMETA
 */
if ($wpdb->termmeta) {

    replace_in_table(
        $wpdb->termmeta,
        'meta_id',
        [
            'meta_value'
        ],
        $old,
        $new
    );
}


/*
 * 5. USERMETA
 */
if ($wpdb->usermeta) {

    replace_in_table(
        $wpdb->usermeta,
        'umeta_id',
        [
            'meta_value'
        ],
        $old,
        $new
    );
}


/*
 * 6. COMMENTMETA
 */
if ($wpdb->commentmeta) {

    replace_in_table(
        $wpdb->commentmeta,
        'meta_id',
        [
            'meta_value'
        ],
        $old,
        $new
    );
}


/*
 * ============================================================
 * REVOLUTION SLIDER
 * ============================================================
 *
 * Revolution Slider utiliza sus propias tablas y puede guardar
 * URLs dentro de datos estructurados/serializados.
 *
 * Por eso las procesamos explícitamente.
 */


/*
 * SLIDERS
 */
$revslider_sliders = $wpdb->prefix . 'revslider_sliders7';

if (
    $wpdb->get_var(
        "SHOW TABLES LIKE '{$revslider_sliders}'"
    ) === $revslider_sliders
) {

    replace_in_table(
        $revslider_sliders,
        'id',
        [
            'params',
            'settings',
            'type'
        ],
        $old,
        $new
    );
}


/*
 * SLIDES
 */
$revslider_slides = $wpdb->prefix . 'revslider_slides7';

if (
    $wpdb->get_var(
        "SHOW TABLES LIKE '{$revslider_slides}'"
    ) === $revslider_slides
) {

    replace_in_table(
        $revslider_slides,
        'id',
        [
            'layers',
            'params',
            'static'
        ],
        $old,
        $new
    );
}


/*
 * PREVIEW SLIDERS
 */
$revslider_preview_sliders =
    $wpdb->prefix . 'revslider_preview_sliders7';

if (
    $wpdb->get_var(
        "SHOW TABLES LIKE '{$revslider_preview_sliders}'"
    ) === $revslider_preview_sliders
) {

    replace_in_table(
        $revslider_preview_sliders,
        'id',
        [
            'params',
            'settings',
            'type'
        ],
        $old,
        $new
    );
}


/*
 * PREVIEW SLIDES
 */
$revslider_preview_slides =
    $wpdb->prefix . 'revslider_preview_slides7';

if (
    $wpdb->get_var(
        "SHOW TABLES LIKE '{$revslider_preview_slides}'"
    ) === $revslider_preview_slides
) {

    replace_in_table(
        $revslider_preview_slides,
        'id',
        [
            'layers',
            'params',
            'static'
        ],
        $old,
        $new
    );
}


echo "\n";
echo "============================================================\n";
echo "REEMPLAZO COMPLETADO\n";
echo "============================================================\n";
echo "Antigua: {$old}\n";
echo "Nueva:   {$new}\n";
echo "============================================================\n";
PHP