<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 *	@author : tonini46
 *  @support: tonini46@yahoo.com
 *	date	: 01 June, 2016
 *	Swivernet Services
 *	http://www.tazamali.com
 *  version: 1.0
 */

class Services_Model extends App_Model
{
    //new
    public const CALIBRATION_ITEMS = ['Level', 'GNSS', 'Total Station', 'Theodolite', 'Lasers'];    
    //public const STATUS_PAID = 2;    
    /**
     * Add new services
     * @param array $data Product data
     * @return boolean
     */
    public function add($data)
    {
        unset($data['serviceid']);

        if ($data['service_type_code'] != '001') {
            unset($data['penalty_rental_price']);
            unset($data['rental_serial']);
            unset($data['rental_duration_check']);
            unset($data['rental_status']);
        } else {
            unset($data['quantity_unit']);
        }

        $this->db->insert('tblservices_module', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Product Added [ID:' . $insert_id . ', ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }
    /**
     * Update Product
     * @param  array $data Product to update
     * @return boolean
     */
    public function edit($data)
    {
        $service_code = $data['serviceid'];
        unset($data['serviceid']);

        /*if($data['service_type_code'] != '001'){
           unset($data['penalty_rental_price']); 
           unset($data['rental_serial']); 
           unset($data['rental_duration_check']); 
           unset($data['rental_status']); 
        }*/

        $this->db->where('service_code', $service_code);
        $this->db->update('tblservices_module', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Product Updated [ID: ' . $service_code . ', ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function get_service_details($service_rental_agreement_id)
    {
        $this->db->select('*'); // Adjust if you need specific fields
        $this->db->from('tblfield_report');
        $this->db->where('service_rental_agreement_id', $service_rental_agreement_id);
        $query = $this->db->get();

        return $query->result(); // Returns an array of results
    }

    // public function get_service_details($service_rental_agreement_id) {
    //     // Select the desired fields from tblfield_report and join with other tables
    //     $this->db->select('tblfield_report.*, 
    //                        tblservices_module.name AS service_name, 
    //                        tblservices_module.rental_serial, 
    //                        tblservice_type.name AS category_name');
    //     $this->db->from('tblfield_report');
    //     $this->db->join('tblservices_module', 'tblservices_module.serviceid = tblfield_report.service_rental_agreement_id', 'left');
    //     $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code', 'left');
    //     $this->db->where('tblfield_report.service_rental_agreement_id', $service_rental_agreement_id);

    //     $query = $this->db->get();

    //     return $query->result(); // Returns an array of results
    // }




    /**
     * Delete services
     * @param  mixed $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->db->select('serviceid', false);
        $this->db->from('tblservices_module');
        $this->db->where('service_code', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->where('service_code', $id);
            $this->db->delete('tblservices_module');
            log_activity('Service Deleted [ID: ' . $id . ']');
            return true;
        } else {
            return false;
        }

        return false;
    }

    /**
     * Add new Service Category
     * @param array $data Service Category data
     * @return boolean
     */
    public function add_category($data)
    {
        unset($data['service_typeid']);

        $this->db->insert('tblservice_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Category Added [ID:' . $insert_id . ', ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update Service Category
     * @param  array $data Service Category to update
     * @return boolean
     */
    public function edit_category($data)
    {
        $service_category_id = $data['service_typeid'];
        unset($data['service_typeid']);

        $this->db->where('service_typeid', $service_category_id);
        $this->db->update('tblservice_type', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Category Updated [ID: ' . $service_category_id . ', ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete services
     * @param  mixed $id
     * @return boolean
     */
    public function delete_category($id)
    {
        $this->db->select('service_code', false);
        $this->db->from('tblservices_module');
        $this->db->where('service_type_code', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return false;
        } else {
            $this->db->where('type_code', $id);
            $this->db->delete('tblservice_type');
            log_activity('Service Category Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    // All Service Categories
    /**
     * [get_all_services_category_info description]
     * @return [type] [description]
     */
    public function get_all_services_category_info($where = array(), $filtered = false)
    {
        $this->db->select('*', false);
        $this->db->order_by('tblservice_type.service_typeid', 'ASC');
        if ($filtered) {
            $this->db->where('type_code !=', '001');
            $this->db->where('type_code !=', '002');
        }

        $this->db->where($where);


        $result = $this->db->get('tblservice_type')->result_array();
        return $result;
    }



    /**
     * @param  integer ID
     * @param  integer Status ID
     * @return boolean
     * Update service status Active/Inactive
     */
    public function change_service_status($id, $status)
    {
        $hook_data['id']     = $id;
        $hook_data['status'] = $status;
        $hook_data           = hooks()->do_action('change_service_status', $hook_data);
        $status              = $hook_data['status'];
        $id                  = $hook_data['id'];
        $this->db->where('service_code', $id);
        $this->db->update('tblservices_module', array(
            'status' => $status
        ));
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Status Changed [ServiceID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @param  integer Status ID
     * @return boolean
     * Update service category status Active/Inactive
     */
    public function change_service_category_status($id, $status)
    {
        $hook_data['id']     = $id;
        $hook_data['status'] = $status;
        $hook_data           = hooks()->do_action('change_service_category_status', $hook_data);
        $status              = $hook_data['status'];
        $id                  = $hook_data['id'];
        $this->db->where('type_code', $id);
        $this->db->update('tblservice_type', array(
            'status' => $status
        ));
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Category Status Changed [Service Category ID: ' . $id . ' Status(Active/Inactive): ' . $status . ']');

            return true;
        }

        return false;
    }

    /** Get Entity Info */
    public function get_services_information_by_id($id)
    {
        $this->db->select('tblservices_module.*', false);
        $this->db->select('tblsubcategory.subcategory_name', false);
        $this->db->select('tblcategory.category_name', false);
        $this->db->from('tblservices_module');
        $this->db->where('tblservices_module.services_id', $id);
        $this->db->join('tblsubcategory', 'tblsubcategory.subcategory_id  =  tblservices_module.subcategory_id ', 'left');
        $this->db->join('tblcategory', 'tblcategory.category_id  =  tblsubcategory.category_id ', 'left');

        $this->db->order_by('tblservices_module.services_id', ' DESC');
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    public function get_services_stock_information_by_id($id)
    {
        $this->db->select('tblservices_module.services_name', false);
        $this->db->select('tblservices_moduleservices.*', false);
        $this->db->from('tblservices_moduleservices');
        $this->db->where('tblservices_moduleservices.id', $id);
        $this->db->join('tblservices_module', 'tblservices_module.services_id  =  tblservices_moduleservices.services_id ', 'inner');

        $this->db->order_by('tblservices_moduleservices.id', ' DESC');
        $query_result = $this->db->get();
        $result = $query_result->row();
        return $result;
    }

    //check existance:
    public function check_if_exist($select, $table, $value)
    {
        $this->db->select($select, false);
        $this->db->from($table);
        $this->db->where($select, $value);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * [get_all_services_info description]
     * @return [type] [description]
     */
    public function get_all_services_info()
    {
        $this->db->select('tblservices_module.*', false);
        $this->db->select('tblsubcategory.subcategory_name', false);
        $this->db->select('tblcategory.category_name', false);
        $this->db->from('tblservices_module');
        $this->db->join('tblsubcategory', 'tblsubcategory.subcategory_id  =  tblservices_module.subcategory_id ', 'left');
        $this->db->join('tblcategory', 'tblcategory.category_id  =  tblsubcategory.category_id ', 'left');

        $this->db->order_by('tblservices_module.services_id', ' DESC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    public function get_inspection_data($service_request_id)
    {
        $this->db->where('service_request_id', $service_request_id);
        return $this->db->get('tblinspection_requests')->result_array();
    }

    public function get_checklist_data($service_request_id)
    {
        $this->db->select('*');
        $this->db->from('tblchecklist1');
        $this->db->where('service_request_id', $service_request_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_collection_data($service_request_id)
    {
        $this->db->select('*');
        $this->db->from('tblcollection1');
        $this->db->where('service_request_id', $service_request_id);
        $query = $this->db->get();
        return $query->row(); // Assuming a single row is returned
    }

    public function delete_inspection_item($service_request_id, $inspection_item, $inspection_type)
    {
        $this->db->where('service_request_id', $service_request_id);
        $this->db->where('inspection_item', $inspection_item);
        $this->db->where('inspection_type', $inspection_type);

        return $this->db->delete('tblinspection_requests'); // Replace with your table name
    }





    //Get All Services
    public function get_all_services($code, $filtered = false, $exclude = array())
    {

        $data = array();
        $this->db->select('tblservices_module.*, tblservice_type.name as category_name');
        if (isset($code) && $code != null) {
            $this->db->where('service_type_code', $code);
        }

        if ($code == '001' and $filtered) {
            $this->db->where('rental_status', 'Not-Hired');
        }

        if (count($exclude) > 0) {
            foreach ($exclude as $key => $value) {
                $this->db->where('service_type_code !=', $value);
            }
        }

        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code AND tblservice_type.status = 1');
        $data = $this->db->get('tblservices_module')->result();


        return $data;
    }

    public function get_all_services_accessories()
    {
        $this->db->select('id, description, rate'); // Select the columns needed
        $this->db->from('tblitems'); // Table name
        $this->db->where('active', 1); // Fetch only active services
        $query = $this->db->get();

        return $query->result(); // Return the result as an array of objects
    }

    public function get_request_accessories($service_request_id)
    {
        // Select the columns needed
        $this->db->select('sra.id, sra.accessory_id, sra.price, ti.description, ti.rate');
        // Join tblitems to get accessory details
        $this->db->from('tblservice_request_accessories sra');
        $this->db->join('tblitems ti', 'sra.accessory_id = ti.id', 'left'); // Left join to get accessory info
        // Filter by service request ID
        $this->db->where('sra.service_request_id', $service_request_id);
        $this->db->where('ti.active', 1); // Ensure the accessory is active
        $query = $this->db->get();

        // Return the result as an array of objects
        return $query->result();
    }



    public function get_service_accessory_by_id($id)
    {
        $this->db->select('rate'); // Select the price column
        $this->db->from('tblitems');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row(); // Return a single row
    }


    public function get_all_services_warranty($code, $filtered = false, $exclude = array())
    {
        $this->db->select('serviceid, name'); // Select only serviceid and name
        if (isset($code) && $code != null) {
            $this->db->where('service_type_code', $code);
        }

        if ($code == '001' && $filtered) {
            $this->db->where('rental_status', 'Not-Hired');
        }

        if (count($exclude) > 0) {
            foreach ($exclude as $value) {
                $this->db->where('service_type_code !=', $value);
            }
        }

        $this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code AND tblservice_type.status = 1');
        $data = $this->db->get('tblservices_module')->result_array(); // Return as array

        return $data;
    }

    public function get_warranty_services()
    {
        // Select fields needed for the dropdown
        $this->db->select('
            tblitems.id,
            tblitems.commodity_barcode,
            tblitems.description AS name,
            tblitems.commodity_barcode AS service_code,
            tblitems.commodity_code
        ');

        // From tblitems table
        $this->db->from('tblitems');

        // Return the result as an array
        return $this->db->get()->result_array();
    }




    public function get_new_warranty_services()
    {
        $this->db->select('serviceid, name, serial_number, commodity_code, date_sold, warranty_days_remaining, warranty_end_date, description');

        $query = $this->db->get('tblwarranty');

        log_message('debug', 'Query: ' . $this->db->last_query());
        log_message('debug', 'Data result: ' . print_r($query->result_array(), true)); // Changed to query result

        return $query->result_array();
    }


    public function add_warranty($data)
    {
        // Validate the input data to ensure 'serial_number' is provided
        if (empty($data['serial_number'])) {
            return false; // Serial number is required
        }

        // Check if the serial number already exists
        $this->db->where('serial_number', $data['serial_number']);
        $query = $this->db->get('tblwarranty');

        if ($query->num_rows() > 0) {
            return false; // Serial number already exists
        }

        // Insert the data if the serial number doesn't exist
        if ($this->db->insert('tblwarranty', $data)) {
            return $this->db->insert_id(); // Return the newly inserted ID
        }

        return false; // Return false if the insert fails
    }



    public function get_table_products_bulk($id)
    {
        $CI = &get_instance();

        // Step 1: Get the service_request_id(s) from tblservice_request where invoice_rel_id = $id
        $CI->db->select('service_request_id');
        $CI->db->where('invoice_rel_id', $id);
        $service_request_query = $CI->db->get('tblservice_request');

        $items_data = [];

        foreach ($service_request_query->result() as $service_request_row) {
            $service_request_id = $service_request_row->service_request_id;

            // Step 2: Get the serviceid(s) from tblservice_request_details using the service_request_id
            $CI->db->select('serviceid, price');
            $CI->db->where('service_request_id', $service_request_id);
            $details_query = $CI->db->get('tblservice_request_details');

            foreach ($details_query->result() as $details_row) {
                $serviceid = $details_row->serviceid;
                $price = $details_row->price;

                // Step 3: Get the service name from tblservices_module using the serviceid
                $CI->db->select('name');
                $CI->db->where('serviceid', $serviceid);
                $service_query = $CI->db->get('tblservices_module');

                $service_name = $service_query->row() ? $service_query->row()->name : 'Unknown Service';

                // Prepare HTML row with the gathered data
                $items_data[] = '<tr><td>' . $service_name . '</td><td>' . $service_request_id . '</td><td>' . $price . '</td></tr>';
            }
        }

        return ['html' => implode('', $items_data)];
    }

    public function get_request($code = null)
    {
        $this->db->select('tblservice_request.*');
        $this->db->from('tblservice_request');
        $this->db->where('tblservice_request.service_request_code', $code);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }

    public function get_request_details($service_request_id = null)
    {
        $this->db->select('tblservice_request_details.*');
        $this->db->from('tblservice_request_details');
        $this->db->where('tblservice_request_details.service_request_id', $service_request_id);
        $query_result = $this->db->get();
        $result = $query_result->result();

        return $result;
    }

    /**
     * Add new services
     * @param array $data Product data
     * @return boolean
     */
    public function add_request($data)
    {

        $this->db->insert('tblservice_request', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Added [ID:' . $insert_id . ', ' . $data['service_request_code'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update Product
     * @param  array $data Product to update
     * @return boolean
     */
    // public function edit_request($data)
    // {
    //     $service_request_id = $data['service_request_id'];
    //     unset($data['service_request_id']);

    //     $this->db->where('service_request_id', $service_request_id);
    //     $this->db->update('tblservice_request', $data);
    //     if ($this->db->affected_rows() > 0) {
    //         log_activity('Service Request Updated [ID: ' . $service_request_id . ', ' . $data['service_request_code'] . ']');

    //         return true;
    //     }

    //     return false;
    // }
    public function edit_request($data)
    {
        $service_request_id = $data['service_request_id'];
        unset($data['service_request_id']);

        // Update query
        $this->db->where('service_request_id', $service_request_id);
        $this->db->update('tblservice_request', $data);

        // Always consider the update successful, unless there's a database error
        if ($this->db->trans_status() === false) {
            log_message('error', 'Failed to update Service Request ID: ' . $service_request_id);
            return false;
        }

        log_activity('Service Request Updated [ID: ' . $service_request_id . ']');
        return true;
    }



    // public function edit_request($data)
    // {
    //     // Get the service request ID
    //     $service_request_id = $data['service_request_code'];

    //     // Remove service request ID from data (since it's used for the WHERE clause)
    //     unset($data['service_request_id']);

    //     // Check if new files are being uploaded
    //     if (isset($_FILES['service_files']) && !empty($_FILES['service_files']['name'][0])) {
    //         // Get existing files
    //         $existing_files = $this->get_existing_files($service_request_id);

    //         // Configure file upload settings
    //         $config = [
    //             'upload_path'   => './modules/bizit_services_msl/uploads/reports/',
    //             'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx',
    //             'max_size'      => 2048, // Max file size in KB
    //         ];

    //         $this->load->library('upload', $config);

    //         // Process the uploaded files
    //         $files = $_FILES['service_files'];
    //         $uploaded_file_names = [];

    //         for ($i = 0; $i < count($files['name']); $i++) {
    //             if (!empty($files['name'][$i])) {
    //                 // Set up the $_FILES array for the upload library
    //                 $_FILES['file']['name'] = $files['name'][$i];
    //                 $_FILES['file']['type'] = $files['type'][$i];
    //                 $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
    //                 $_FILES['file']['error'] = $files['error'][$i];
    //                 $_FILES['file']['size'] = $files['size'][$i];

    //                 // Try uploading the file
    //                 if ($this->upload->do_upload('file')) {
    //                     $file_data = $this->upload->data();
    //                     $uploaded_file_names[] = $file_data['file_name']; // Store the uploaded file name
    //                 } else {
    //                     log_message('error', 'File upload failed: ' . $this->upload->display_errors());
    //                     set_alert('danger', 'File upload failed: ' . $this->upload->display_errors());
    //                     redirect(admin_url('services/edit_request'), 'refresh');
    //                     return;
    //                 }
    //             }
    //         }

    //         // Merge the existing and newly uploaded file names
    //         $all_files = array_merge($existing_files, $uploaded_file_names);

    //         var_dump($all_files);

    //         // Save the merged filenames as JSON
    //         $data['report_files'] = json_encode($all_files);
    //     }

    //     // Update the service request in the database
    //     $this->db->where('service_request_code', $service_request_id);
    //     $this->db->update('tblservice_request', $data);

    //     // Check if the update was successful
    //     if ($this->db->affected_rows() > 0) {
    //         log_activity('Service Request Updated [ID: ' . $service_request_id . ', ' . $data['service_request_code'] . ']');
    //         return true;
    //     }

    //     return false;
    // }


    // Method to get existing files for a specific service request
    public function get_existing_files($service_request_id)
    {
        // Query the database to get the existing files (if any)
        $this->db->select('report_files');
        $this->db->from('tblservice_request');
        $this->db->where('service_request_code', $service_request_id);
        $query = $this->db->get();

        // Check if the service request exists and has files
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return json_decode($result->report_files, true) ?: [];
        }

        // Return an empty array if no files exist
        return [];
    }


    public function get_rental_agreement_files($agreement_id)
    {
        $this->db->select('report_files');
        $this->db->where('service_rental_agreement_id', $agreement_id);
        $result = $this->db->get('tblservice_rental_agreement')->row();

        if (!empty($result->report_files)) {
            // Parse the `report_files` field (assuming JSON storage)
            return json_decode($result->report_files);
        }

        return []; // Return an empty array if no files exist
    }


    public function get_service_files($service_request_id)
    {
        // Select the `report_files` field from the `tblservice_request` table
        $this->db->select('report_files');
        $this->db->where('service_request_id', $service_request_id);
        $result = $this->db->get('tblservice_request')->row();

        // Check if `report_files` is not empty and decode the JSON data
        if (!empty($result->report_files)) {
            return json_decode($result->report_files, true); // Decode as an associative array
        }

        return []; // Return an empty array if no files are found
    }

    /**
     * [add_request description]
     * @param [type] $data [description]
     */
    public function add_request_details($data)
    {

        $this->db->insert('tblservice_request_details', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Detail Added [ID:' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * [edit_request description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function edit_request_details($data, $service_request_details_id)
    {
        $this->db->where('service_request_details_id', $service_request_details_id);
        $this->db->update('tblservice_request_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Detail Updated [ID: ' . $service_request_details_id . ']');

            return true;
        }

        return false;
    }


    public function delete_request_details($id = null, $code = null)
    {
        $this->db->where('service_request_details_id', $id);
        $this->db->delete('tblservice_request_details');
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Detail Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * [add_request_calibration description]
     * @param [type] $data [description]
     */
    public function add_request_calibration($data)
    {
        if(isset($data['service_info'])){
            unset($data['service_info']);
        }

        $this->db->insert('tblservices_calibration', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Request Calibration Report Added [ID:' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * [edit_request_calibration description]
     * @param  [type] $data                       [description]
     * @param  [type] $service_request_details_id [description]
     * @return [type]                             [description]
     */
    public function edit_request_calibration($data, $calibration_id)
    {
        $this->db->where('calibration_id', $calibration_id);
        $this->db->update('tblservices_calibration', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Request Calibration Report Updated [ID: ' . $calibration_id . ']');

            return true;
        }

        return false;
    }

    public function get_report_check($id = null)
    {
        $this->db->select('tblservices_calibration.*', false);
        $this->db->from('tblservices_calibration');
        $this->db->where('tblservices_calibration.service_request_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }

    public function get_field_report_check($id = null)
    {
        $this->db->select('tblfield_report.*', false);
        $this->db->from('tblfield_report');
        $this->db->where('tblfield_report.service_rental_agreement_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }


    //REQUEST DETAILS
    public function get_rental_agreement($code = null)
    {
        $this->db->select('tblservice_rental_agreement.*');
        $this->db->from('tblservice_rental_agreement');
        $this->db->where('tblservice_rental_agreement.service_rental_agreement_code', $code);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }

    public function get_rental_agreement_details($service_rental_agreement_id = null)
    {
        $this->db->select('tblservice_rental_agreement_details.*');
        $this->db->from('tblservice_rental_agreement_details');
        $this->db->where('tblservice_rental_agreement_details.service_rental_agreement_id', $service_rental_agreement_id);
        $query_result = $this->db->get();
        $result = $query_result->result();

        return $result;
    }

    /**
     * Add new services
     * @param array $data Product data
     * @return boolean
     */
    public function add_rental_agreement($data)
    {

        $this->db->insert('tblservice_rental_agreement', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service Rental Agreement Added [ID:' . $insert_id . ', ' . $data['service_rental_agreement_code'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update Product
     * @param  array $data Product to update
     * @return boolean
     */
    public function edit_rental_agreement($data)
    {
        $service_rental_agreement_id = $data['service_rental_agreement_id'];
        unset($data['service_rental_agreement_id']);

        $this->db->where('service_rental_agreement_id', $service_rental_agreement_id);
        $this->db->update('tblservice_rental_agreement', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Rental Agreement Updated [ID: ' . $service_rental_agreement_id . ', ' . $data['service_rental_agreement_code'] . ']');

            return true;
        }

        return false;
    }

    /**
     * [add_rental_agreement description]
     * @param [type] $data [description]
     */
    public function add_rental_agreement_details($data)
    {

        $this->db->where('serviceid', $data['serviceid']);
        $this->db->update('tblservices_module', array('rental_status' => 'Hired'));

        $this->db->insert('tblservice_rental_agreement_details', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Service rental_agreement Detail Added [ID:' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * [edit_rental_agreement description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function edit_rental_agreement_details($data, $service_rental_agreement_details_id)
    {
        $serviceID = $this->db->where('service_rental_agreement_details_id', $service_rental_agreement_details_id)->get('tblservice_rental_agreement_details')->row()->serviceid;

        //Set to Hired
        $this->db->where('serviceid', $serviceID);
        $this->db->update('tblservices_module', array('rental_status' => 'Hired'));

        $this->db->where('service_rental_agreement_details_id', $service_rental_agreement_details_id);
        $this->db->update('tblservice_rental_agreement_details', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service rental_agreement Detail Updated [ID: ' . $service_rental_agreement_details_id . ']');

            return true;
        }

        return false;
    }


    public function delete_rental_agreement_details($id = null, $code = null)
    {

        $service_details_arr = $this->db->where('service_rental_agreement_details_id', $id)->get('tblservice_rental_agreement_details')->row();
        //Set to Not-Hired
        if (!empty($service_details_arr)) {
            $this->db->where('serviceid', $service_details_arr->serviceid);
            $this->db->update('tblservices_module', array('rental_status' => 'Not-Hired'));
        }

        $this->db->where('service_rental_agreement_details_id', $id);
        $this->db->delete('tblservice_rental_agreement_details');
        if ($this->db->affected_rows() > 0) {
            log_activity('Service rental_agreement Detail Deleted [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    //calender
    public function get_calendar_rental_details()
    {
        $this->db->select('tblservice_rental_agreement_details.*', false);
        $this->db->select('tblservice_rental_agreement.*', false);
        $this->db->select('tblservices_module.*', false);
        $this->db->from('tblservice_rental_agreement_details');
        $this->db->join('tblservice_rental_agreement', 'tblservice_rental_agreement.service_rental_agreement_id  =  tblservice_rental_agreement_details.service_rental_agreement_id ', 'left');
        $this->db->join('tblservices_module', 'tblservices_module.serviceid  =  tblservice_rental_agreement_details.serviceid ', 'left');


        $this->db->where('tblservice_rental_agreement.status !=', 1);
        $this->db->group_by('tblservices_module.serviceid');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }

    //FIELD REPORT CRUD

    /**
     * [get_field_report description]
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    public function get_field_report($code = null)
    {
        $this->db->select('tblfield_report.*');
        $this->db->from('tblfield_report');
        $this->db->where('tblfield_report.report_code', $code);
        $query_result = $this->db->get();
        $result = $query_result->row();

        return $result;
    }

    /**
     * [add_field_report description]
     * @param [type] $data [description]
     */
    public function add_field_report($data)
    {
        $rental_agreement_code = $data['rental_agreement_code'];
        unset($data['rental_agreement_code']);
        $rental_info = $this->get_rental_agreement($rental_agreement_code);
        $data['service_rental_agreement_id'] = $rental_info->service_rental_agreement_id;
        $data['report_code'] = $rental_agreement_code . '-1';
        $data['clientid'] = $rental_info->clientid;
        $data['added_by'] = get_staff_user_id();
        $this->db->insert('tblfield_report', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New ' . _l('field_report') . ' Added [Field Report ID:' . $insert_id . ' ' . _l('rental_agreement_code') . ' ' .  $data['site_name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * [edit_field_report description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function edit_field_report($data)
    {
        $field_report_id = $data['field_report_id'];
        $added_by = isset($data['added_by']) ? $data['added_by'] : "";
        unset($data['field_report_id']);
        unset($data['added_by']);
        unset($data['DataTables_Table_0_length']);

        if (isset($data['status']) && $data['status'] <= 2) {
            $data['rejected_by'] = null;
            $data['rejection_remarks'] = null;
        }

        if (isset($data['status']) && $data['status'] == 0) {
            $approvers = [];
            if(empty(get_option('field_report_approvers'))){
                $approvers = unserialize(get_option('field_report_approvers'));
            }
            if (empty($approvers)) {
                //TODO: Set Alert
                //return false; 
            }
        }

        $this->db->where('field_report_id', $field_report_id);
        $this->db->update('tblfield_report', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity(_l('field_report') . ' Updated [ID: ' . $field_report_id . ', ' . $data['site_name'] . ']');
            if (isset($data['status'])) {
                if ($data['status'] == 2) {

                    $approvers = unserialize(get_option('field_report_approvers'));

                    if (is_array($approvers)) {
                        foreach ($approvers as $key => $to_user) {
                            rental_agreement_report_notifications($to_user, '', $data['report_code'], 'approval_notice', $data['site_name'], true, true);
                        }
                    } else {
                        log_message('error', 'Invalid data for approvers in Services_model.php, line 724');
                    }
                } else if ($data['status'] == 3) {
                    //Rejected

                    rental_agreement_report_notifications($added_by, $data['rejected_by'], $data['report_code'], 'field_report_rejected', $data['site_name']);

                    foreach (unserialize(get_option('field_report_approvers')) as $key => $to_user) {
                        rental_agreement_report_notifications($to_user, '', $data['report_code'], 'field_report_rejected', $data['site_name'], true);
                    }
                } else if ($data['status'] == 4) {
                    //Approved

                    rental_agreement_report_notifications($added_by, $data['approved_by'], $data['report_code'], 'field_report_approved', $data['site_name']);

                    foreach (unserialize(get_option('field_report_approvers')) as $key => $to_user) {
                        rental_agreement_report_notifications($to_user, '', $data['report_code'], 'field_report_approved', $data['site_name'], true);
                    }
                }
            }

            return true;
        }

        return false;
    }


    public function delete_file($id, $type, $type_id, $file_name)
    {
        if (is_dir(get_upload_path_by_type($type) . $type_id)) {
            if ($id != null) {
                $this->db->where('id', $id);
            } else if ($type_id != null) {
                $this->db->where('rel_id', $type_id);
            }
            $this->db->where('rel_type', $type);
            $this->db->delete('tblfiles');
            if ($id != null) {
                log_activity($type . ' File Deleted [' . $type . 'ID: ' . $type_id . ' - FileID: ' . $id . ']');
                unlink(get_upload_path_by_type($type) . $type_id . '/' . $file_name);
            } else {
                $dirname = get_upload_path_by_type($type) . $type_id;
                array_map('unlink', glob("$dirname/*.*"));
                rmdir($dirname);
            }
            return true;
        }

        return false;
    }

    public function delete_field_report($field_report_id)
    {
        $this->db->where('field_report_id', $field_report_id);
        $this->db->delete('tblfield_report');
        if ($this->db->affected_rows() > 0) {
            log_activity(_l('field_report') . ' Deleted [ID: ' . $field_report_id . ']');
            $this->delete_file('', 'field_report', $field_report_id, ''); //TODO: Implement file deletion logic if needed
            return true;
        }

        return false;
    }
}
