<?php

namespace cybot\cookiebot\lib;

// Original regex-based implementation from helper.php
function cookiebot_addons_manipulate_script_old( $buffer, $keywords ) {
    $normalized_buffer = preg_replace( '/(<script(.*?)\/>)/is', '<script$2></script>', $buffer );
    if ( $normalized_buffer !== null ) {
        $buffer = $normalized_buffer;
    }
    $pattern = '/(<script[^>]*+>)(.*?)(<\/script>)/is';
    $updated_scripts = preg_replace_callback(
        $pattern,
        function ( $matches ) use ( $keywords ) {
            $script           = $matches[0];
            $script_tag_open  = $matches[1];
            $script_tag_inner = $matches[2];
            $script_tag_close = $matches[3];

            foreach ( $keywords as $needle => $cookie_type ) {
                if ( strpos( $script, $needle ) !== false ) {
                    $script_tag_open = str_replace( '\'', '"', $script_tag_open );
                    $script_tag_open = preg_replace( '/\sdata-cookieconsent=\"[^"]*+\"/', '', $script_tag_open );
                    $script_tag_open = preg_replace( '/\stype=\"[^"]*+\"/', '', $script_tag_open );

                    $cookie_types = cookiebot_addons_output_cookie_types( $cookie_type );

                    $script_tag_open = str_replace(
                        '<script',
                        sprintf( '<script type="text/plain" data-cookieconsent="%s"', $cookie_types ),
                        $script_tag_open
                    );
                    $script = $script_tag_open . $script_tag_inner . $script_tag_close;
                }
            }
            return $script;
        },
        $buffer
    );
    if ( $updated_scripts === null ) {
        $updated_scripts = $buffer;
        if ( get_option( 'cookiebot_regex_stacklimit' ) === false ) {
            update_option( 'cookiebot_regex_stacklimit', 1 );
        }
    }
    return $updated_scripts;
}

namespace cybot\cookiebot\lib\script_loader_tag;

class Script_Loader_Tag_Old {
    private $tags = array();
    private $ignore_scripts = array();

    public function __construct() {}

    public function add_tag( $tag, $type ) {
        $this->tags[ $tag ] = $type;
    }

    public function ignore_script( $script ) {
        array_push( $this->ignore_scripts, $script );
    }

    public function cookiebot_add_consent_attribute_to_tag( $tag, $handle, $src ) {
        if ( array_key_exists( $handle, $this->tags ) && ! empty( $this->tags[ $handle ] ) ) {
            return '<script src="' . $src . '" type="text/plain" data-cookieconsent="' . implode( ',', $this->tags[ $handle ] ) . '"></script>';
        }

        if ( $this->check_ignore_script( $src ) ) {
            return preg_replace_callback(
                '/<script\s*(?<atts>[^>]*)>/',
                function ( $tag ) use ( $handle ) {
                    if ( ! self::validate_attributes_for_consent_ignore( $handle, $tag['atts'] ) ) {
                        return $tag[0];
                    }
                    return str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag[0] );
                },
                $tag
            );
        }
        return $tag;
    }

    private function check_ignore_script( $src ) {
        foreach ( $this->ignore_scripts as $ignore_script ) {
            if ( strpos( $src, $ignore_script ) !== false ) {
                return true;
            }
        }
        return false;
    }

    private static function validate_attributes_for_consent_ignore( $script_handle, $tag_attributes ) {
        $quoted_handle = preg_quote( $script_handle, '/' );
        if ( ! preg_match( "/id=['\"]$quoted_handle(?:-js(-(after|before))?)?['\"]/", $tag_attributes ) ) {
            return false;
        }
        return is_bool( strpos( $tag_attributes, 'data-cookieconsent=' ) );
    }
}
