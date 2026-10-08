<?php
/**
 * MCP Safety Filter — blocks all destructive abilities.
 *
 * Hooks into the MCP Adapter's ability discovery and removes abilities
 * whose names contain delete, trash, or destroy keywords.
 *
 * This is a defence-in-depth measure: even if a plugin registers a
 * delete ability, it will never be exposed to AI assistants via MCP.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'mcp_adapter_abilities', 'seo_mcp_safety_filter', 999 );

function seo_mcp_safety_filter( $abilities ) {
    if ( ! is_array( $abilities ) ) {
        return $abilities;
    }

    // Keywords that indicate destructive operations.
    $blocked_keywords = array( 'delete', 'trash', 'destroy', 'remove', 'purge', 'erase', 'wipe' );

    $filtered = array();

    foreach ( $abilities as $key => $ability ) {
        $name = '';

        // Ability can be an array or object — get the name/key.
        if ( is_array( $ability ) && isset( $ability['name'] ) ) {
            $name = strtolower( $ability['name'] );
        } elseif ( is_object( $ability ) && isset( $ability->name ) ) {
            $name = strtolower( $ability->name );
        } else {
            $name = strtolower( (string) $key );
        }

        $blocked = false;
        foreach ( $blocked_keywords as $keyword ) {
            if ( strpos( $name, $keyword ) !== false ) {
                $blocked = true;
                break;
            }
        }

        if ( ! $blocked ) {
            $filtered[ $key ] = $ability;
        }
    }

    return $filtered;
}
