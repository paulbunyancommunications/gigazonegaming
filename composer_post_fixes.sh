#!/usr/bin/env bash

# fixes for the media-tags plugin, these are temporary and should be removed in the future
rm -f ${PWD}/public_html/wp-content/plugins/media-tags/media_tags.php.bak | true
cp ${PWD}/public_html/wp-content/plugins/media-tags/media_tags.php ${PWD}/public_html/wp-content/plugins/media-tags/media_tags.php.bak
sed -i "s/'show_ui' 			=> false/'show_ui' 			=> true/" ${PWD}/public_html/wp-content/plugins/media-tags/media_tags.php >/dev/null;
sed -i "s/function MediaTags()/function __construct()/" ${PWD}/public_html/wp-content/plugins/media-tags/media_tags.php >/dev/null;
