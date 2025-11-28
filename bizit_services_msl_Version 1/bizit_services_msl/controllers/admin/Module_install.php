<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Module_install extends CI_Controller
{
    public function install()
    {
        // Load the migration library
        $this->load->library('migration');
        
        // Check the current version from the database
        $module_version = $this->get_installed_version();

        // Set the target version (for example, 305)
        $target_version = 102;

        // If there is an update to the migration
        if ($module_version < $target_version) {
            // Run the migration for your module
            if ($this->migration->version($target_version) === FALSE) {
                // Handle migration errors
                show_error($this->migration->error_string());
            } else {
                // Update the module version in the database
                $this->update_version($target_version);
                echo "Migration completed successfully.";
            }
        } else {
            echo "No updates required.";
        }
    }

    /**
     * Get the installed version from the database
     */
    private function get_installed_version() {
        $this->db->where('module_name', BIZIT_SERVICES_MSL);
        $query = $this->db->get(db_prefix() . 'modules');
        $module = $query->row();
        
        return $module ? $module->installed_version : '1.0.1';
    }

    /**
     * Update the version in the database after migration
     */
    private function update_version($version) {
        $this->db->where('module_name', BIZIT_SERVICES_MSL);
        $this->db->update(db_prefix() . 'modules', ['installed_version' => $version]);
    }
}

