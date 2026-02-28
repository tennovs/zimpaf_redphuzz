#!/bin/bash
set -u

WP_PATH="/var/www/html/wordpress"
WP="php $WP_PATH/wp-cli.phar --allow-root --path=$WP_PATH"

echo "Containter Waiting for mysql database..."

until php -r "exit(@mysqli_connect('db_host','root','') ? 0 : 1);" > /dev/null 2>&1
do
  echo "Containter Waiting for DB..."
  sleep 2
done

echo "DB ready."

echo "Activating foundation plugins..."
set -u

WP_PATH="/var/www/html/wordpress"
WP="php $WP_PATH/wp-cli.phar --allow-root --path=$WP_PATH"

echo "Starting individual plugin activation..."

activate_plugin () {
    plugin=$1
    echo "Activating: $plugin"

    timeout 7s $WP plugin activate "$plugin" > /dev/null 2>&1

    if [ $? -ne 0 ]; then
        echo "⚠ $plugin : failed to activate (skipped)"
    else
        echo "✓ $plugin : activated"
    fi
}

# ---- Plugins ----
activate_plugin akismet
activate_plugin all-in-one-wp-security-and-firewall
activate_plugin arprice-responsive-pricing-table
activate_plugin show-all-comments-in-one-page
activate_plugin crm-perks-forms
activate_plugin essential-real-estate
activate_plugin gallery-album
activate_plugin hello
activate_plugin hypercomments
activate_plugin joomsport-sports-league-results-management
activate_plugin kivicare-clinic-management-system
activate_plugin nirweb-support
activate_plugin newsletter-optin-box
activate_plugin phastpress
activate_plugin photo-gallery
activate_plugin pie-register
activate_plugin rezgo
activate_plugin totop-link
activate_plugin seo-local-rank
activate_plugin ubigeo-peru
activate_plugin webp-converter-for-media
activate_plugin udraw
activate_plugin usc-e-shop
activate_plugin woocommerce
activate_plugin nmedia-user-file-uploader

echo "First activation pass complete."

# ---- Second Pass (dependency resolution sweep) ----

echo "Running final dependency sweep..."
timeout 30s $WP plugin activate --all > /dev/null 2>&1 \
    && echo "Final sweep completed." \
    || echo "Final sweep encountered issues (continuing anyway)."

echo "Activation cycle complete."
echo "Starting Apache..."

# exec apache2-foreground
exec apache2-foreground > /dev/null 2>&1
