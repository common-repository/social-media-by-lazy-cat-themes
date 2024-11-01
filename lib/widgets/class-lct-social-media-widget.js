/**
 * Created by Duncan on 20/06/2015.
 */

( function( $ ) {
    var body    = $( 'body' );

    $(document).ready(function() {

        // Makes follower count visible only when the social networking icons are set to large
        $('.widgets-holder-wrap, .wp-full-overlay-sidebar').on('change','.social-bar-size', follower_count_visibility );

        function follower_count_visibility() {
            toggle_on_select( $(this), 'large', 'div.widget-group', '.show_follower_count' );
        }

        function toggle_on_select( select_node, matching_value, parent_selector, toggle_item_selector) {
            var queryParent = select_node.parentsUntil(parent_selector).parent();

            if ( select_node.val() === matching_value ) {
                hide_nodes_on_boolean( true, toggle_item_selector, queryParent);
            } else {
                hide_nodes_on_boolean( false, toggle_item_selector, queryParent);
            }

        }

        function hide_nodes_on_boolean( is_checked, selector, parent) {
            if (is_checked) {
                parent.find(selector).each( show_node );
            } else {
                parent.find(selector).each( hide_node );
            }
        }

        function hide_node() {
            $(this).addClass('hidden');
        }

        function show_node() {
            $(this).removeClass('hidden');
        }
    } )

} )( jQuery );



