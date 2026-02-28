<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_Location')) {
    /**
     * Class ERE_Admin
     */
    class ERE_Admin_Location
    {
        /*
         * Countries settings
         */
        public function countries_create_menu() {
            add_submenu_page(
                'edit.php?post_type=property',
                esc_html__( 'Country', 'essential-real-estate' ),
                esc_html__( 'Country','essential-real-estate' ),
                'manage_real_estate',
                'countries_settings',
                array( $this, 'countries_settings_page' ));
        }

        public function countries_register_setting() {
            register_setting( 'countries-settings-group', 'country_list' );
        }

        public function countries_settings_page() {?>
            <div class="wrap ere-countries-settings">
                <h1><?php esc_html_e( 'Countries', 'essential-real-estate' ); ?></h1>
                <p><?php esc_html_e( 'Please Choose Country', 'essential-real-estate' ); ?></p>
                <form method="post" action="options.php">
                    <?php settings_fields( 'countries-settings-group' ); ?>
                    <?php do_settings_sections( 'countries-settings-group' ); ?>
                    <?php
                    $countries_selected = get_option( 'country_list' );
                    $countries = ere_get_countries();
                    foreach($countries as $key => $value):
                        ?>
                        <div class="form-group">
                            <input type="checkbox" name="country_list[]" <?php if($countries_selected) echo in_array($key, $countries_selected) ? 'checked' : ''; ?> value="<?php echo esc_attr($key);?>" id="<?php echo esc_attr($key);?>"/>
                            <label for="<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></label>
                        </div>
                    <?php endforeach;?>
                    <?php submit_button(); ?>
                </form>
            </div>
        <?php }

        public function add_form_fields_property_city($taxonomy) {
            $default_country = ere_get_option('default_country', 'US');
            ?>
            <div id="property-country" class="form-field term-group selectdiv ere-property-select-meta-box-wrap">
                <label for="property_city_country"><?php esc_html_e('Country', 'essential-real-estate'); ?></label>
                <select id="property_city_country" name="property_city_country" class="postform ere-property-country-ajax">
                    <?php
                    $countries = ere_get_selected_countries();
                    foreach ($countries as $key => $country):
                        echo '<option ' . selected($default_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <div id="property-state" class="form-field term-group selectdiv ere-property-select-meta-box-wrap">
                <label for="property_city_state"><?php esc_html_e('Province / State', 'essential-real-estate'); ?></label>
                <select id="property_city_state" name="property_city_state" data-slug="0" class="postform ere-property-state-ajax">
                    <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                    <?php
                    $terms = get_categories(
                        array(
                            'taxonomy' => 'property-state',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0
                        )
                    );
                    foreach ($terms as $term): ?>
                        <option
                            value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
        }

        public function save_property_city_meta( $term_id, $tt_id ){
            if( isset( $_POST['property_city_country'] ) && !empty($_POST['property_city_country']) ){
                $property_city_country = sanitize_title(wp_unslash($_POST['property_city_country'] ) );
                add_term_meta( $term_id, 'property_city_country', strtoupper($property_city_country), true );
            }
            if( isset( $_POST['property_city_state'] ) && !empty($_POST['property_city_state']) ){
                $property_city_state = sanitize_title(wp_unslash($_POST['property_city_state'])  );
                add_term_meta( $term_id, 'property_city_state', $property_city_state, true );
            }
        }

        public function edit_form_fields_property_city( $term, $taxonomy ){
            $property_city_country = get_term_meta( $term->term_id, 'property_city_country', true );
            $property_city_state = get_term_meta( $term->term_id, 'property_city_state', true );
            ?>
            <tr id="property-country" class="form-field term-group-wrap ere-property-select-meta-box-wrap">
            <th scope="row"><label for="property_city_country"><?php esc_html_e('Country', 'essential-real-estate'); ?></label></th>
            <td><select class="postform ere-property-country-ajax" id="property_city_country" name="property_city_country">
                    <?php
                    $countries = ere_get_selected_countries();
                    foreach ($countries as $key => $country):
                        echo '<option ' . selected($property_city_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                    endforeach;
                    ?>
                </select></td>
            </tr>
            <tr id="property-state" class="form-field term-group-wrap ere-property-select-meta-box-wrap">
                <th scope="row"><label for="property_city_state"><?php esc_html_e('Province / State', 'essential-real-estate'); ?></label></th>
                <td><select data-selected="<?php echo esc_attr($property_city_state); ?>" data-slug="0" class="postform ere-property-state-ajax" id="property_city_state" name="property_city_state">
                        <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                        <?php
                        $terms = get_categories(
                            array(
                                'taxonomy' => 'property-state',
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'parent' => 0
                            )
                        );
                        foreach ($terms as $term):
                            echo '<option ' . selected($property_city_state, $term->term_id, false) . ' value="'. esc_attr($term->term_id).'">'. esc_html($term->name).'</option>';
                        endforeach; ?>
                    </select></td>
            </tr>
            <?php
        }

        public function update_property_city_meta( $term_id, $tt_id ){
            if( isset( $_POST['property_city_country'] ) && !empty($_POST['property_city_country']) ){
                $property_city_country = sanitize_title(wp_unslash($_POST['property_city_country'])  );
                update_term_meta( $term_id, 'property_city_country', strtoupper($property_city_country));
            }
            if( isset( $_POST['property_city_state'] ) && !empty($_POST['property_city_state'])){
                $property_city_state = sanitize_title( wp_unslash($_POST['property_city_state']) );
                update_term_meta( $term_id, 'property_city_state', $property_city_state);
            }
        }

        public function add_columns_property_city($columns ){
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['name'] = esc_html__('Name', 'essential-real-estate');
            $columns['description'] = esc_html__('Description', 'essential-real-estate');
            $columns['slug'] = esc_html__('Slug', 'essential-real-estate');
            $columns['property_city_state'] = esc_html__('Province / State', 'essential-real-estate');
            $columns['posts'] = esc_html__('Count', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb','name','description', 'slug', 'property_city_state','posts');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }

        public function add_columns_property_city_content( $content, $column_name, $term_id ){

            if( $column_name !== 'property_city_state' ){
                return $content;
            }
            $term_id = absint( $term_id );
            $property_city_state_tax_id  = get_term_meta( $term_id, 'property_city_state', true );
            if(!empty($property_city_state_tax_id))
            {
                $property_city_state = get_term( $property_city_state_tax_id );
                if( !empty( $property_city_state ) && isset($property_city_state->name )){
                    $content .= esc_html( $property_city_state->name );
                }
            }
            return $content;
        }

        public function add_columns_property_city_sortable( $sortable ){
            $sortable[ 'property_city_state' ] = 'property_city_state';
            return $sortable;
        }

        //property-neighborhood
        public function add_form_fields_property_neighborhood($taxonomy) {
            $default_country = ere_get_option('default_country', 'US');
            ?>
            <div id="property-country" class="form-field term-group selectdiv ere-property-select-meta-box-wrap">
                <label for="property_neighborhood_country"><?php esc_html_e('Country', 'essential-real-estate'); ?></label>
                <select id="property_neighborhood_country" name="property_neighborhood_country" class="postform ere-property-country-ajax">
                    <?php
                    $countries = ere_get_selected_countries();
                    foreach ($countries as $key => $country):
                        echo '<option ' . selected($default_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <div id="property-state" class="form-field term-group selectdiv ere-property-select-meta-box-wrap">
                <label for="property_neighborhood_state"><?php esc_html_e('Province / State', 'essential-real-estate'); ?></label>
                <select id="property_neighborhood_state" name="property_neighborhood_state" data-slug="0" class="postform ere-property-state-ajax">
                    <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                    <?php
                    $terms_state = get_categories(
                        array(
                            'taxonomy' => 'property-state',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0
                        )
                    );
                    foreach ($terms_state as $term): ?>
                        <option
                            value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="property-city" class="form-field term-group selectdiv ere-property-select-meta-box-wrap">
                <label for="property_neighborhood_city"><?php esc_html_e('City', 'essential-real-estate'); ?></label>
                <select id="property_neighborhood_city" name="property_neighborhood_city" data-slug="0" class="postform ere-property-city-ajax">
                    <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                    <?php
                    $terms_city = get_categories(
                        array(
                            'taxonomy' => 'property-city',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => false,
                            'parent' => 0
                        )
                    );
                    foreach ($terms_city as $term): ?>
                        <option
                            value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
        }

        public function save_property_neighborhood_meta( $term_id, $tt_id ){
            if( isset( $_POST['property_neighborhood_country'] ) && !empty($_POST['property_neighborhood_country']) ){
                $property_neighborhood_country = sanitize_title( wp_unslash($_POST['property_neighborhood_country'] ) );
                add_term_meta( $term_id, 'property_neighborhood_country', strtoupper($property_neighborhood_country), true );
            }
            if( isset( $_POST['property_neighborhood_state'] ) && !empty($_POST['property_neighborhood_state']) ){
                $property_neighborhood_state = sanitize_title( wp_unslash($_POST['property_neighborhood_state']) );
                add_term_meta( $term_id, 'property_neighborhood_state', $property_neighborhood_state, true );
            }
            if( isset( $_POST['property_neighborhood_city'] ) && !empty($_POST['property_neighborhood_city']) ){
                $property_neighborhood_city = sanitize_title(wp_unslash($_POST['property_neighborhood_city'])  );
                add_term_meta( $term_id, 'property_neighborhood_city', $property_neighborhood_city, true );
            }
        }

        public function edit_form_fields_property_neighborhood( $term, $taxonomy ){
            $property_neighborhood_country = get_term_meta( $term->term_id, 'property_neighborhood_country', true );
            $property_neighborhood_state = get_term_meta( $term->term_id, 'property_neighborhood_state', true );
            $property_neighborhood_city =  get_term_meta( $term->term_id, 'property_neighborhood_city', true );
            ?>
            <tr id="property-country" class="form-field term-group-wrap ere-property-select-meta-box-wrap">
                <th scope="row"><label for="property_neighborhood_country"><?php esc_html_e('Country', 'essential-real-estate'); ?></label></th>
                <td><select class="postform ere-property-country-ajax" id="property_neighborhood_country" name="property_neighborhood_country">
                        <?php
                        $countries = ere_get_selected_countries();
                        foreach ($countries as $key => $country):
                            echo '<option ' . selected($property_neighborhood_country, $key, false) . ' value="' . $key . '">' . $country . '</option>';
                        endforeach;
                        ?>
                    </select></td>
            </tr>
            <tr id="property-state" class="form-field term-group-wrap ere-property-select-meta-box-wrap">
                <th scope="row"><label for="property_neighborhood_state"><?php esc_html_e('Province / State', 'essential-real-estate'); ?></label></th>
                <td><select data-selected="<?php echo esc_attr($property_neighborhood_state); ?>" data-slug="0" class="postform ere-property-state-ajax" id="property_neighborhood_state" name="property_neighborhood_state">
                        <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                        <?php
                        $terms_state = get_categories(
                            array(
                                'taxonomy' => 'property-state',
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'parent' => 0
                            )
                        );
                        foreach ($terms_state as $term):
                            echo '<option ' . selected($property_neighborhood_state, $term->term_id, false) . ' value="'. esc_attr($term->term_id).'">'. esc_html($term->name).'</option>';
                        endforeach; ?>
                    </select></td>
            </tr>
            <tr id="property-city" class="form-field term-group-wrap ere-property-select-meta-box-wrap">
                <th scope="row"><label for="property_neighborhood_city"><?php esc_html_e('City', 'essential-real-estate'); ?></label></th>
                <td><select data-selected="<?php echo esc_attr($property_neighborhood_city); ?>" data-slug="0" class="postform ere-property-city-ajax" id="property_neighborhood_city" name="property_neighborhood_city">
                        <option value=""><?php esc_html_e('None', 'essential-real-estate'); ?></option>
                        <?php
                        $terms_city = get_categories(
                            array(
                                'taxonomy' => 'property-city',
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'parent' => 0
                            )
                        );
                        foreach ($terms_city as $term):
                            echo '<option ' . selected($property_neighborhood_city, $term->term_id, false) . ' value="'. esc_attr($term->term_id).'">'. esc_html($term->name).'</option>';
                        endforeach; ?>
                    </select></td>
            </tr>
            <?php
        }

        public function update_property_neighborhood_meta( $term_id, $tt_id ){
            if( isset( $_POST['property_neighborhood_country'] ) && !empty($_POST['property_neighborhood_country']) ){
                $property_neighborhood_country = sanitize_title(wp_unslash($_POST['property_neighborhood_country']));
                update_term_meta( $term_id, 'property_neighborhood_country', strtoupper($property_neighborhood_country));
            }
            if( isset( $_POST['property_neighborhood_state'] ) && !empty($_POST['property_neighborhood_state'])){
                $property_neighborhood_state = sanitize_title(wp_unslash($_POST['property_neighborhood_state'])  );
                update_term_meta( $term_id, 'property_neighborhood_state', $property_neighborhood_state);
            }
            if( isset( $_POST['property_neighborhood_city'] ) && !empty($_POST['property_neighborhood_city'])){
                $property_neighborhood_city = sanitize_title(wp_unslash($_POST['property_neighborhood_city'])  );
                update_term_meta( $term_id, 'property_neighborhood_city', $property_neighborhood_city);
            }
        }

        public function add_columns_property_neighborhood($columns ){
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['name'] = esc_html__('Name', 'essential-real-estate');
            $columns['description'] = esc_html__('Description', 'essential-real-estate');
            $columns['slug'] = esc_html__('Slug', 'essential-real-estate');
            $columns['property_neighborhood_city'] = esc_html__('City', 'essential-real-estate');
            $columns['posts'] = esc_html__('Count', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb','name','description', 'slug','property_neighborhood_city','posts');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }

        public function add_columns_property_neighborhood_content( $content, $column_name, $term_id ){

            if( $column_name !== 'property_neighborhood_city' ){
                return $content;
            }
            $term_id = absint( $term_id );
            $property_neighborhood_city_tax_id  = get_term_meta( $term_id, 'property_neighborhood_city', true );
            if(!empty($property_neighborhood_city_tax_id))
            {
                $property_neighborhood_city = get_term( $property_neighborhood_city_tax_id );
                if( !empty( $property_neighborhood_city ) && isset($property_neighborhood_city->name )){
                    $content .= esc_html( $property_neighborhood_city->name );
                }
            }
            return $content;
        }

        public function add_columns_property_neighborhood_sortable( $sortable ){
            $sortable[ 'property_neighborhood_city' ] = 'property_neighborhood_city';
            return $sortable;
        }
    }
}