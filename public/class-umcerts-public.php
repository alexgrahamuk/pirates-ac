<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://a-graham.com
 * @since      1.0.0
 *
 * @package    Umcerts
 * @subpackage Umcerts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Umcerts
 * @subpackage Umcerts/public
 * @author     A Graham <alex@a-graham.com>
 */
class Umcerts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Umcerts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Umcerts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/umcerts-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Umcerts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Umcerts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/umcerts-public.js', array( 'jquery' ), $this->version, false );

	}



    /**
     * Here we mark uploads as hidden
     *
     * AG: This is working but the pre_updating_files callback maybe better (never crash out on this)
     * https://github.com/ultimatemember/ultimatemember/blob/master/includes/core/um-actions-profile.php
     *
     * @param $form_data
     * @param $user_info
     */
    public function umcerts_after_update_profile($data)
    {
    }


    /**
     * Set the pending flag for any updated files (if not editor)
     *
     * @param $files
     * @return mixed
     */
    public function umcerts_pre_update_profile($files)
    {
        //Editor / admin can do anything
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        $roles = array_combine(array_values($roles), array_values($roles));
        if ((isset($roles['administrator'])) || (isset($roles['editor'])))
            return $files;

        $fields = get_option("umcerts_verify_fields", "");
        $fields = trim($fields, ",");
        $fields = explode(",", $fields);

        foreach ($fields as $field)
        {
            if (!isset($files[$field]))
                continue;

            if ($files[$field] == "")
                continue;

            $user = um_profile_id();

            $v = "Pending";
            if ($files[$field] == "empty_file")
            {
                $v = "";
                //Also blank dates
                update_user_meta( $user, $field."_issue_date", "");
                update_user_meta( $user, $field."_expiry_date", "");
            }


            update_user_meta( $user, $field."_verified", $v);
        }

        return $files;
    }

    public function umcerts_tidy_orphan_data($submitted, $user_id)
    {
        $user = um_profile_id();

        //Double check yes no fields
        $fields = get_option("umcerts_verify_fields", "");
        $fields = trim($fields, ",");
        $fields = explode(",", $fields);

        $yn_fields = get_option("umcerts_yesno_fields", "");
        $yn_fields = trim($yn_fields, ",");
        $yn_fields = explode(",", $yn_fields);

        $map = array();
        foreach ($yn_fields as $field)
            $map[$field] = array_shift($fields);

        /**
         * Debug broken admin setup, look at $map especially
         */
        /*
        echo "Fields";
        print_r($fields);
        echo "<br />";
        echo "YN Fields";
        print_r($yn_fields);
        echo "<br />";
        echo "Map";
        print_r($map);
        die();
        */

        foreach ($map as $field => $vfield)
        {
            if (!isset($submitted[$field]))
                continue;

            if (!is_array($submitted[$field]))
                continue;

            if (!isset($submitted[$field][0]))
                continue;

            if ($submitted[$field][0] != "No")
                continue;

            //Reset all fields
            update_user_meta( $user, $vfield, "");
            update_user_meta( $user, $vfield."_verified", "");
            update_user_meta( $user, $vfield."_issue_date", "");
            update_user_meta( $user, $vfield."_expiry_date", "");
        }
    }

    public function umcerts_pre_update_profile_date_check($changes)
    {
        //Editor / admin can do anything
        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        $roles = array_combine(array_values($roles), array_values($roles));
        if ((isset($roles['administrator'])) || (isset($roles['editor'])))
            return;

        $fields = get_option("umcerts_verify_fields", "");
        $fields = trim($fields, ",");
        $fields = explode(",", $fields);

        $user = um_profile_id();

        $new_fields = array();

        $reverify = array();

        foreach ($fields as $field)
        {
            $reverify[$field."_verified"] = $field;
            $new_fields[$field."_issue_date"] = $field."_verified";
            $new_fields[$field."_expiry_date"] = $field."_verified";
        }

        foreach ($new_fields as $field => $vfield)
        {
            if (isset($changes[$field]))
            {
                $existing = um_user($field);
                if ($existing == $changes[$field])
                    continue;

                update_user_meta( $user, $vfield, "Pending");

                //Don't mess with this field
                if (isset($reverify[$vfield]))
                    unset($reverify[$vfield]);
            }
        }

        //If we need to fix anything here
        foreach ($reverify as $vfield => $field)
        {
            $existing = um_user($vfield);
            update_user_meta( $user, $vfield, $existing);
        }

    }


    public function admin_dash($content)
    {
        global $post;

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        $roles = array_combine(array_values($roles), array_values($roles));

        if ((!isset($roles['administrator'])) && (!isset($roles['editor'])))
            return $content;

        if (('page' == $post->post_type) && (0 === $post->post_parent) && ('user' == $post->post_name))
        {
            if (um_is_profile_owner($user->id) === false)
                return $content;

            ob_start();
            include_once 'partials/umcerts-public-display.php';
            $content = ob_get_contents().$content;
            ob_end_clean();
        }

        return $content;
    }


    /**
     * @param $value
     * @param $field
     * @param string $suffix
     * @return string
     *
     * Defunct
     */
    /*
    public function show_upload_edit($value, $field, $suffix="")
    {
        if ($this->_can_see_pdf($field) === true)
            return $value;

        return $this->_cannot_view_pdf($field.$suffix);
    }*/

    public function show_upload_view($value, $field, $suffix="")
    {
        $status = $this->_can_see_pdf($field);
        if ($status === true)
            return $value;

        return $this->_cannot_view_pdf($field.$suffix, $status);
    }


    protected function _cannot_view_pdf($field, $status=0)
    {
        $msg = "This certificate is pending admin approval.";

        $rclass = "pending";

        if ($status == "-1")
        {
            $rclass = "bad";
            $msg = "An admin has found a problem, please correct this.";
        }

        return  '<div class="um-field">'.
            '<div class="um-field-label"><label>'.ucwords(str_replace("_", " ", $field)).'</label><div class="um-clear"></div></div>'.
            '<div class="um-field-area"><p class="problem '.$rclass.'">'.$msg.'</p></div>'.
            '</div>';
    }


    protected function _can_see_pdf($field)
    {
        $pending = um_user($field.'_verified');
        if (($pending != "Pending") && ($pending != "No"))
            return true;

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;
        $roles = array_combine(array_values($roles), array_values($roles));

        if ((isset($roles['administrator'])) || (isset($roles['editor'])))
            return true;

        return ($pending == "Pending") ? 0 : -1;
    }


    public function backup_last_file($user_id)
    {
//        print_r($user_id);
//        die();
    }

}
