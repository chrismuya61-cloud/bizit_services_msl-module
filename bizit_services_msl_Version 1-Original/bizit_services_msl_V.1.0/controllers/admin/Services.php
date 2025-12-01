<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );

class Services extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('services_model');
		$this->load->model('clients_model');
		$this->load->model('invoices_model');
		//$this->load->helper('bizit_services_msl_helper');
		// $this->load->library('qrcode');


	}

	/* List all available items */
	public function index()
	{

		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') and !is_admin()) {
			access_denied('Services');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services'));;
		}
		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_categories'] = $this->services_model->get_all_services_category_info();
		//echo json_encode($data); exit;
		$data['title'] = _l('services');
		$this->load->view('admin/services/manage', $data);
	}

	public function view_warranty()
	{
		// Check permissions
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view') && !is_admin()) {
			access_denied('Services');
		}

		if ($this->input->post()) {
			$this->load->model('services_model'); // Load your model
			$postData = $this->input->post();

			// Try to insert the warranty data
			$inserted = $this->services_model->add_warranty($postData);

			if ($inserted) {
				// Success flash message
				$this->session->set_flashdata('success', 'Warranty added successfully!');
			} else {
				// Failure flash message
				$this->session->set_flashdata('error', 'Warranty already exists!');
			}

			// Redirect to the same page to display the flash message
			redirect('admin/services/view_warranty');
		}

		// Prepare data for the view
		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_categories'] = $this->services_model->get_all_services_category_info();
		$data['warranty_services'] = $this->services_model->get_warranty_services();
		$data['new_warranty_services'] = $this->services_model->get_new_warranty_services();
		$data['title'] = _l('services');

		$this->load->view('admin/services/manage_warranty', $data);
	}

	public function get_serial_numbers_by_commodity_code()
	{
		$commodity_code = $this->input->post('commodity_code');

		if ($commodity_code) {
			// Load the database
			$this->db->select('tblwh_inventory_serial_numbers.serial_number');
			$this->db->from('tblwh_inventory_serial_numbers');
			$this->db->join('tblitems', 'tblitems.id = tblwh_inventory_serial_numbers.commodity_id');
			$this->db->where('tblitems.commodity_code', $commodity_code);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				echo json_encode($query->result_array());
			} else {
				echo json_encode([]);
			}
		} else {
			echo json_encode([]);
		}
	}

	public function get_service_code()
	{
		$serviceId = $this->input->post('serviceid');
		$this->db->select('service_code');
		$this->db->from('tblservices_module');
		$this->db->where('serviceid', $serviceId);
		$query = $this->db->get();
		$service = $query->row_array();

		echo json_encode($service);
	}


	public function collect_produts()
	{
		$this->load->model('Services_model');

		// Call the get_all_services method. Adjust parameters as needed.
		$service_code = '001'; // Example service code
		$data['service_categories'] = $this->Services_model->get_all_services_warranty($service_code);

		// Load your view
		$this->load->view('admin/services/manage_warranty', $data);
	}


	public function sales_list()
	{

		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_sales_list'));;
		}

		$data['title'] = _l('als_services_sales_list');
		$this->load->view('admin/services/manage_sales_list', $data);
	}

	/* Edit or update services / ajax request /*/
	public function manage()
	{
		if (has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			if ($this->input->post()) {
				$data = $this->input->post();
				if ($data['serviceid'] == '') {
					if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
						header('HTTP/1.0 400 Bad error');
						echo _l('access_denied');
						die;
					}
					//$this->check_if_exist('service_name','service',$data['service_name'],'Service Name');
					$id = $this->services_model->add($data);
					$service = array();
					$success = false;
					$message = '';
					if ($id) {
						$success = true;
						$message = _l('added_successfully', _l('service'));
					}
					echo json_encode(array(
						'success' => $success,
						'message' => $message,
					));
				} else {
					if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
						header('HTTP/1.0 400 Bad error');
						echo _l('access_denied');
						die;
					}
					$success = $this->services_model->edit($data);
					$message = 'Nothing updated!';
					if ($success) {
						$message = _l('updated_successfully', _l('service'));
					}
					echo json_encode(array(
						'success' => $success,
						'message' => $message,
					));
				}
			}
		}
	}

	/* Delete Service*/
	public function delete($id)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) {
			access_denied('Services');
		}

		if (!$id) {
			redirect(admin_url('services'));
		}

		$response = $this->services_model->delete($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('service_lowercase')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('service')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('service_lowercase')));
		}
		redirect(admin_url('services'));
	}


	/**
	 * Delete warranty
	 * @param  mixed $id
	 * @return boolean
	 */
	public function delete_warranty($serial_number)
	{
		// Check if the user has admin privileges
		if (!has_permission(BIZIT_SERVICES_MSL, '_warranty', 'delete')) {
			access_denied('Services');
		}

		// Validate serial number
		if (empty($serial_number)) {
			$this->session->set_flashdata('error', 'Invalid serial number provided.');
			redirect('admin/services/view_warranty');
		}

		// Check if the warranty exists by serial_number
		$warranty = $this->db->select('id')
			->from('tblwarranty')
			->where('serial_number', $serial_number)
			->get()
			->row();

		if ($warranty) {
			// Delete the warranty
			$this->db->where('serial_number', $serial_number)
				->delete('tblwarranty');

			log_activity('Warranty Deleted [Serial Number: ' . $serial_number . ']');

			$this->session->set_flashdata('success', 'Warranty deleted successfully.');
		} else {
			$this->session->set_flashdata('error', 'Warranty not found or unable to delete.');
		}

		// Redirect to the warranty list view
		redirect('admin/services/view_warranty');
	}


	private function is_admin()
	{
		// Check if the current user's group_id matches admin (e.g., 1)
		return isset($_SESSION['group_id']) && $_SESSION['group_id'] == 1;
	}




	/* Edit or update category / ajax request /*/
	public function category_manage()
	{
		if (has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			if ($this->input->post()) {
				$data = $this->input->post();
				if ($data['service_typeid'] == '') {
					if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
						header('HTTP/1.0 400 Bad error');
						echo _l('access_denied');
						die;
					}
					$id = $this->services_model->add_category($data);
					$success = false;
					$message = '';
					if ($id) {
						$success = true;
						$message = _l('added_successfully', _l('service_type'));
					}
					echo json_encode(array(
						'success' => $success,
						'message' => $message,
					));
				} else {
					if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
						header('HTTP/1.0 400 Bad error');
						echo _l('access_denied');
						die;
					}

					$success = false;
					$message = '';

					$success = $this->services_model->edit_category($data);
					if ($success) {
						$message = _l('updated_successfully', _l('service_type'));
					} else {
						$message = _l('no_changes', _l('service_type'));
					}

					echo json_encode(array(
						'success' => $success,
						'message' => $message,
					));
				}
			}
		}
	}
	/* Delete Product Category*/
	public function delete_category($id)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'delete')) {
			access_denied('Services');
		}

		if (!$id) {
			redirect(admin_url('services'));
		}

		$response = $this->services_model->delete_category($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('product_lowercase') . ' category'));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('product') . ' category'));
		} else {
			set_alert('warning', _l('problem_deleting', _l('product_lowercase') . ' category'));
		}
		redirect(admin_url('services'));
	}

	public function service_category()
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/service_category'));;
		}
	}

	/* Change services status / active / inactive */
	public function change_service_status($id, $status)
	{
		if ($this->input->is_ajax_request()) {
			$this->services_model->change_service_status($id, $status);
		}
	}

	/* Change service category status / active / inactive */
	public function change_service_category_status($id, $status)
	{
		if ($this->input->is_ajax_request()) {
			$this->services_model->change_service_category_status($id, $status);
		}
	}

	//Get Services
	public function get_services($id_code)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		$data = array();

		if ($id_code != null):
			$this->db->where('service_type_code', $id_code);
			$data = $this->db->get('tblservices_module')->result();
		endif;

		header('content-type: application/json; charset=utf-8');
		// convert into JSON format and print
		$response = json_encode($data);
		if (isset($_GET['callback'])) {
			echo $_GET['callback'] . "(" . $response . ")";
		} else {
			echo $response;
		}
	}

	//Get Services by code
	public function get_service_by_code($service_code)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		$data = array();

		if ($service_code != null):
			$this->db->select('tblservices_module.*, tblservice_type.name as category_name');
			$this->db->where('service_code', $service_code);
			$this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code');
			$data = $this->db->get('tblservices_module')->row();
		endif;

		header('content-type: application/json; charset=utf-8');
		// convert into JSON format and print
		$response = json_encode($data);
		if (isset($_GET['callback'])) {
			echo $_GET['callback'] . "(" . $response . ")";
		} else {
			echo $response;
		}
	}

	//Get Services by id
	public function get_service_by_id($serviceid)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		$data = array();

		if ($serviceid != null):
			$this->db->select('tblservices_module.*, tblservice_type.name as category_name');
			$this->db->where('serviceid', $serviceid);
			$this->db->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code');
			$data = $this->db->get('tblservices_module')->row();
		endif;

		header('content-type: application/json; charset=utf-8');
		// convert into JSON format and print
		$response = json_encode($data);
		if (isset($_GET['callback'])) {
			echo $_GET['callback'] . "(" . $response . ")";
		} else {
			echo $response;
		}
	}

	//code generators
	public function getNextServiceCode($id_code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		if ($id_code != null):
			$this->db->where('service_type_code', $id_code);
			$q = $this->db->get('tblservices_module')->result();
			$p_service_code = $id_code;

			if ($q) {
				$last = end($q);
				$last = $last->service_code;
				$l_array = explode('-', $last);
				$new_index = end($l_array);
				$new_index += 1;
				$new_index = sprintf("%04d", $new_index);
				echo $p_service_code . "-" . $new_index;
			} else {
				echo $p_service_code . "-" . sprintf("%04d", 1);
			}
		endif;
	}

	//code generators
	public function getNextServiceCategoryCode()
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		$q = $this->db->get('tblservice_type')->result();
		if (!empty($q)) {
			$last = end($q);
			$new_index = $last->type_code;
		}
		$new_index += 1;
		$new_index = sprintf("%03d", $new_index);
		echo $new_index;
	}

	//==========================================================
	//  SERVICE REQUESTS
	//==========================================================

	//Calibration Repair Requests
	public function requests()
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_requests'));;
		}

		$data['title'] = _l('als_services_requests');
		$this->load->view('admin/services/manage_requests', $data);
	}

	public function new_request($flag = null, $code = null)
	{
		// Check if the user has permission to create a service request
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('Services');
		}

		// Redirect if the flag is invalid
		if (!is_null($flag) && !is_numeric($flag)) {
			redirect(admin_url('services/new_request'));
		}

		// If flag is empty, generate a random service request code
		if (empty($flag)) {
			$this->session->unset_userdata(['service_request_code' => '']);
			$random_number = rand(10000000, 99999);

			$q = $this->db->get('tblservice_request')->result();
			if (!empty($q)) {
				$last = end($q);
				$random_number .= $last->service_request_id;
			}

			$this->session->set_userdata(['service_request_code' => $random_number]);
		}

		// Get all available services and currency symbol
		$data['all_services'] = $this->services_model->get_all_services('002');
		$data['currency_symbol'] = get_default_currency('symbol');

		// Fetch all services accessories (accessory type and price)
		$data['all_services_accessories'] = $this->services_model->get_all_services_accessories();

		// If a code is provided, load the service request and related data
		if ($code != null) {
			$data['request'] = $this->services_model->get_request($code);

			// Check if the request exists
			if (empty($data['request'])) {
				show_error('Invalid service request code provided.');
			}

			// Get the request details and related data
			$data['request_details'] = $this->services_model->get_request_details($data['request']->service_request_id);
			$data['service_file_info'] = $data['request'];
			$data['uploaded_files'] = $this->services_model->get_service_files($data['request']->service_request_id) ?? [];

			// Fetch inspection data
			$data['inspection_data'] = $this->services_model->get_inspection_data($data['request']->service_request_id);

			// Fetch checklist data
			$data['checklist_items'] = $this->services_model->get_checklist_data($data['request']->service_request_id);

			// Fetch collection data
			$data['collection_data'] = $this->services_model->get_collection_data($data['request']->service_request_id);

			// Set pre-filled values for dropped-off and received-by details
			$data['dropped_off_by'] = $data['request']->dropped_off_by;
			$data['dropped_off_date'] = $data['request']->dropped_off_date;
			$data['dropped_off_signature'] = $data['request']->dropped_off_signature;
			$data['dropped_off_id_number'] = $data['request']->dropped_off_id_number;
			$data['req_received_by'] = $data['request']->req_received_by;
			$data['received_date'] = $data['request']->received_date;
			$data['received_signature'] = $data['request']->received_signature;
			$data['received_id_number'] = $data['request']->received_id_number;

			// Separate pre-inspection and post-inspection items
			$data['pre_inspection_items'] = [];
			$data['post_inspection_items'] = [];
			if (!empty($data['inspection_data'])) {
				foreach ($data['inspection_data'] as $inspection) {
					if (isset($inspection['inspection_type'])) {
						if ($inspection['inspection_type'] == 'pre_inspection') {
							$data['pre_inspection_items'][] = $inspection;
						} elseif ($inspection['inspection_type'] == 'post_inspection') {
							$data['post_inspection_items'][] = $inspection;
						}
					} elseif (isset($inspection->inspection_type)) {
						if ($inspection->inspection_type == 'pre_inspection') {
							$data['pre_inspection_items'][] = $inspection;
						} elseif ($inspection->inspection_type == 'post_inspection') {
							$data['post_inspection_items'][] = $inspection;
						}
					}
				}
			}

			// Set the service request code in session
			$this->session->set_userdata(['service_request_code' => $data['request']->service_request_code]);

			// Fetch existing accessories for the service request
			$existing_accessories = $this->services_model->get_request_accessories($data['request']->service_request_id);
			$data['existing_accessories'] = $existing_accessories; // Set the page title and load the view
		}

		$data['title'] = _l('add_service_request');
		$this->load->view('admin/services/service_requests_form', $data);
	}


	public function save_request()
	{
		// Check permissions
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('Services');
		}

		// Gather form data
		$data_service = [
			'service_request_code'  => $this->input->post('service_request_code', true),
			'clientid'              => $this->input->post('clientid', true),
			'drop_off_date'         => to_sql_date($this->input->post('drop_off_date', true)),
			'collection_date'       => to_sql_date($this->input->post('collection_date', true)),
			'condition'             => $this->input->post('condition', true),
			'item_type'             => $this->input->post('type', true),
			'item_model'            => $this->input->post('model', true),
			'item_make'             => $this->input->post('make', true),
			'serial_no'             => $this->input->post('serial_no', true),
			'service_note'          => strip_tags($this->input->post('service_note', true)),
			'received_by'           => get_staff_user_id(),
			'dropped_off_by'        => $this->input->post('dropped_off_by', true),
			'dropped_off_date'      => to_sql_date($this->input->post('dropped_off_date', true)),
			'dropped_off_signature' => $this->input->post('dropped_off_signature', true),
			'dropped_off_id_number' => $this->input->post('dropped_off_id_number', true),
			'req_received_by'       => $this->input->post('req_received_by', true),
			'received_date'         => to_sql_date($this->input->post('received_date', true)),
			'received_signature'    => $this->input->post('received_signature', true),
			'received_id_number'    => $this->input->post('received_id_number', true)
		];

		// Get accessory data
		$submitted_accessory_ids = $this->input->post('accessoryserviceid', true);
		$submitted_accessory_prices = $this->input->post('accessoryservice_price', true);

		// Get additional data for editing
		$serviceid = $this->input->post('serviceid', true);
		$service_price = $this->input->post('service_price', true);
		$edit_id = $this->input->post('edit_id', true);
		$service_request_details_id_edit = $this->input->post('service_request_details_id', true);

		// Handle file uploads
		$uploaded_files = $this->handle_file_uploads();

		// Store uploaded files in JSON format
		$data_service['report_files'] = json_encode($uploaded_files, JSON_PRETTY_PRINT);

		// Insert or update service request
		$service_request_id = $this->save_or_update_service_request($data_service, $edit_id);

		// Handle inspection data
		$this->handle_inspection_data($service_request_id);

		// Handle checklist and collection data
		$this->handle_checklist_and_collection_data($service_request_id);

		// Process service request details
		if ($serviceid && $service_price) {
			$this->process_service_request_details($serviceid, $service_price, $service_request_details_id_edit, $edit_id ?? $service_request_id);
		}
		// Ensure $submitted_accessory_ids is an array
		$submitted_accessory_ids = !empty($submitted_accessory_ids) ? $submitted_accessory_ids : [];

		// Fetch existing accessory IDs for this service request
		$this->db->select('accessory_id');
		$this->db->from('tblservice_request_accessories');
		$this->db->where('service_request_id', $service_request_id);
		$existing_accessories = $this->db->get()->result_array();

		// Extract accessory IDs into a simple array
		$existing_accessory_ids = !empty($existing_accessories) ? array_column($existing_accessories, 'accessory_id') : [];

		// Determine which accessories to delete
		$accessories_to_delete = array_diff($existing_accessory_ids, $submitted_accessory_ids);

		// Delete accessories not present in the submitted data
		if (!empty($accessories_to_delete)) {
			$this->db->where('service_request_id', $service_request_id);
			$this->db->where_in('accessory_id', $accessories_to_delete);
			$this->db->delete('tblservice_request_accessories');
		}
		// Insert new accessories
		if (!empty($submitted_accessory_ids)) {
			foreach ($submitted_accessory_ids as $index => $accessory_id) {
				if (!in_array($accessory_id, $existing_accessory_ids)) {
					$accessory_data = [
						'service_request_id' => $service_request_id,
						'accessory_id'       => $accessory_id,
						'price'              => $submitted_accessory_prices[$index]
					];
					$this->db->insert('tblservice_request_accessories', $accessory_data);
				}
			}
		}

		// Redirect with success message
		set_alert('success', $edit_id ? 'Service request updated successfully' : 'Service request added successfully');
		redirect(admin_url('services/view_request/' . $data_service['service_request_code']), 'refresh');
	}



	private function handle_file_uploads()
	{
		$uploaded_files = [];

		// Process existing files (from hidden inputs)
		if (!empty($this->input->post('report_files'))) {
			$existing_files = array_filter($this->input->post('report_files'), fn($file) => !empty($file));
			$uploaded_files = array_merge($existing_files, $uploaded_files);
		}

		// Configure file upload settings
		$config = [
			'upload_path'   => './modules/bizit_services_msl/uploads/reports/',
			'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx',
			'max_size'      => 4096, // Max file size in KB (4MB)
		];
		$this->load->library('upload', $config);

		// Process new service files (from file input fields)
		if (!empty($_FILES['service_files'])) {
			$this->load->library('upload');
			$files = $_FILES['service_files'];

			for ($i = 0; $i < count($files['name']); $i++) {
				if ($files['name'][$i] != '') {
					$_FILES['file'] = [
						'name'     => $files['name'][$i],
						'type'     => $files['type'][$i],
						'tmp_name' => $files['tmp_name'][$i],
						'error'    => $files['error'][$i],
						'size'     => $files['size'][$i],
					];

					if ($this->upload->do_upload('file')) {
						$file_data = $this->upload->data();
						$uploaded_files[] = $file_data['file_name'];
					} else {
						set_alert('danger', 'File upload failed');
						redirect(admin_url('services/new_request'));
					}
				}
			}
		}

		return $uploaded_files;
	}

	private function save_or_update_service_request($data_service, $edit_id)
	{
		if ($edit_id) {
			$data_service['service_request_id'] = $edit_id;
			$this->services_model->edit_request($data_service);
			return $edit_id;
		} else {
			$this->db->insert('tblservice_request', $data_service);
			return $this->db->insert_id();
		}
	}

	// private function handle_inspection_data($service_request_id) 
	// {
	// 	// Pre-inspection and post-inspection data
	// 	$pre_inspection_items = $this->input->post('inspection_items', true);
	// 	$pre_remarks = $this->input->post('remarks', true);
	// 	$post_inspection_items = $this->input->post('postinspection_items', true);
	// 	$post_remarks = $this->input->post('postremarks', true);

	// 	// Removed items
	// 	$removed_pre_items = $this->input->post('removed_pre_items', true);
	// 	$removed_post_items = $this->input->post('removed_post_items', true);

	// 	if ($service_request_id) {
	// 		// Handle removed pre-inspection items
	// 		if ($removed_pre_items) {
	// 			$removed_pre_items = explode(',', $removed_pre_items);
	// 			$this->remove_inspection_items($service_request_id, $removed_pre_items, 'Pre Inspection');
	// 		}

	// 		// Handle removed post-inspection items
	// 		if ($removed_post_items) {
	// 			$removed_post_items = explode(',', $removed_post_items);
	// 			$this->remove_inspection_items($service_request_id, $removed_post_items, 'Post Inspection');
	// 		}

	// 		// Save pre-inspection items
	// 		if (!empty($pre_inspection_items)) {
	// 			$this->handle_inspection_items($service_request_id, $pre_inspection_items, $pre_remarks, 'Pre Inspection');
	// 		}

	// 		// Save post-inspection items
	// 		if (!empty($post_inspection_items)) {
	// 			$this->handle_inspection_items($service_request_id, $post_inspection_items, $post_remarks, 'Post Inspection');
	// 		}
	// 	}
	// }

	private function handle_inspection_data($service_request_id)
	{
		// Pre-inspection and post-inspection data
		$pre_inspection_items = $this->input->post('inspection_items', true);
		$pre_remarks = $this->input->post('remarks', true);
		$post_inspection_items = $this->input->post('postinspection_items', true);
		$post_remarks = $this->input->post('postremarks', true);

		// Removed items
		$removed_pre_items = $this->input->post('removed_pre_items', true);
		$removed_post_items = $this->input->post('removed_post_items', true);

		if ($service_request_id) {
			// Handle removed pre-inspection items
			if ($removed_pre_items) {
				$removed_pre_items = explode(',', $removed_pre_items);
				$this->remove_inspection_items($service_request_id, $removed_pre_items, 'pre_inspection');
			}

			// Handle removed post-inspection items
			if ($removed_post_items) {
				$removed_post_items = explode(',', $removed_post_items);
				$this->remove_inspection_items($service_request_id, $removed_post_items, 'post_inspection');
			}

			// Save pre-inspection items
			if (!empty($pre_inspection_items)) {
				$this->handle_inspection_items($service_request_id, $pre_inspection_items, $pre_remarks, 'pre_inspection');
			}

			// Save post-inspection items
			if (!empty($post_inspection_items)) {
				$this->handle_inspection_items($service_request_id, $post_inspection_items, $post_remarks, 'post_inspection');
			}
		}
	}

	// Function to remove inspection items from the database
	private function remove_inspection_items($service_request_id, $items, $type)
	{
		if (!empty($items)) {
			// Print debug info
			log_message('debug', 'Removing items: ' . implode(',', $items)); // This line logs to the CodeIgniter log
			$this->db->where_in('inspection_item', $items);
			$this->db->where('service_request_id', $service_request_id);
			$this->db->where('inspection_type', $type);
			$this->db->delete('tblinspection_requests');
		}
	}


	private function handle_inspection_items($service_request_id, $inspection_items, $remarks, $type)
	{
		foreach ($inspection_items as $item) {
			$data = [
				'service_request_id' => $service_request_id,
				'inspection_item'    => $item,
				'remarks_condition'  => isset($remarks[$item]) ? $remarks[$item] : null,
				'inspection_type'    => $type, // Pre or post inspection
			];

			// Check if the item already exists
			$existing = $this->db->get_where('tblinspection_requests', [
				'service_request_id' => $service_request_id,
				'inspection_item'    => $item,
				'inspection_type'    => $type,
			])->row();

			if ($existing) {
				// Update existing record
				$this->db->update('tblinspection_requests', $data, ['id' => $existing->id]);
			} else {
				// Insert new record
				$this->db->insert('tblinspection_requests', $data);
			}
		}
	}


	private function process_service_request_details($serviceid, $service_price, $service_request_details_id_edit, $service_request_id)
	{
		for ($i = 0; $i < count($serviceid); $i++) {
			if (!empty($serviceid[$i]) && !empty($service_price[$i])) {
				$data_service_details = [
					'service_request_id' => $service_request_id,
					'serviceid'          => $serviceid[$i],
					'price'              => $service_price[$i],
				];

				if (!empty($service_request_details_id_edit[$i])) {
					$this->services_model->edit_request_details($data_service_details, $service_request_details_id_edit[$i]);
				} else {
					$this->services_model->add_request_details($data_service_details);
				}
			}
		}
	}

	public function delete_accessory()
	{
		$id = $this->input->post('id', true); // Get ID from AJAX

		if ($id) {
			$this->db->where('id', $id);
			$deleted = $this->db->delete('tblservice_request_accessories');

			if ($deleted) {
				echo json_encode(['success' => true, 'message' => 'Accessory deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete accessory']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid accessory ID']);
		}
	}


	public function handle_checklist_and_collection_data($service_request_id)
	{
		// Get checklist data
		$item_status = $this->input->post('item_status', true); // Array of statuses (X/√)
		$checklist_items = ['Calibration Certificate Issued', 'Calibration Sticker Issued', 'Date of Next Calibration Advised', 'Equipment in Good Condition'];

		// Fetch existing checklist data from the database
		if ($service_request_id) {
			foreach ($checklist_items as $index => $item) {
				$existing_checklist = $this->db->get_where('tblchecklist1', ['service_request_id' => $service_request_id, 'item' => $item])->row();
				$checklist_items[$index] = [
					'item' => $item,
					'status' => $existing_checklist ? $existing_checklist->status : null
				];
			}
		}

		// Update or insert checklist data (existing code)
		if ($item_status && $service_request_id) {
			foreach ($checklist_items as $index => $item) {
				$data = [
					'service_request_id' => $service_request_id,
					'item'               => $item['item'],
					'status'             => $item_status[$index],
				];

				$existing_checklist = $this->db->get_where('tblchecklist1', ['service_request_id' => $service_request_id, 'item' => $item['item']])->row();

				if ($existing_checklist) {
					$this->db->update('tblchecklist1', $data, ['id' => $existing_checklist->id]);
				} else {
					$this->db->insert('tblchecklist1', $data);
				}
			}
		}

		// Passing checklist items to the view
		$data['checklist_items'] = $checklist_items;

		// Update collection data
		$collection_data = [
			'service_request_id'   => $service_request_id,
			'released_by'          => $this->input->post('released_by', true),
			'released_date'        => to_sql_date($this->input->post('released_date', true)),
			'released_id_number'   => $this->input->post('released_id_number', true),
			'collected_by'         => $this->input->post('collected_by', true),
			'collected_date'       => to_sql_date($this->input->post('collected_date', true)),
			'collected_id_number'  => $this->input->post('collected_id_number', true),
		];

		// Check if the collection record exists
		$existing_collection = $this->db->get_where('tblcollection1', ['service_request_id' => $service_request_id])->row();
		if ($existing_collection) {
			// Update existing collection data
			$this->db->update('tblcollection1', $collection_data, ['service_request_id' => $service_request_id]);
		} else {
			// Insert new collection data
			$this->db->insert('tblcollection1', $collection_data);
		}

		// Passing checklist items to the view
		$data['checklist_items'] = $checklist_items;

		// Return or load the view as needed
		return $data; // or load view as required

	}

	public function get_service_accessory_by_id($id)
	{
		// Load model (if not autoloaded)
		$this->load->model('services_model');

		// Fetch the rate using the model
		$result = $this->services_model->get_service_accessory_by_id($id);

		// Check if a result was found
		if ($result) {
			echo json_encode(['success' => true, 'rate' => $result->rate]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Item not found']);
		}
		exit;
	}

	/*** Delete service price ***/
	public function delete_service_price($id, $code)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}
		$deleted = $this->services_model->delete_request_details($id, $code);
		if ($deleted) {
			set_alert('success', 'Successfully deleted service');
		} else {
			set_alert('warning', 'Failed to delete service');
		}
		redirect(admin_url('services/new_request/1/' . $code));
	}

	public function view_request($code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		// Get service request info
		$data['service_info'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		if (empty($data['service_info'])) {
			// Redirect to manage requests if no data found
			redirect(admin_url('services/manage_requests'));
		}

		// Extract Dropped Off and Received Information
		$data['dropped_off_by'] = $data['service_info']->dropped_off_by ?? 'N/A';
		$data['dropped_off_date'] = $data['service_info']->dropped_off_date ?? 'N/A';
		$data['dropped_off_signature'] = $data['service_info']->dropped_off_signature ?? 'N/A';
		$data['dropped_off_id_number'] = $data['service_info']->dropped_off_id_number ?? 'N/A';
		$data['received_by'] = $data['service_info']->req_received_by ?? 'N/A';
		$data['received_date'] = $data['service_info']->received_date ?? 'N/A';
		$data['received_signature'] = $data['service_info']->received_signature ?? 'N/A';
		$data['received_id_number'] = $data['service_info']->received_id_number ?? 'N/A';

		// Get request details
		$data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')
			->where('service_request_id', $data['service_info']->service_request_id)
			->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')
			->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
			->get('tblservice_request_details')->result();

		// Fetch inspection items
		$inspection_items = $this->db->where('service_request_id', $data['service_info']->service_request_id)->get('tblinspection_requests')->result();
		$data['pre_inspection_items'] = [];
		$data['post_inspection_items'] = [];
		foreach ($inspection_items as $item) {
			if ($item->inspection_type == 'pre_inspection') {
				$data['pre_inspection_items'][] = $item;
			} elseif ($item->inspection_type == 'post_inspection') {
				$data['post_inspection_items'][] = $item;
			}
		}

		// Fetch checklist items
		$data['checklist_items'] = $this->db->where('service_request_id', $data['service_info']->service_request_id)
			->get('tblchecklist1')->result();

		// Fetch collection (released by) information
		$data['released_info'] = $this->db->where('service_request_id', $data['service_info']->service_request_id)
			->get('tblcollection1')->row();

		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);

		$data['existing_accessories'] = $this->db
			->select('tblservice_request_accessories.id, tblitems.description, tblservice_request_accessories.price')
			->from('tblservice_request_accessories')
			->join('tblitems', 'tblitems.id = tblservice_request_accessories.accessory_id', 'left')
			->where('tblservice_request_accessories.service_request_id', $data['service_info']->service_request_id)
			->get()
			->result();

		$data['title'] = 'Service Request View';
		$this->load->view('admin/services/view_request', $data);
	}

	/*** Service Request Reconfirmation  ***/
	public function service_re_confirmation()
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}
		$data['status'] = $this->input->post('status', true);

		$service_request_id = $this->input->post('service_request_id', true);
		$service_request_code = $this->input->post('service_request_code', true);

		if ($data['status'] == 3 or $data['status'] == 2 or $data['status'] == 1 or $data['status'] == 0) {
			//cancel service
			$this->db->where('service_request_id', $service_request_id)->update('tblservice_request', $data);
		}

		if ($this->db->affected_rows() > 0) {
			set_alert('success', 'Status change successful');
			redirect(admin_url('services/view_request/' . $service_request_code));
		}
	}

	// PDF Report with qr code generation logic implemented

	public function report($flag = null, $code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}


		if (empty($flag)) {
			redirect(admin_url('services/requests'));
		}

		if ($flag == '1') {
			$service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
			$data['service_request_id'] = $service_info->service_request_id;
			$data['calibration_instrument'] = $service_info->item_type;
			$data['service_info'] = $service_info;
			

			if ($this->services_model->add_request_calibration($data)) {
				set_alert('success', 'New Service Calibration Report Added successfully');
				redirect(admin_url('services/report/edit/' . $code));
			}
		}


		if ($flag == 'edit' or $flag == 'view' or $flag == 'pdf') {
			if (empty($code)) {
				redirect(admin_url('services/requests'));
			}
			$service_info = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
			$data['service_request_id'] = $service_info->service_request_id;
			$data['calibration_info'] = $this->db->where(array('service_request_id' => $data['service_request_id']))->get('tblservices_calibration')->row();
			$data['service_info'] = $service_info;
			$data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
			$data['service_request_code'] = $code;
		} else {
			if (!is_numeric($flag)) {
				redirect(admin_url('services/requests'));
			}
			$data['service_info'] = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
			$data['service_request_code'] = $code;
			// Request details
			$data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')
				->where('service_request_id', $data['service_info']->service_request_id)
				->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')
				->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
				->get('tblservice_request_details')->result();
		}

		// View logic
		if ($flag == 'view') {
			$data['title'] = 'View Service Calibration Report';
			$this->load->view('admin/services/report_calibration_view', $data);

			// PDF logic
		} else if ($flag == 'pdf') {
			$data['title'] = 'Service Calibration Report PDF';
			$request_number = get_option('service_request_prefix') . $code;


			// Generate QR code logic (common for both view and pdf)
			$qr_data = "This Service Report Registration No. REQ-{$code} is a valid report from Measurement Systems Ltd.";
			$this->load->library('ciqrcode');
			$params['data'] = $qr_data;
			$params['level'] = 'L';
			$params['size'] = 2;
			$qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
			$params['savename'] = $qr_image_path;
			$this->ciqrcode->generate($params);


			//echo '<pre>'.json_encode($data); die;

			// Start output buffering before PDF generation
			ob_start();

			try {
				$pdf = service_request_report_pdf($data); // Include $data with QR code
			} catch (Exception $e) {
				log_message('error', 'Error generating PDF with service_request_report_pdf: ' . $e->getMessage());
			}

			$type = 'I';
			if ($this->input->get('print')) {
				$type = 'I';
			}

			$pdf->Output('SERVICE REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);

			// End and clean the output buffer after PDF generation
			// ob_end_clean();
		} else if ($flag == 'edit') {
			$data['title'] = 'Edit Service Calibration Report';
			$this->load->view('admin/services/new_report', $data);
		} else {
			if (!in_array($data['service_info']->item_type, services_model::CALIBRATION_ITEMS)) {
				redirect(admin_url('services/requests'));
			}
			$data['title'] = 'Add New Service Calibration Report';
			$this->load->view('admin/services/new_report', $data);
		}
	}

	// PDF rental angreement pdf Report with qr code generation logic implemented
	public function field_report($flag = null, $code = null, $approval = false)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement_field_report');
		}

		if ($this->input->is_ajax_request() && isset($code)) {
			for ($index = 1; $index < 20; $index++) {
				if (total_rows('tblfield_report', ['report_code' => $code . '-' . $index]) == 0) {
					echo $code . '-' . $index;
					exit;
				}
			}
		} else {
			if (empty($flag)) {
				redirect(admin_url('services/rental_agreements'));
			}

			if (in_array($flag, ['1', 'edit', 'view', 'pdf'])) {
				if (empty($code)) {
					redirect(admin_url('services/rental_agreements'));
				}

				if ($flag == '1') {
					if ($this->services_model->add_field_report(['rental_agreement_code' => $code])) {
						set_alert('success', 'Field report created successfully');
						redirect(admin_url('services/field_report/edit/' . $code . '-1'));
					}
				}

				$data['field_report_info'] = $this->services_model->get_field_report($code);

				// Debugging step - check if 'field_report_info' is retrieved correctly
				if (empty($data['field_report_info'])) {
					log_message('error', 'Field report not found for code: ' . $code);
					redirect(admin_url('services/rental_agreements'));
				}

				$code_arr = explode('-', $code);

				$data['service_request_client'] = $this->clients_model->get($data['field_report_info']->clientid);
				$data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code_arr[0])->get('tblservice_rental_agreement')->row();
				$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')
					->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)
					->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')
					->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
					->get('tblservice_rental_agreement_details')->result();


				$data['service_info'] = $this->services_model->get_rental_agreement($code_arr[0]);

				// Debugging step - check if service_rental_agreement_id is retrieved correctly
				log_message('debug', 'Field report info: ' . print_r($data['field_report_info'], true));

				// Fetching the service details with the correct ID
				$service_rental_agreement_id = $data['field_report_info']->service_rental_agreement_id;
				if (!empty($service_rental_agreement_id)) {
					// Call the get_service_details method
					$data['service_details'] = $this->services_model->get_service_details($service_rental_agreement_id);
				} else {
					log_message('error', 'Service rental agreement ID not found in field report.');
					$data['service_details'] = null;  // Handle the error appropriately
				}

				// Additional check - ensure 'service_details' is retrieved correctly
				log_message('debug', 'Service details: ' . print_r($data['service_details'], true));
			} else {
				redirect(admin_url('services/rental_agreements'));
			}

			if ($flag == 'view') {
				$data['title'] = 'View Service Field Report';
				$data['report_approval'] = ($approval && in_array(get_staff_user_id(), unserialize(get_option('field_report_approvers'))));
				$this->load->view('admin/services/field_report_view', $data);
			} else if ($flag == 'pdf') {
				// Ensure service details are included in PDF generation
				$data['title'] = 'Service Calibration Report PDF';
				$request_number = get_option('service_rental_agreement_prefix') . $code;
				$data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();


				// Prepare data for PDF
				$data['service_rental_agreement'] = $data['service_info']; // Rental agreement information
				$data['service_rental_agreement_client'] = $data['service_request_client']; // Client information
				$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();
				$data['field_report_info'] = $this->services_model->get_field_report($code);
				$data['service_rental_request_code'] = $code;

				// Generate QR code logic (common for both view and pdf)
				$qr_data = "This Rental Agreement Report Registration No. ARG-{$code} is a valid report from Measurement Systems Ltd.";
				$this->load->library('ciqrcode');
				$params['data'] = $qr_data;
				$params['level'] = 'L';
				$params['size'] = 2;
				$qr_image_path = FCPATH . 'modules/wonder_pdf_template/assets/images/' . $code . '_qrcode.png';
				$params['savename'] = $qr_image_path;
				$this->ciqrcode->generate($params);

				if (file_exists($qr_image_path)) {
					$qr_code_base64 = base64_encode(file_get_contents($qr_image_path));
					$data['qr_code_base64'] = $qr_code_base64;
				} else {
					log_message('error', 'QR code image not found: ' . $qr_image_path);
					$data['qr_code_base64'] = '';
				}

				// Start PDF generation
				ob_start();
				try {
					$pdf = service_rental_agreement_pdf($data); // Pass all required data
				} catch (Exception $e) {
					log_message('error', 'Error generating PDF with service_rental_agreement_pdf: ' . $e->getMessage());
				}

				$type = 'I';
				if ($this->input->get('print')) {
					$type = 'I';
				}

				log_message('debug', print_r($data, true)); // Log data to debug

				$pdf->Output('RENTAL FIELD REPORT ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
			} else {
				$data['title'] = 'Edit Service Field Report ' . get_option('service_rental_agreement_prefix') . $code;
				$this->load->view('admin/services/new_field_report', $data);
			}
		}
	}

	/*** Save request ***/
	public function save_calibration()
	{

		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create') and !has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}

		$calibration_instrument = $this->input->post('calibration_instrument', true);

		//Total Station or Theodolite
		if ($calibration_instrument == 'Total Station'):

			$data['i_edm_a_1'] = $this->input->post('i_edm_a_1');
			$data['i_edm_a_2'] = $this->input->post('i_edm_a_2');
			$data['i_edm_a_3'] = $this->input->post('i_edm_a_3');
			$data['i_edm_a_4'] = $this->input->post('i_edm_a_4');
			$data['i_edm_a_5'] = $this->input->post('i_edm_a_5');
			$data['i_edm_a_6'] = $this->input->post('i_edm_a_6');
			$data['i_edm_a_7'] = $this->input->post('i_edm_a_7');
			$data['i_edm_a_8'] = $this->input->post('i_edm_a_8');
			$data['i_edm_a_9'] = $this->input->post('i_edm_a_9');
			$data['i_edm_a_10'] = $this->input->post('i_edm_a_10');
			$data['i_edm_a_11'] = $this->input->post('i_edm_a_11');
			$data['i_edm_a_12'] = $this->input->post('i_edm_a_12');
			$data['i_edm_a_13'] = $this->input->post('i_edm_a_13');
			$data['i_edm_a_14'] = $this->input->post('i_edm_a_14');
			$data['i_edm_a_15'] = $this->input->post('i_edm_a_15');
			$data['i_edm_a_16'] = $this->input->post('i_edm_a_16');
			$data['i_edm_a_17'] = $this->input->post('i_edm_a_17');
			$data['i_edm_a_18'] = $this->input->post('i_edm_a_18');
			$data['i_edm_a_19'] = $this->input->post('i_edm_a_19');
			$data['i_edm_a_20'] = $this->input->post('i_edm_a_20');
			$data['i_edm_a_21'] = $this->input->post('i_edm_a_21');
			$data['i_edm_a_22'] = $this->input->post('i_edm_a_22');
			$data['i_edm_a_23'] = $this->input->post('i_edm_a_23');
			$data['i_edm_a_24'] = $this->input->post('i_edm_a_24');
			$data['i_edm_a_25'] = $this->input->post('i_edm_a_25');
			$data['i_edm_a_26'] = $this->input->post('i_edm_a_26');
			$data['i_edm_a_27'] = $this->input->post('i_edm_a_27');
			$data['i_edm_a_28'] = $this->input->post('i_edm_a_28');
			$data['i_edm_a_29'] = $this->input->post('i_edm_a_29');
			$data['i_edm_a_30'] = $this->input->post('i_edm_a_30');
			$data['i_edm_a_31'] = $this->input->post('i_edm_a_31');
			$data['i_edm_a_32'] = $this->input->post('i_edm_a_32');
			$data['i_edm_a_33'] = $this->input->post('i_edm_a_33');
			$data['i_edm_a_34'] = $this->input->post('i_edm_a_34');
			$data['i_edm_a_35'] = $this->input->post('i_edm_a_35');
			$data['i_edm_a_36'] = $this->input->post('i_edm_a_36');
			$data['i_edm_a_37'] = $this->input->post('i_edm_a_37');
			$data['i_edm_a_38'] = $this->input->post('i_edm_a_38');
			$data['i_edm_a_39'] = $this->input->post('i_edm_a_39');
			$data['i_edm_a_40'] = $this->input->post('i_edm_a_40');
			$data['i_edm_a_41'] = $this->input->post('i_edm_a_41');
			$data['i_edm_a_42'] = $this->input->post('i_edm_a_42');
			$data['i_edm_a_43'] = $this->input->post('i_edm_a_43');
			$data['i_edm_a_44'] = $this->input->post('i_edm_a_44');
			$data['i_edm_a_45'] = $this->input->post('i_edm_a_45');
			$data['i_edm_a_46'] = $this->input->post('i_edm_a_46');
			$data['i_edm_a_47'] = $this->input->post('i_edm_a_47');
			$data['i_edm_a_48'] = $this->input->post('i_edm_a_48');

			$data['t_v_a_1'] = $this->input->post('t_v_a_1');
			$data['t_v_a_2'] = $this->input->post('t_v_a_2');
			$data['t_v_a_3'] = $this->input->post('t_v_a_3');
			$data['t_v_a_4'] = $this->input->post('t_v_a_4');
			$data['t_v_a_5'] = $this->input->post('t_v_a_5');
			$data['t_v_a_6'] = $this->input->post('t_v_a_6');
			$data['t_v_a_7'] = $this->input->post('t_v_a_7');
			$data['t_v_a_8'] = $this->input->post('t_v_a_8');
			$data['t_v_a_9'] = $this->input->post('t_v_a_9');
			$data['t_v_a_10'] = $this->input->post('t_v_a_10');
			$data['t_v_a_11'] = $this->input->post('t_v_a_11');
			$data['t_v_a_12'] = $this->input->post('t_v_a_12');
			$data['t_v_a_13'] = $this->input->post('t_v_a_13');
			$data['t_v_a_14'] = $this->input->post('t_v_a_14');
			$data['t_v_a_15'] = $this->input->post('t_v_a_15');
			$data['t_v_a_16'] = $this->input->post('t_v_a_16');


			$i_h_a = $this->input->post('i_h_a');
			$i_h_b = $this->input->post('i_h_b[]');
			$ii_h_a = $this->input->post('ii_h_a');
			$ii_h_b = $this->input->post('ii_h_b');
			$i_v_a = $this->input->post('i_v_a');
			$i_v_b = $this->input->post('i_v_b');
			$ii_v_a = $this->input->post('ii_v_a');
			$ii_v_b = $this->input->post('ii_v_b');

			$data['i_h_a'] = dms2dec($i_h_a[0], $i_h_a[1], $i_h_a[2]);
			$data['i_h_b'] = dms2dec($i_h_b[0], $i_h_b[1], $i_h_b[2]);
			$data['ii_h_a'] = dms2dec($ii_h_a[0], $ii_h_a[1], $ii_h_a[2]);
			$data['ii_h_b'] = dms2dec($ii_h_b[0], $ii_h_b[1], $ii_h_b[2]);
			$data['i_v_a'] = dms2dec($i_v_a[0], $i_v_a[1], $i_v_a[2]);
			$data['i_v_b'] = dms2dec($i_v_b[0], $i_v_b[1], $i_v_b[2]);
			$data['ii_v_a'] = dms2dec($ii_v_a[0], $ii_v_a[1], $ii_v_a[2]);
			$data['ii_v_b'] = dms2dec($ii_v_b[0], $ii_v_b[1], $ii_v_b[2]);

			// Postcalibration data
			$data['i_edm_a_49'] = $this->input->post('i_edm_a_49');
			$data['i_edm_a_50'] = $this->input->post('i_edm_a_50');
			$data['i_edm_a_51'] = $this->input->post('i_edm_a_51');
			$data['i_edm_a_52'] = $this->input->post('i_edm_a_52');
			$data['i_edm_a_53'] = $this->input->post('i_edm_a_53');
			$data['i_edm_a_54'] = $this->input->post('i_edm_a_54');
			$data['i_edm_a_55'] = $this->input->post('i_edm_a_55');
			$data['i_edm_a_56'] = $this->input->post('i_edm_a_56');
			$data['i_edm_a_57'] = $this->input->post('i_edm_a_57');
			$data['i_edm_a_58'] = $this->input->post('i_edm_a_58');
			$data['i_edm_a_59'] = $this->input->post('i_edm_a_59');
			$data['i_edm_a_60'] = $this->input->post('i_edm_a_60');
			$data['i_edm_a_61'] = $this->input->post('i_edm_a_61');
			$data['i_edm_a_62'] = $this->input->post('i_edm_a_62');
			$data['i_edm_a_63'] = $this->input->post('i_edm_a_63');
			$data['i_edm_a_64'] = $this->input->post('i_edm_a_64');
			$data['i_edm_a_65'] = $this->input->post('i_edm_a_65');
			$data['i_edm_a_66'] = $this->input->post('i_edm_a_66');
			$data['i_edm_a_67'] = $this->input->post('i_edm_a_67');
			$data['i_edm_a_68'] = $this->input->post('i_edm_a_68');
			$data['i_edm_a_69'] = $this->input->post('i_edm_a_69');
			$data['i_edm_a_70'] = $this->input->post('i_edm_a_70');
			$data['i_edm_a_71'] = $this->input->post('i_edm_a_71');
			$data['i_edm_a_72'] = $this->input->post('i_edm_a_72');
			$data['i_edm_a_73'] = $this->input->post('i_edm_a_73');
			$data['i_edm_a_74'] = $this->input->post('i_edm_a_74');
			$data['i_edm_a_75'] = $this->input->post('i_edm_a_75');
			$data['i_edm_a_76'] = $this->input->post('i_edm_a_76');
			$data['i_edm_a_77'] = $this->input->post('i_edm_a_77');
			$data['i_edm_a_78'] = $this->input->post('i_edm_a_78');
			$data['i_edm_a_79'] = $this->input->post('i_edm_a_79');
			$data['i_edm_a_80'] = $this->input->post('i_edm_a_80');
			$data['i_edm_a_81'] = $this->input->post('i_edm_a_81');
			$data['i_edm_a_82'] = $this->input->post('i_edm_a_82');
			$data['i_edm_a_83'] = $this->input->post('i_edm_a_83');
			$data['i_edm_a_84'] = $this->input->post('i_edm_a_84');
			$data['i_edm_a_85'] = $this->input->post('i_edm_a_85');
			$data['i_edm_a_86'] = $this->input->post('i_edm_a_86');
			$data['i_edm_a_87'] = $this->input->post('i_edm_a_87');
			$data['i_edm_a_88'] = $this->input->post('i_edm_a_88');
			$data['i_edm_a_89'] = $this->input->post('i_edm_a_89');
			$data['i_edm_a_90'] = $this->input->post('i_edm_a_90');
			$data['i_edm_a_91'] = $this->input->post('i_edm_a_91');
			$data['i_edm_a_92'] = $this->input->post('i_edm_a_92');
			$data['i_edm_a_93'] = $this->input->post('i_edm_a_93');
			$data['i_edm_a_94'] = $this->input->post('i_edm_a_94');
			$data['i_edm_a_95'] = $this->input->post('i_edm_a_95');
			$data['i_edm_a_96'] = $this->input->post('i_edm_a_96');


			$t_h_a = $this->input->post('t_h_a');
			$t_h_b = $this->input->post('t_h_b[]');
			$tt_h_a = $this->input->post('tt_h_a');
			$tt_h_b = $this->input->post('tt_h_b');
			$t_v_a = $this->input->post('t_v_a');
			$t_v_b = $this->input->post('t_v_b');
			$tt_v_a = $this->input->post('tt_v_a');
			$tt_v_b = $this->input->post('tt_v_b');

			$data['t_h_a'] = dms2dec($t_h_a[0], $t_h_a[1], $t_h_a[2]);
			$data['t_h_b'] = dms2dec($t_h_b[0], $t_h_b[1], $t_h_b[2]);
			$data['tt_h_a'] = dms2dec($tt_h_a[0], $tt_h_a[1], $tt_h_a[2]);
			$data['tt_h_b'] = dms2dec($tt_h_b[0], $tt_h_b[1], $tt_h_b[2]);
			$data['t_v_a'] = dms2dec($t_v_a[0], $t_v_a[1], $t_v_a[2]);
			$data['t_v_b'] = dms2dec($t_v_b[0], $t_v_b[1], $t_v_b[2]);
			$data['tt_v_a'] = dms2dec($tt_v_a[0], $tt_v_a[1], $tt_v_a[2]);
			$data['tt_v_b'] = dms2dec($tt_v_b[0], $tt_v_b[1], $tt_v_b[2]);

		endif;

		//Total Station or Theodolite
		if ($calibration_instrument == 'Theodolite'):

			$data['th_v_a_1'] = $this->input->post('th_v_a_1');
			$data['th_v_a_2'] = $this->input->post('th_v_a_2');
			$data['th_v_a_3'] = $this->input->post('th_v_a_3');
			$data['th_v_a_4'] = $this->input->post('th_v_a_4');
			$data['th_v_a_5'] = $this->input->post('th_v_a_5');
			$data['th_v_a_6'] = $this->input->post('th_v_a_6');
			$data['th_v_a_7'] = $this->input->post('th_v_a_7');
			$data['th_v_a_8'] = $this->input->post('th_v_a_8');
			$data['th_v_a_9'] = $this->input->post('th_v_a_9');
			$data['th_v_a_10'] = $this->input->post('th_v_a_10');
			$data['th_v_a_11'] = $this->input->post('th_v_a_11');
			$data['th_v_a_12'] = $this->input->post('th_v_a_12');
			$data['th_v_a_13'] = $this->input->post('th_v_a_13');
			$data['th_v_a_14'] = $this->input->post('th_v_a_14');


			$th_h_a = $this->input->post('th_h_a');
			$th_h_b = $this->input->post('th_h_b[]');
			$thh_h_a = $this->input->post('thh_h_a');
			$thh_h_b = $this->input->post('thh_h_b');
			$th_v_a = $this->input->post('th_v_a');
			$th_v_b = $this->input->post('th_v_b');
			$thh_v_a = $this->input->post('thh_v_a');
			$thh_v_b = $this->input->post('thh_v_b');
			$thh_v_c = $this->input->post('thh_v_c');

			$data['th_h_a'] = dms2dec($th_h_a[0], $th_h_a[1], $th_h_a[2]);
			$data['th_h_b'] = dms2dec($th_h_b[0], $th_h_b[1], $th_h_b[2]);
			$data['thh_h_a'] = dms2dec($thh_h_a[0], $thh_h_a[1], $thh_h_a[2]);
			$data['thh_h_b'] = dms2dec($thh_h_b[0], $thh_h_b[1], $thh_h_b[2]);
			$data['th_v_a'] = dms2dec($th_v_a[0], $th_v_a[1], $th_v_a[2]);
			$data['th_v_b'] = dms2dec($th_v_b[0], $th_v_b[1], $th_v_b[2]);
			$data['thh_v_a'] = dms2dec($thh_v_a[0], $thh_v_a[1], $thh_v_a[2]);
			$data['thh_v_b'] = dms2dec($thh_v_b[0], $thh_v_b[1], $thh_v_b[2]);
			$data['thh_v_c'] = dms2dec($thh_v_c[0], $thh_v_c[1], $thh_v_c[2]);

			// Postcalibration data
			$th_h_a1 = $this->input->post('th_h_a1');
			$th_h_b1 = $this->input->post('th_h_b1[]');
			$thh_h_a1 = $this->input->post('thh_h_a1');
			$thh_h_b1 = $this->input->post('thh_h_b1');
			$th_v_a1 = $this->input->post('th_v_a1');
			$th_v_b1 = $this->input->post('th_v_b1');
			$thh_v_a1 = $this->input->post('thh_v_a1');
			$thh_v_b1 = $this->input->post('thh_v_b1');
			$thh_v_c1 = $this->input->post('thh_v_c1');

			$data['th_h_a1'] = dms2dec($th_h_a1[0], $th_h_a1[1], $th_h_a1[2]);
			$data['th_h_b1'] = dms2dec($th_h_b1[0], $th_h_b1[1], $th_h_b1[2]);
			$data['thh_h_a1'] = dms2dec($thh_h_a1[0], $thh_h_a1[1], $thh_h_a1[2]);
			$data['thh_h_b1'] = dms2dec($thh_h_b1[0], $thh_h_b1[1], $thh_h_b1[2]);
			$data['th_v_a1'] = dms2dec($th_v_a1[0], $th_v_a1[1], $th_v_a1[2]);
			$data['th_v_b1'] = dms2dec($th_v_b1[0], $th_v_b1[1], $th_v_b1[2]);
			$data['thh_v_a1'] = dms2dec($thh_v_a1[0], $thh_v_a1[1], $thh_v_a1[2]);
			$data['thh_v_b1'] = dms2dec($thh_v_b1[0], $thh_v_b1[1], $thh_v_b1[2]);
			$data['thh_v_c1'] = dms2dec($thh_v_c1[0], $thh_v_c1[1], $thh_v_c1[2]);

		endif;

		//GNSS
		if ($calibration_instrument == 'GNSS'):

			// Pre-Calibration Cheks Entry To Database

			$data['i_v_a_1'] = $this->input->post('i_v_a_1');
			$data['i_v_a_2'] = $this->input->post('i_v_a_2');
			$data['i_v_a_3'] = $this->input->post('i_v_a_3');
			$data['ii_v_a_1'] = $this->input->post('ii_v_a_1');
			$data['ii_v_a_2'] = $this->input->post('ii_v_a_2');
			$data['ii_v_a_3'] = $this->input->post('ii_v_a_3');
			$data['iii_v_a_1'] = $this->input->post('iii_v_a_1');
			$data['iii_v_a_2'] = $this->input->post('iii_v_a_2');
			$data['iii_v_a_3'] = $this->input->post('iii_v_a_3');
			$data['iv_v_a_1'] = $this->input->post('iv_v_a_1');
			$data['iv_v_a_2'] = $this->input->post('iv_v_a_2');
			$data['iv_v_a_3'] = $this->input->post('iv_v_a_3');
			$data['v_v_a_1'] = $this->input->post('v_v_a_1');
			$data['v_v_a_2'] = $this->input->post('v_v_a_2');
			$data['v_v_a_3'] = $this->input->post('v_v_a_3');
			$data['vi_v_a_1'] = $this->input->post('vi_v_a_1');
			$data['vi_v_a_2'] = $this->input->post('vi_v_a_2');
			$data['vi_v_a_3'] = $this->input->post('vi_v_a_3');
			$data['vii_v_a_1'] = $this->input->post('vii_v_a_1');
			$data['vii_v_a_2'] = $this->input->post('vii_v_a_2');
			$data['vii_v_a_3'] = $this->input->post('vii_v_a_3');
			$data['viii_v_a_1'] = $this->input->post('viii_v_a_1');
			$data['viii_v_a_2'] = $this->input->post('viii_v_a_2');
			$data['viii_v_a_3'] = $this->input->post('viii_v_a_3');
			$data['xi_v_a_1'] = $this->input->post('xi_v_a_1');
			$data['xi_v_a_2'] = $this->input->post('xi_v_a_2');
			$data['xi_v_a_3'] = $this->input->post('xi_v_a_3');
			$data['x_v_a_1'] = $this->input->post('x_v_a_1');
			$data['x_v_a_2'] = $this->input->post('x_v_a_2');
			$data['x_v_a_3'] = $this->input->post('x_v_a_3');

			// Table A3
			$data['i_v_b_1'] = $this->input->post('i_v_b_1');
			$data['i_v_b_2'] = $this->input->post('i_v_b_2');
			$data['i_v_b_3'] = $this->input->post('i_v_b_3');
			$data['ii_v_b_1'] = $this->input->post('ii_v_b_1');
			$data['ii_v_b_2'] = $this->input->post('ii_v_b_2');
			$data['ii_v_b_3'] = $this->input->post('ii_v_b_3');
			$data['iii_v_b_1'] = $this->input->post('iii_v_b_1');
			$data['iii_v_b_2'] = $this->input->post('iii_v_b_2');
			$data['iii_v_b_3'] = $this->input->post('iii_v_b_3');
			$data['iv_v_b_1'] = $this->input->post('iv_v_b_1');
			$data['iv_v_b_2'] = $this->input->post('iv_v_b_2');
			$data['iv_v_b_3'] = $this->input->post('iv_v_b_3');
			$data['v_v_b_1'] = $this->input->post('v_v_b_1');
			$data['v_v_b_2'] = $this->input->post('v_v_b_2');
			$data['v_v_b_3'] = $this->input->post('v_v_b_3');
			$data['vi_v_b_1'] = $this->input->post('vi_v_b_1');
			$data['vi_v_b_2'] = $this->input->post('vi_v_b_2');
			$data['vi_v_b_3'] = $this->input->post('vi_v_b_3');
			$data['vii_v_b_1'] = $this->input->post('vii_v_b_1');
			$data['vii_v_b_2'] = $this->input->post('vii_v_b_2');
			$data['vii_v_b_3'] = $this->input->post('vii_v_b_3');
			$data['viii_v_b_1'] = $this->input->post('viii_v_b_1');
			$data['viii_v_b_2'] = $this->input->post('viii_v_b_2');
			$data['viii_v_b_3'] = $this->input->post('viii_v_b_3');
			$data['xi_v_b_1'] = $this->input->post('xi_v_b_1');
			$data['xi_v_b_2'] = $this->input->post('xi_v_b_2');
			$data['xi_v_b_3'] = $this->input->post('xi_v_b_3');
			$data['x_v_b_1'] = $this->input->post('x_v_b_1');
			$data['x_v_b_2'] = $this->input->post('x_v_b_2');
			$data['x_v_b_3'] = $this->input->post('x_v_b_3');

			// Table A4
			$data['i_v_c_1'] = $this->input->post('i_v_c_1');
			$data['i_v_c_2'] = $this->input->post('i_v_c_2');
			$data['i_v_c_3'] = $this->input->post('i_v_c_3');
			$data['ii_v_c_1'] = $this->input->post('ii_v_c_1');
			$data['ii_v_c_2'] = $this->input->post('ii_v_c_2');
			$data['ii_v_c_3'] = $this->input->post('ii_v_c_3');
			$data['iii_v_c_1'] = $this->input->post('iii_v_c_1');
			$data['iii_v_c_2'] = $this->input->post('iii_v_c_2');
			$data['iii_v_c_3'] = $this->input->post('iii_v_c_3');
			$data['iv_v_c_1'] = $this->input->post('iv_v_c_1');
			$data['iv_v_c_2'] = $this->input->post('iv_v_c_2');
			$data['iv_v_c_3'] = $this->input->post('iv_v_c_3');
			$data['v_v_c_1'] = $this->input->post('v_v_c_1');
			$data['v_v_c_2'] = $this->input->post('v_v_c_2');
			$data['v_v_c_3'] = $this->input->post('v_v_c_3');
			$data['vi_v_c_1'] = $this->input->post('vi_v_c_1');
			$data['vi_v_c_2'] = $this->input->post('vi_v_c_2');
			$data['vi_v_c_3'] = $this->input->post('vi_v_c_3');
			$data['vii_v_c_1'] = $this->input->post('vii_v_c_1');
			$data['vii_v_c_2'] = $this->input->post('vii_v_c_2');
			$data['vii_v_c_3'] = $this->input->post('vii_v_c_3');
			$data['viii_v_c_1'] = $this->input->post('viii_v_c_1');
			$data['viii_v_c_2'] = $this->input->post('viii_v_c_2');
			$data['viii_v_c_3'] = $this->input->post('viii_v_c_3');
			$data['xi_v_c_1'] = $this->input->post('xi_v_c_1');
			$data['xi_v_c_2'] = $this->input->post('xi_v_c_2');
			$data['xi_v_c_3'] = $this->input->post('xi_v_c_3');
			$data['x_v_c_1'] = $this->input->post('x_v_c_1');
			$data['x_v_c_2'] = $this->input->post('x_v_c_2');
			$data['x_v_c_3'] = $this->input->post('x_v_c_3');

			// Post Calibration Checks Entry To Database
			$data['i_v_aa_1'] = $this->input->post('i_v_aa_1');
			$data['i_v_aa_2'] = $this->input->post('i_v_aa_2');
			$data['i_v_aa_3'] = $this->input->post('i_v_aa_3');
			$data['ii_v_aa_1'] = $this->input->post('ii_v_aa_1');
			$data['ii_v_aa_2'] = $this->input->post('ii_v_aa_2');
			$data['ii_v_aa_3'] = $this->input->post('ii_v_aa_3');
			$data['iii_v_aa_1'] = $this->input->post('iii_v_aa_1');
			$data['iii_v_aa_2'] = $this->input->post('iii_v_aa_2');
			$data['iii_v_aa_3'] = $this->input->post('iii_v_aa_3');
			$data['iv_v_aa_1'] = $this->input->post('iv_v_aa_1');
			$data['iv_v_aa_2'] = $this->input->post('iv_v_aa_2');
			$data['iv_v_aa_3'] = $this->input->post('iv_v_aa_3');
			$data['v_v_aa_1'] = $this->input->post('v_v_aa_1');
			$data['v_v_aa_2'] = $this->input->post('v_v_aa_2');
			$data['v_v_aa_3'] = $this->input->post('v_v_aa_3');
			$data['vi_v_aa_1'] = $this->input->post('vi_v_aa_1');
			$data['vi_v_aa_2'] = $this->input->post('vi_v_aa_2');
			$data['vi_v_aa_3'] = $this->input->post('vi_v_aa_3');
			$data['vii_v_aa_1'] = $this->input->post('vii_v_aa_1');
			$data['vii_v_aa_2'] = $this->input->post('vii_v_aa_2');
			$data['vii_v_aa_3'] = $this->input->post('vii_v_aa_3');
			$data['viii_v_aa_1'] = $this->input->post('viii_v_aa_1');
			$data['viii_v_aa_2'] = $this->input->post('viii_v_aa_2');
			$data['viii_v_aa_3'] = $this->input->post('viii_v_aa_3');
			$data['xi_v_aa_1'] = $this->input->post('xi_v_aa_1');
			$data['xi_v_aa_2'] = $this->input->post('xi_v_aa_2');
			$data['xi_v_aa_3'] = $this->input->post('xi_v_aa_3');
			$data['x_v_aa_1'] = $this->input->post('x_v_aa_1');
			$data['x_v_aa_2'] = $this->input->post('x_v_aa_2');
			$data['x_v_aa_3'] = $this->input->post('x_v_aa_3');

			// Table A3
			$data['i_v_bb_1'] = $this->input->post('i_v_bb_1');
			$data['i_v_bb_2'] = $this->input->post('i_v_bb_2');
			$data['i_v_bb_3'] = $this->input->post('i_v_bb_3');
			$data['ii_v_bb_1'] = $this->input->post('ii_v_bb_1');
			$data['ii_v_bb_2'] = $this->input->post('ii_v_bb_2');
			$data['ii_v_bb_3'] = $this->input->post('ii_v_bb_3');
			$data['iii_v_bb_1'] = $this->input->post('iii_v_bb_1');
			$data['iii_v_bb_2'] = $this->input->post('iii_v_bb_2');
			$data['iii_v_bb_3'] = $this->input->post('iii_v_bb_3');
			$data['iv_v_bb_1'] = $this->input->post('iv_v_bb_1');
			$data['iv_v_bb_2'] = $this->input->post('iv_v_bb_2');
			$data['iv_v_bb_3'] = $this->input->post('iv_v_bb_3');
			$data['v_v_bb_1'] = $this->input->post('v_v_bb_1');
			$data['v_v_bb_2'] = $this->input->post('v_v_bb_2');
			$data['v_v_bb_3'] = $this->input->post('v_v_bb_3');
			$data['vi_v_bb_1'] = $this->input->post('vi_v_bb_1');
			$data['vi_v_bb_2'] = $this->input->post('vi_v_bb_2');
			$data['vi_v_bb_3'] = $this->input->post('vi_v_bb_3');
			$data['vii_v_bb_1'] = $this->input->post('vii_v_bb_1');
			$data['vii_v_bb_2'] = $this->input->post('vii_v_bb_2');
			$data['vii_v_bb_3'] = $this->input->post('vii_v_bb_3');
			$data['viii_v_bb_1'] = $this->input->post('viii_v_bb_1');
			$data['viii_v_bb_2'] = $this->input->post('viii_v_bb_2');
			$data['viii_v_bb_3'] = $this->input->post('viii_v_bb_3');
			$data['xi_v_bb_1'] = $this->input->post('xi_v_bb_1');
			$data['xi_v_bb_2'] = $this->input->post('xi_v_bb_2');
			$data['xi_v_bb_3'] = $this->input->post('xi_v_bb_3');
			$data['x_v_bb_1'] = $this->input->post('x_v_bb_1');
			$data['x_v_bb_2'] = $this->input->post('x_v_bb_2');
			$data['x_v_bb_3'] = $this->input->post('x_v_bb_3');

			// Table A4
			$data['i_v_cc_1'] = $this->input->post('i_v_cc_1');
			$data['i_v_cc_2'] = $this->input->post('i_v_cc_2');
			$data['i_v_cc_3'] = $this->input->post('i_v_cc_3');
			$data['ii_v_cc_1'] = $this->input->post('ii_v_cc_1');
			$data['ii_v_cc_2'] = $this->input->post('ii_v_cc_2');
			$data['ii_v_cc_3'] = $this->input->post('ii_v_cc_3');
			$data['iii_v_cc_1'] = $this->input->post('iii_v_cc_1');
			$data['iii_v_cc_2'] = $this->input->post('iii_v_cc_2');
			$data['iii_v_cc_3'] = $this->input->post('iii_v_cc_3');
			$data['iv_v_cc_1'] = $this->input->post('iv_v_cc_1');
			$data['iv_v_cc_2'] = $this->input->post('iv_v_cc_2');
			$data['iv_v_cc_3'] = $this->input->post('iv_v_cc_3');
			$data['v_v_cc_1'] = $this->input->post('v_v_cc_1');
			$data['v_v_cc_2'] = $this->input->post('v_v_cc_2');
			$data['v_v_cc_3'] = $this->input->post('v_v_cc_3');
			$data['vi_v_cc_1'] = $this->input->post('vi_v_cc_1');
			$data['vi_v_cc_2'] = $this->input->post('vi_v_cc_2');
			$data['vi_v_cc_3'] = $this->input->post('vi_v_cc_3');
			$data['vii_v_cc_1'] = $this->input->post('vii_v_cc_1');
			$data['vii_v_cc_2'] = $this->input->post('vii_v_cc_2');
			$data['vii_v_cc_3'] = $this->input->post('vii_v_cc_3');
			$data['viii_v_cc_1'] = $this->input->post('viii_v_cc_1');
			$data['viii_v_cc_2'] = $this->input->post('viii_v_cc_2');
			$data['viii_v_cc_3'] = $this->input->post('viii_v_cc_3');
			$data['xi_v_cc_1'] = $this->input->post('xi_v_cc_1');
			$data['xi_v_cc_2'] = $this->input->post('xi_v_cc_2');
			$data['xi_v_cc_3'] = $this->input->post('xi_v_cc_3');
			$data['x_v_cc_1'] = $this->input->post('x_v_cc_1');
			$data['x_v_cc_2'] = $this->input->post('x_v_cc_2');
			$data['x_v_cc_3'] = $this->input->post('x_v_cc_3');


			// Table A4
			$data['r_v_a_1'] = $this->input->post('r_v_a_1');
			$data['r_v_a_2'] = $this->input->post('r_v_a_2');
			$data['r_v_a_3'] = $this->input->post('r_v_a_3');
			$data['r_v_a_4'] = $this->input->post('r_v_a_4');
			$data['r_v_a_5'] = $this->input->post('r_v_a_5');
			$data['r_v_a_6'] = $this->input->post('r_v_a_6');
			$data['r_v_a_7'] = $this->input->post('r_v_a_7');
			$data['r_v_a_8'] = $this->input->post('r_v_a_8');
			$data['r_v_a_9'] = $this->input->post('r_v_a_9');
			$data['r_v_a_10'] = $this->input->post('r_v_a_10');
			$data['r_v_a_11'] = $this->input->post('r_v_a_11');
			$data['r_v_a_12'] = $this->input->post('r_v_a_12');
			$data['r_v_a_13'] = $this->input->post('r_v_a_13');

			// Assuming you have a date component, you might need to add it
			$current_date = date('Y-m-d'); // This gets today's date

			$data['start_time_1'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_1')));
			$data['stop_time_1'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_1')));
			$data['start_time_2'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_2')));
			$data['stop_time_2'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_2')));
			$data['start_time_3'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_3')));
			$data['stop_time_3'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_3')));
			$data['start_time_4'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_4')));
			$data['stop_time_4'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_4')));
			$data['start_time_5'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_5')));
			$data['stop_time_5'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_5')));
			$data['start_time_6'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('start_time_6')));
			$data['stop_time_6'] = $current_date . ' ' . date('H:i:s', strtotime($this->input->post('stop_time_6')));



		endif;


		//Level
		if ($calibration_instrument == 'Level'):

			// INSTRUMENT INFORMATION REPORT
			$data['lv_v_a_1'] = $this->input->post('lv_v_a_1', true);
			$data['lv_v_a_2'] = $this->input->post('lv_v_a_2', true);
			$data['lv_v_a_3'] = $this->input->post('lv_v_a_3', true);
			$data['lv_v_a_4'] = $this->input->post('lv_v_a_4', true);
			$data['lv_v_a_5'] = $this->input->post('lv_v_a_5', true);
			$data['lv_v_a_6'] = $this->input->post('lv_v_a_6', true);
			$data['lv_v_a_7'] = $this->input->post('lv_v_a_7', true);
			$data['lv_v_a_8'] = $this->input->post('lv_v_a_8', true);
			$data['lv_v_a_9'] = $this->input->post('lv_v_a_9', true);


			//pre-calibration 
			$data['i_backsight_a'] = $this->input->post('i_backsight_a', true);
			$data['i_foresight_b'] = $this->input->post('i_foresight_b', true);
			$data['i_backsight_c'] = $this->input->post('i_backsight_c', true);
			$data['i_foresight_d'] = $this->input->post('i_foresight_d', true);

			$data['ii_backsight_a'] = $this->input->post('ii_backsight_a', true);
			$data['ii_foresight_b'] = $this->input->post('ii_foresight_b', true);
			$data['ii_backsight_c'] = $this->input->post('ii_backsight_c', true);
			$data['ii_foresight_d'] = $this->input->post('ii_foresight_d', true);

			$data['iii_backsight_a'] = $this->input->post('iii_backsight_a', true);
			$data['iii_foresight_b'] = $this->input->post('iii_foresight_b', true);
			$data['iii_backsight_c'] = $this->input->post('iii_backsight_c', true);
			$data['iii_foresight_d'] = $this->input->post('iii_foresight_d', true);

			$data['iv_backsight_a'] = $this->input->post('iv_backsight_a', true);
			$data['iv_foresight_b'] = $this->input->post('iv_foresight_b', true);
			$data['iv_backsight_c'] = $this->input->post('iv_backsight_c', true);
			$data['iv_foresight_d'] = $this->input->post('iv_foresight_d', true);

			$data['v_backsight_a'] = $this->input->post('v_backsight_a', true);
			$data['v_foresight_b'] = $this->input->post('v_foresight_b', true);
			$data['v_backsight_c'] = $this->input->post('v_backsight_c', true);
			$data['v_foresight_d'] = $this->input->post('v_foresight_d', true);

			$data['vi_backsight_a'] = $this->input->post('vi_backsight_a', true);
			$data['vi_foresight_b'] = $this->input->post('vi_foresight_b', true);
			$data['vi_backsight_c'] = $this->input->post('vi_backsight_c', true);
			$data['vi_foresight_d'] = $this->input->post('vi_foresight_d', true);

			$data['vii_backsight_a'] = $this->input->post('vii_backsight_a', true);
			$data['vii_foresight_b'] = $this->input->post('vii_foresight_b', true);
			$data['vii_backsight_c'] = $this->input->post('vii_backsight_c', true);
			$data['vii_foresight_d'] = $this->input->post('vii_foresight_d', true);

			$data['viii_backsight_a'] = $this->input->post('viii_backsight_a', true);
			$data['viii_foresight_b'] = $this->input->post('viii_foresight_b', true);
			$data['viii_backsight_c'] = $this->input->post('viii_backsight_c', true);
			$data['viii_foresight_d'] = $this->input->post('viii_foresight_d', true);

			$data['ix_backsight_a'] = $this->input->post('ix_backsight_a', true);
			$data['ix_foresight_b'] = $this->input->post('ix_foresight_b', true);
			$data['ix_backsight_c'] = $this->input->post('ix_backsight_c', true);
			$data['ix_foresight_d'] = $this->input->post('ix_foresight_d', true);

			$data['x_backsight_a'] = $this->input->post('x_backsight_a', true);
			$data['x_foresight_b'] = $this->input->post('x_foresight_b', true);
			$data['x_backsight_c'] = $this->input->post('x_backsight_c', true);
			$data['x_foresight_d'] = $this->input->post('x_foresight_d', true);

			//post-calibration 
			$data['i_backsight_e'] = $this->input->post('i_backsight_e', true);
			$data['i_foresight_f'] = $this->input->post('i_foresight_f', true);
			$data['i_backsight_g'] = $this->input->post('i_backsight_g', true);
			$data['i_foresight_h'] = $this->input->post('i_foresight_h', true);

			$data['ii_backsight_e'] = $this->input->post('ii_backsight_e', true);
			$data['ii_foresight_f'] = $this->input->post('ii_foresight_f', true);
			$data['ii_backsight_g'] = $this->input->post('ii_backsight_g', true);
			$data['ii_foresight_h'] = $this->input->post('ii_foresight_h', true);

			$data['iii_backsight_e'] = $this->input->post('iii_backsight_e', true);
			$data['iii_foresight_f'] = $this->input->post('iii_foresight_f', true);
			$data['iii_backsight_g'] = $this->input->post('iii_backsight_g', true);
			$data['iii_foresight_h'] = $this->input->post('iii_foresight_h', true);

			$data['iv_backsight_e'] = $this->input->post('iv_backsight_e', true);
			$data['iv_foresight_f'] = $this->input->post('iv_foresight_f', true);
			$data['iv_backsight_g'] = $this->input->post('iv_backsight_g', true);
			$data['iv_foresight_h'] = $this->input->post('iv_foresight_h', true);

			$data['v_backsight_e'] = $this->input->post('v_backsight_e', true);
			$data['v_foresight_f'] = $this->input->post('v_foresight_f', true);
			$data['v_backsight_g'] = $this->input->post('v_backsight_g', true);
			$data['v_foresight_h'] = $this->input->post('v_foresight_h', true);

			$data['vi_backsight_e'] = $this->input->post('vi_backsight_e', true);
			$data['vi_foresight_f'] = $this->input->post('vi_foresight_f', true);
			$data['vi_backsight_g'] = $this->input->post('vi_backsight_g', true);
			$data['vi_foresight_h'] = $this->input->post('vi_foresight_h', true);

			$data['vii_backsight_e'] = $this->input->post('vii_backsight_e', true);
			$data['vii_foresight_f'] = $this->input->post('vii_foresight_f', true);
			$data['vii_backsight_g'] = $this->input->post('vii_backsight_g', true);
			$data['vii_foresight_h'] = $this->input->post('vii_foresight_h', true);

			$data['viii_backsight_e'] = $this->input->post('viii_backsight_e', true);
			$data['viii_foresight_f'] = $this->input->post('viii_foresight_f', true);
			$data['viii_backsight_g'] = $this->input->post('viii_backsight_g', true);
			$data['viii_foresight_h'] = $this->input->post('viii_foresight_h', true);

			$data['ix_backsight_e'] = $this->input->post('ix_backsight_e', true);
			$data['ix_foresight_f'] = $this->input->post('ix_foresight_f', true);
			$data['ix_backsight_g'] = $this->input->post('ix_backsight_g', true);
			$data['ix_foresight_h'] = $this->input->post('ix_foresight_h', true);

			$data['x_backsight_e'] = $this->input->post('x_backsight_e', true);
			$data['x_foresight_f'] = $this->input->post('x_foresight_f', true);
			$data['x_backsight_g'] = $this->input->post('x_backsight_g', true);
			$data['x_foresight_h'] = $this->input->post('x_foresight_h', true);

		endif;

		//Lasers
		if ($calibration_instrument == 'lasers'):

			$data['ls_v_a_1'] = $this->input->post('ls_v_a_1', true); // Instrument Make
			$data['ls_v_a_2'] = $this->input->post('ls_v_a_2', true); // Instrument Model
			$data['ls_v_a_3'] = $this->input->post('ls_v_a_3', true); // Instrument Serial No.
			$data['ls_v_a_4'] = $this->input->post('ls_v_a_4', true); // Instrument Condition
			$data['ls_v_a_5'] = $this->input->post('ls_v_a_5', true); // Test Distance (M)
			$data['ls_v_a_6'] = $this->input->post('ls_v_a_6', true); // Manufacturer EDM Accuracy (MM)
			$data['ls_v_a_7'] = $this->input->post('ls_v_a_7', true); // Weather Condition
			$data['ls_v_a_8'] = $this->input->post('ls_v_a_8', true); // Temperature (°C)
			$data['ls_v_a_9'] = $this->input->post('ls_v_a_9', true); // Air Pressure (hPa)

			$data['hh_bsa1'] = $this->input->post('hh_bsa1', true);
			$data['hh_fsa1'] = $this->input->post('hh_fsa1', true);
			$data['hl_bsa1'] = $this->input->post('hl_bsa1', true);
			$data['hl_fsa1'] = $this->input->post('hl_fsa1', true);
			$data['hh_bsa2'] = $this->input->post('hh_bsa2', true);
			$data['hh_fsa2'] = $this->input->post('hh_fsa2', true);
			$data['vl_bsa1'] = $this->input->post('vl_bsa1', true);
			$data['vl_fsa1'] = $this->input->post('vl_fsa1', true);
			$data['hh_bsa3'] = $this->input->post('hh_bsa3', true);
			$data['hh_fsa3'] = $this->input->post('hh_fsa3', true);
			$data['hl_bsa2'] = $this->input->post('hl_bsa2', true);
			$data['hl_fsa2'] = $this->input->post('hl_fsa2', true);
			$data['hh_bsa4'] = $this->input->post('hh_bsa4', true);
			$data['hh_fsa4'] = $this->input->post('hh_fsa4', true);
			$data['vl_bsa2'] = $this->input->post('vl_bsa2', true);
			$data['vl_fsa2'] = $this->input->post('vl_fsa2', true);
		endif;


		$data['calibration_instrument'] = $calibration_instrument;
		$data['calibration_remark'] = $this->input->post('calibration_remark', true);

		$service_code = $this->input->post('service_code', true);
		$edit_id = $this->input->post('edit_id', true);
		$service_request_details_id_edit = $this->input->post('service_request_details_id', true);

		if (!empty($service_code)) {
			$service_info = $this->db->where('service_request_code', $service_code)->get('tblservice_request')->row();
			$data['service_request_id'] = $service_info->service_request_id;
		}

		//echo json_encode($data); exit;

		//Entry into tbl_service_calibration
		$service_calibration = false;
		$for = null;
		if (empty($edit_id)) {
			$service_calibration = $this->services_model->add_request_calibration($data);
			$for = 'added';
		} else {
			$service_calibration = $this->services_model->edit_request_calibration($data, $edit_id);
			$for = 'updated';
		}

		$autosave = $this->input->post('autosave', true);
		if (!isset($autosave)) {
			if ($service_calibration) {
				set_alert('success', 'Calibration report ' . $for . ' successful');
			} else {
				set_alert('success', 'Calibration report Successfull');
			}

			//display request
			redirect(admin_url('services/report/edit/' . $service_code));
		} else {
			// If autosave is set, return the ID of the saved calibration
			if ($service_calibration) {
				echo json_encode(['success' => true, 'message' => 'Calibration report ' . $for . ' successful']);
			} else {
				echo json_encode(['success' => true, 'message' => 'Calibration report Successfull']);
			}
		}
	}

	public function gpsDetails()
	{
		$data['gps_details'] = $this->services_model->get_gps_details();
		$this->load->view('admin/services/gps_details', $data);
	}

	public function form()
	{
		$this->load->view('admin/services/gps_data_form');
	}

	public function insert_data()
	{
		// Load form validation library
		$this->form_validation->set_rules('data', 'Data', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('admin/services/gps_data_form');
		} else {
			$data = $this->input->post('data');
			$this->GpsDataModel->insert_data($data);
			$this->load->view('admin/services/form_success');
		}
	}

	public function request_pdf($code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		if (empty($code)) {
			redirect(admin_url('services/requests'));
		}

		$request_number = get_option('service_request_prefix') . $code;

		// Get service
		$data['service_request'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		if (!$data['service_request']) {
			show_404(); // Handle case where service request is not found
		}

		// Request details
		$data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')
			->where('service_request_id', $data['service_request']->service_request_id)
			->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')
			->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
			->get('tblservice_request_details')->result();

		// Fetch inspection items
		$inspection_items = $this->db->where('service_request_id', $data['service_request']->service_request_id)
			->get('tblinspection_requests')->result();
		$data['pre_inspection_items'] = [];
		$data['post_inspection_items'] = [];

		foreach ($inspection_items as $item) {
			if ($item->inspection_type == 'pre_inspection') {
				$data['pre_inspection_items'][] = $item;
			} elseif ($item->inspection_type == 'post_inspection') {
				$data['post_inspection_items'][] = $item;
			}
		}

		// Fetch checklist items
		$data['checklist_items'] = $this->db->where('service_request_id', $data['service_request']->service_request_id)
			->get('tblchecklist1')->result();

		// Fetch collection (released by) information
		$data['released_info'] = $this->db->where('service_request_id', $data['service_request']->service_request_id)
			->get('tblcollection1')->row();

		// Fetch client information
		$data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);

		// Fetch accessory information along with item descriptions
		$data['existing_accessories'] = $this->db->select('
				tblservice_request_accessories.*, 
				tblitems.description AS item_description, 
				tblitems.rate AS item_rate,
				tblitems.unit AS item_unit,
				tblitems.commodity_name AS item_name
				')
			->from('tblservice_request_accessories')
			->join('tblitems', 'tblitems.id = tblservice_request_accessories.accessory_id')
			->where('tblservice_request_accessories.service_request_id', $data['service_request']->service_request_id)
			->get()
			->result();


		// Fetch additional data for dropped off and received info
		$this->db->select('dropped_off_by, dropped_off_date, dropped_off_signature, dropped_off_id_number, received_by, received_date, received_signature, received_id_number');
		$this->db->where('service_request_id', $data['service_request']->service_request_id);
		$service_request_info = $this->db->get('tblservice_request')->row();

		// Assign values or set to 'N/A' if they don't exist
		$data['dropped_off_by'] = isset($service_request_info->dropped_off_by) ? $service_request_info->dropped_off_by : 'N/A';
		$data['dropped_off_date'] = isset($service_request_info->dropped_off_date) ? $service_request_info->dropped_off_date : 'N/A';
		$data['dropped_off_signature'] = isset($service_request_info->dropped_off_signature) ? $service_request_info->dropped_off_signature : 'N/A';
		$data['dropped_off_id_number'] = isset($service_request_info->dropped_off_id_number) ? $service_request_info->dropped_off_id_number : 'N/A';

		$data['received_by'] = isset($service_request_info->received_by) ? $service_request_info->received_by : 'N/A';
		$data['received_date'] = isset($service_request_info->received_date) ? $service_request_info->received_date : 'N/A';
		$data['received_signature'] = isset($service_request_info->received_signature) ? $service_request_info->received_signature : 'N/A';
		$data['received_id_number'] = isset($service_request_info->received_id_number) ? $service_request_info->received_id_number : 'N/A';



		try {
			$pdf = service_request_pdf($data);
		} catch (Exception $e) {
			$message = $e->getMessage();
			echo $message;
			if (strpos($message, 'Unable to get the size of the image') !== false) {
				show_pdf_unable_to_get_image_size_error();
			}
			die;
		}

		$type = 'I';
		if ($this->input->get('print')) {
			$type = 'I';
		}

		$pdf->Output('SERVICE REQUEST FORM ' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
	}



	public function warranty_pdf($serial_number = null)
	{
		$this->load->helper('bizit_services_msl_helper');

		// Log the start of the request
		log_message('info', 'Starting request_warranty_pdf with serial number: ' . $serial_number);

		// Check user permissions
		if (!has_permission(BIZIT_SERVICES_MSL, '_warranty', 'view')) {
			log_message('error', 'Access denied for user.');
			access_denied('Services');
		}

		// Validate the provided serial number
		if (empty($serial_number)) {
			log_message('error', 'Serial number is empty.');
			redirect(admin_url('services/view_warranty'));
		}

		// Fetch warranty data using the serial number
		$data['warranty'] = $this->db->where('serial_number', $serial_number)->get('tblwarranty')->row();

		if (!$data['warranty']) {
			log_message('error', 'Warranty not found for serial number: ' . $serial_number);
			$this->session->set_flashdata('error', 'Warranty not found.');
			redirect(admin_url('services/view_warranty'));
		}

		// Attempt to generate PDF
		try {
			$pdf = warranty_request_pdf($data); // Call the helper function
			log_message('info', 'PDF generated successfully.');

			// Determine whether to display inline or download
			$outputType = $this->input->get('print') ? 'I' : 'D';
			$fileName = 'WARRANTY_' . strtoupper(slug_it($data['warranty']->serial_number)) . '.pdf';

			// Output the PDF with the specified type
			$pdf->Output($fileName, $outputType);
		} catch (Exception $e) {
			// Log error and set error message
			log_message('error', 'PDF generation error for serial number ' . $data['warranty']->serial_number . ': ' . $e->getMessage());
			$this->session->set_flashdata('error', 'An error occurred while generating the PDF.');
			redirect(admin_url('services/view_warranty'));
		}
	}



	/* Delivery Note */
	public function delivery_note($id)
	{
		if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) {
			access_denied('invoices');
		}
		if (!$id) {
			redirect(admin_url('invoices/list_invoices'));
		}
		$invoice        = $this->invoices_model->get($id);
		$invoice_number = format_invoice_number($invoice->id);
		$status = $invoice->status;


		// Fetch client details
		$client_details = $this->clients_model->get($invoice->clientid);

		// Custom fields for the client
		$custom_fields = get_custom_fields('customers', ['show_on_pdf' => 1]);
		$client_custom_fields = [];
		foreach ($custom_fields as $field) {
			$value = get_custom_field_value($invoice->clientid, $field['id'], 'customers');
			if ($value) {
				$client_custom_fields[$field['name']] = $value;
			}
		}

		// Retrieve items associated with the invoice
		$items_data = $this->services_model->get_table_products_bulk($id);

		try {
			// Generate the PDF using all data collected
			$pdf = delivery_note_pdf([
				'invoice' => $invoice,
				'invoice_number' => $invoice_number,
				'status' => $status,
				'client' => $client_details,
				'client_custom_fields' => $client_custom_fields,
				'items_data' => $items_data
			]);
		} catch (Exception $e) {
			$message = $e->getMessage();
			echo $message;

			if (strpos($message, 'Unable to get the size of the image') !== false) {
				show_pdf_unable_to_get_image_size_error();
			}

			die;
		}


		$type           = 'I';
		if ($this->input->get('print')) {
			$type = 'D';
		}

		$pdf->Output(_l('delivery_note') . mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
	}

	public function inventory_checklist($id)
	{
		// Check permissions
		if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own')) {
			access_denied('invoices');
		}

		// Redirect if no ID provided
		if (!$id) {
			redirect(admin_url('invoices/list_invoices'));
		}

		// Retrieve invoice data
		$invoice = $this->invoices_model->get($id);
		if (!$invoice) {
			show_404();
		}

		$invoice_number = format_invoice_number($invoice->id);
		$status = $invoice->status;

		// Fetch client details
		$client_details = $this->clients_model->get($invoice->clientid);

		// Custom fields for the client
		$custom_fields = get_custom_fields('customers', ['show_on_pdf' => 1]);
		$client_custom_fields = [];
		foreach ($custom_fields as $field) {
			$value = get_custom_field_value($invoice->clientid, $field['id'], 'customers');
			if ($value) {
				$client_custom_fields[$field['name']] = $value;
			}
		}

		// Retrieve items associated with the invoice
		$items_data = $this->services_model->get_table_products_bulk($id);

		try {
			// Generate the PDF using all data collected
			$pdf = inventory_checklist_pdf([
				'invoice' => $invoice,
				'invoice_number' => $invoice_number,
				'status' => $status,
				'client' => $client_details,
				'client_custom_fields' => $client_custom_fields,
				'items_data' => $items_data
			]);
		} catch (Exception $e) {
			$message = $e->getMessage();
			echo $message;

			if (strpos($message, 'Unable to get the size of the image') !== false) {
				show_pdf_unable_to_get_image_size_error();
			}

			die;
		}

		$type = 'I';
		if ($this->input->get('print')) {
			$type = 'D';
		}

		$pdf->Output(_l('inventory_checklist') . mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
	}

	public function change_assignee($invoiceid)
	{
		$data = array();
		if (is_admin()) {
			$data['addedfrom'] = $this->input->post('addedfrom');
			$this->db->where('id', $invoiceid)->update('tblinvoices', $data);
			redirect(admin_url('invoices/list_invoices#' . $invoiceid));
		}

		return true;
	}

	public function inventory_qty_check($product_id = 0, $qty = 0)
	{
		if ($this->input->is_ajax_request()) {
			echo $this->invoices_model->inventory_qty_check($product_id, $qty);
		}
	}


	public function test2($value = '')
	{
		$bulk_serial = 'bs-2323122';
		echo substr($bulk_serial, 3);
	}


	// public function request_invoice_generation($code = null) {

	// 	$this->load->model('invoices_model');
	// 	if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
	// 		access_denied('invoices');
	// 	}

	// 	//Get service
	// 	$service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

	// 	//request details
	// 	$service_details = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservices_module.quantity_unit, tblservices_module.service_code, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $service_request->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();
	// 	$i = 1;
	// 	$newitems = array();
	// 	$subtotal = 0;
	// 	foreach ($service_details as $key => $value) {
	// 		$newitems[$i] = array(
	// 			"order" => $i,
	// 			"description" => $value->name,
	// 			"long_description" => $value->category_name . ', Service Code => ' . $value->service_code . '<br/> Make: ' . $service_request->item_make . '<br/> Model: ' . $service_request->item_model . '<br/> Serial: ' . $service_request->serial_no,
	// 			"serial" => "",
	// 			"product_id" => "",
	// 			"qty" => "1",
	// 			"unit" => empty($value->quantity_unit) ? "Service" : $value->quantity_unit,
	// 			"rate" => $value->price,
	// 			"taxable" => "1",
	// 			"item_for" => "3",
	// 		);
	// 		$subtotal += $value->price;
	// 		$i++;
	// 	}

	// 	//Client Details
	// 	$service_request_client = $this->clients_model->get($service_request->clientid);

	// 	//Invoice Number
	// 	$next_invoice_number = get_option('next_invoice_number');
	// 	$format = get_option('invoice_number_format');
	// 	$prefix = get_option('invoice_prefix');
	// 	if ($format == 1) {
	// 		// Number based
	// 		$__number = $next_invoice_number;
	// 	} else {
	// 		$__number = $next_invoice_number;
	// 		$prefix = $prefix . '<span id="prefix_year">' . date('Y') . '</span>/';
	// 	}
	// 	$_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);

	// 	//Payment Modes
	// 	$this->load->model('payment_modes_model');
	// 	$payment_modes = $this->payment_modes_model->get('', array(
	// 		'expenses_only !=' => 1,
	// 	));
	// 	$allowed_payment_modes = array();
	// 	if (count($payment_modes) > 0) {
	// 		foreach ($payment_modes as $mode) {
	// 			if ($mode['selected_by_default'] == 1) {
	// 				$allowed_payment_modes[] = $mode['id'];
	// 			}
	// 		}
	// 	}

	// 	//Generate Invoice Data
	// 	$invoice_data = array(
	// 		"save_as_draft" => "true",
	// 		"clientid" => $service_request_client->userid,
	// 		"invoice_for" => "3",
	// 		"project_id" => "",
	// 		"billing_street" => $service_request_client->billing_street,
	// 		"billing_city" => $service_request_client->billing_city,
	// 		"billing_state" => $service_request_client->billing_state,
	// 		"billing_zip" => $service_request_client->billing_zip,
	// 		"billing_country" => $service_request_client->billing_country,
	// 		//"include_shipping" => "on",
	// 		"show_shipping_on_invoice" => "on",
	// 		"shipping_street" => $service_request_client->shipping_street,
	// 		"shipping_city" => $service_request_client->shipping_city,
	// 		"shipping_state" => $service_request_client->shipping_state,
	// 		"shipping_zip" => $service_request_client->shipping_zip,
	// 		"shipping_country" => $service_request_client->shipping_country,
	// 		"number" => $_invoice_number,
	// 		"date" => _d(date('Y-m-d')),
	// 		"duedate" => get_option('invoice_due_after') != 0 ? _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d'))))) : "",
	// 		"allowed_payment_modes" => $allowed_payment_modes,
	// 		"currency" => get_default_currency('id'),
	// 		"sale_agent" => "",
	// 		"recurring" => "0",
	// 		"discount_type" => "",
	// 		"repeat_every_custom" => "1",
	// 		"repeat_type_custom" => "day",
	// 		"recurring_ends_on" => "",
	// 		"adminnote" => "",
	// 		//"barcode" => "",
	// 		"product_select" => "",
	// 		"item_select" => "",
	// 		"task_select" => "",
	// 		"show_quantity_as" => "1",
	// 		"service_select" => "",
	// 		"invoice_services" => "",
	// 		"description" => "",
	// 		"long_description" => "",
	// 		"serial" => "",
	// 		"product_id" => "",
	// 		"quantity" => "1",
	// 		"unit" => "",
	// 		"rate" => "",
	// 		"taxable" => "",
	// 		"item_for" => "",
	// 		"newitems" => $newitems,
	// 		"subtotal" => $subtotal,
	// 		"discount_percent" => "0",
	// 		"discount_total" => "0",
	// 		"adjustment" => "0.00",
	// 		"total" => $subtotal,
	// 		"task_id" => "",
	// 		"expense_id" => "",
	// 		"clientnote" => get_option('predefined_clientnote_invoice'),
	// 		"terms" => get_option('predefined_terms_invoice'),
	// 	);
	// 	//echo '<pre>'.json_encode($invoice_data); exit;

	// 	$id = $this->invoices_model->add($invoice_data);
	// 	if ($id) {
	// 		$this->db->where('service_request_id', $service_request->service_request_id)
	// 				 ->update('tblservice_request', array(
	// 					 'invoice_rel_id' => $id
	// 					//  'status' => '2' // Replace 'new_status_value' with the desired status
	// 				 ));
	// 		set_alert('success', _l('added_successfully', _l('invoice')));
	// 		redirect(admin_url('services/view_request/' . $code));
	// 	}

	// }

	public function request_invoice_generation($code = null)
	{
		$this->load->model('invoices_model');

		// Check permissions
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('invoices');
		}

		// Get service request
		$service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		// Request details and accessories
		$service_details = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservices_module.quantity_unit, tblservices_module.service_code, tblservice_type.name as category_name, tblservice_type.service_typeid')
			->where('service_request_id', $service_request->service_request_id)
			->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')
			->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
			->get('tblservice_request_details')->result();

		// Initialize accessory price
		$accessory_price = 0;

		// Fetch all accessories associated with the service_request_id
		$accessory_details = $this->db->select('tblservice_request_accessories.*, tblitems.commodity_name, tblitems.unit')
			->where('service_request_id', $service_request->service_request_id)
			->join('tblitems', 'tblitems.id = tblservice_request_accessories.accessory_id')
			->get('tblservice_request_accessories')
			->result();  // Get all rows as an array of objects

		// Sum up the prices of all accessories
		if ($accessory_details) {
			foreach ($accessory_details as $accessory) {
				$accessory_price += $accessory->price;
			}
		}

		//echo '<pre>'.json_encode($accessory_details); exit;

		// Generate invoice items
		$i = 1;
		$newitems = array();
		$subtotal = 0;
		foreach ($service_details as $key => $value) {
			$newitems[$i] = array(
				"order" => $i,
				"description" => $value->name,
				"long_description" => $value->category_name . ', Service Code => ' . $value->service_code . '<br/> Make: ' . $service_request->item_make . '<br/> Model: ' . $service_request->item_model . '<br/> Serial: ' . $service_request->serial_no,
				"serial" => "",
				"product_id" => "",
				"qty" => "1",
				"unit" => empty($value->quantity_unit) ? "Service" : $value->quantity_unit,
				"rate" => $value->price,
				"taxable" => "1",
				"item_for" => "3",
			);
			$subtotal += $value->price;
			$i++;
		}

		// Generate new items for accessories
		if ($accessory_details) {
			foreach ($accessory_details as $accessory) {
				$newitems[$i] = array(
					"order" => $i,
					"description" => $accessory->commodity_name,
					"long_description" => 'Accessory',
					"serial" => "",
					"product_id" => "",
					"qty" => "1",
					"unit" => $accessory->unit,
					"rate" => $accessory->price,
					"taxable" => "1",
					"item_for" => "3",
				);
				$subtotal += $accessory->price; // Add accessory price to subtotal
				$i++;
			}
		}

		// Add the accessory price to the total
		$total = $subtotal; // Subtotal already includes service details prices


		// Client Details
		$service_request_client = $this->clients_model->get($service_request->clientid);

		// Invoice Number
		$next_invoice_number = get_option('next_invoice_number');
		$format = get_option('invoice_number_format');
		$prefix = get_option('invoice_prefix');
		if ($format == 1) {
			$__number = $next_invoice_number;
		} else {
			$__number = $next_invoice_number;
			$prefix = $prefix . '<span id="prefix_year">' . date('Y') . '</span>/';
		}
		$_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);

		// Payment Modes
		$this->load->model('payment_modes_model');
		$payment_modes = $this->payment_modes_model->get('', array('expenses_only !=' => 1));
		$allowed_payment_modes = array();
		foreach ($payment_modes as $mode) {
			if ($mode['selected_by_default'] == 1) {
				$allowed_payment_modes[] = $mode['id'];
			}
		}

		// Generate Invoice Data
		$invoice_data = array(
			"save_as_draft" => "true",
			"clientid" => $service_request_client->userid,
			"invoice_for" => "3",
			"number" => $_invoice_number,
			"date" => _d(date('Y-m-d')),
			"duedate" => get_option('invoice_due_after') != 0 ? _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d'))))) : "",
			"allowed_payment_modes" => $allowed_payment_modes,
			"currency" => get_default_currency('id'),
			"subtotal" => $subtotal,
			"total" => $total, // Set total including accessories
			"newitems" => $newitems,
			"clientnote" => get_option('predefined_clientnote_invoice'),
			"terms" => get_option('predefined_terms_invoice'),
		);

		//echo '<pre>'.json_encode($invoice_data); exit;

		// Create the invoice
		$id = $this->invoices_model->add($invoice_data);
		if ($id) {
			// Update service request with invoice ID
			$this->db->where('service_request_id', $service_request->service_request_id)
				->update('tblservice_request', array('invoice_rel_id' => $id));

			set_alert('success', _l('added_successfully', _l('invoice')));
			redirect(admin_url('services/view_request/' . $code));
		}
	}


	public function certificate_pdf($code = null)
	{
		// Check if user has permission
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		// Redirect if no code is provided
		if (empty($code)) {
			redirect(admin_url('services/requests'));
		}

		// Fetch the service request data from the database
		$service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		// Ensure the service request was found
		if (!$service_request) {
			show_error('Service request not found.');
		}

		// Prepare the system information data
		$make = $service_request->item_make;
		$model = $service_request->item_model;
		$serial_no = $service_request->serial_no;
		$service_request_code = $service_request->service_request_code;


		// Format the collection date and add one year
		$date = strtotime($service_request->collection_date);
		$one_year_later = strtotime('+1 year', $date);
		$formatted_date = date("M", $one_year_later) . ', ' . date("d", $one_year_later) . ' ' . date("Y", $one_year_later);

		// Generate the QR data with dynamic information
		$qr_data = site_url('service/certificate/validate/' . $service_request_code);

		// Generate the QR code and pass it as base64 data
		$data['qr_code_base64'] = $this->generate($qr_data);

		// Generate the request number for the PDF filename
		$request_number = get_option('service_request_prefix') . $code;

		// Pass the service request data to the view
		$data['service_request'] = $service_request;

		// Load the HTML view for the certificate
		$html = $this->load->view('admin/services/html_certificate', $data, true);

		// Generate the filename for the PDF
		$filename = 'CALIBRATION CERTIFICATE-' . mb_strtoupper(slug_it($request_number));

		// Create the PDF and display it
		$this->dpdf->pdf_create($html, $filename, 'view');
	}


	public function generate($data)
	{
		$this->load->library('ciqrcode');

		// Generate QR code params
		$params['data'] = $data;
		$params['level'] = 'L';
		$params['size'] = 2;

		// Output QR code as image file
		$qr_image_path = FCPATH . 'modules/bizit_services_msl/assets/images/' . uniqid() . '.png';
		$params['savename'] = $qr_image_path;

		$this->ciqrcode->generate($params);

		// Get the image content and encode as base64
		$qr_image_data = file_get_contents($qr_image_path);
		$qr_image_base64 = base64_encode($qr_image_data);

		// Delete the file after getting the content
		unlink($qr_image_path);

		return $qr_image_base64;
	}

	private function generate_qr_code_url($code)
	{
		// This should return the correct URL for generating the QR code
		return admin_url('QrCodeController/generate_qr_code' . urlencode($code));
	}


	public function warranty()
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_warranty', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL . '_warranty');
		}

		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_warranty')); // Fetch warranty data
		}

		$data['title'] = _l('als_services_warranty');
		$this->load->view('admin/services/warranty', $data); // Load the warranty view
	}


	//==========================================================
	//  RENTAL AGREEMENT
	//==========================================================

	public function rental_agreements()
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_rental_agreements'));;
		}

		$data['title'] = _l('als_services_for_hire');
		$this->load->view('admin/services/manage_rental_agreements', $data);
	}

	/*** New rental_agreement ***/
	public function new_rental_agreement($flag = null, $code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}
		if (!is_null($flag) and !is_numeric($flag)) {
			redirect(admin_url('services/rental_agreement'));
		}

		if (empty($flag)) {
			$rental_agreement_session = array('service_rental_agreement_code' => '');
			$this->session->unset_userdata($rental_agreement_session);
			$random_number = rand(10000000, 99999);

			$q = $this->db->get('tblservice_rental_agreement')->result();
			if (!empty($q)) {
				$last = end($q);
				$new_index = $last->service_rental_agreement_id;
				$random_number = $random_number . $new_index;
			}

			$service_rental_agreement_code = array(
				'service_rental_agreement_code' => $random_number,
			);
			$this->session->set_userdata($service_rental_agreement_code);
		}

		$data['all_services'] = $this->services_model->get_all_services('001');
		$data['all_services_filtered'] = $this->services_model->get_all_services('001', true);

		$data['currency_symbol'] = get_default_currency('symbol');
		if ($code != null) {
			$data['rental_agreement'] = $this->services_model->get_rental_agreement($code);
			$data['rental_agreement_details'] = $this->services_model->get_rental_agreement_details($data['rental_agreement']->service_rental_agreement_id);
			// Fetch uploaded files
			$data['field_report_info'] = $data['rental_agreement']; // If applicable
			$data['uploaded_files'] = $this->services_model->get_rental_agreement_files($data['rental_agreement']->service_rental_agreement_id);
			$service_rental_agreement_code = array(
				'service_rental_agreement_code' => $data['rental_agreement']->service_rental_agreement_code,
			);
			$this->session->set_userdata($service_rental_agreement_code);
			if (($data['rental_agreement']->status == 1 or $data['rental_agreement']->status == 2) or !empty($data['rental_agreement']->invoice_rel_id) and $data['rental_agreement']->invoice_rel_id > 0) {
				redirect(admin_url('services/new_rental_agreement'));
			}
		}

		$data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');
		// echo json_encode($data['staff']); exit;
		// view page
		$data['title'] = _l('add_service_rental_agreement');
		$this->load->view('admin/services/service_rental_agreements_form', $data);
	}


	public function save_rental_agreement()
	{

		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'create')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		$data_service['service_rental_agreement_code'] = $this->input->post('service_rental_agreement_code', true);
		$data_service['clientid'] = $this->input->post('clientid', true);
		$data_service['start_date'] = to_sql_date($this->input->post('start_date', true));
		$data_service['end_date'] = to_sql_date($this->input->post('end_date', true));
		$data_service['rental_agreement_note'] = strip_tags($this->input->post('rental_agreement_note', true));
		$data_service['received_by'] = get_staff_user_id();
		$data_service['status'] = 0;
		$data_service['field_operator'] = $this->input->post('field_operator', true);
		$data_service['site_name'] = $this->input->post('site_name', true);

		// Handle file uploads
		$uploaded_files = [];

		// Process existing files (from hidden inputs)
		if (!empty($this->input->post('report_files'))) {
			// Get existing files from the POST data
			$existing_files = $this->input->post('report_files');

			// Filter out any empty values from existing files
			$existing_files = array_filter($existing_files, function ($file) {
				return !empty($file); // Only keep non-empty file names
			});

			// Merge with the newly uploaded files
			$uploaded_files = array_merge($existing_files, $uploaded_files);
		}

		// Configure file upload settings
		$config = [
			'upload_path'   => './modules/bizit_services_msl/uploads/reports/',
			'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx',
			'max_size'      => 4096, // Max file size in KB (4MB)
		];
		$this->load->library('upload', $config);

		// Process new service files (from file input fields)
		if (!empty($_FILES['service_files'])) {
			$this->load->library('upload');
			$files = $_FILES['service_files'];

			for ($i = 0; $i < count($files['name']); $i++) {
				if ($files['name'][$i] != '') {
					$_FILES['file']['name'] = $files['name'][$i];
					$_FILES['file']['type'] = $files['type'][$i];
					$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['file']['error'] = $files['error'][$i];
					$_FILES['file']['size'] = $files['size'][$i];

					if ($this->upload->do_upload('file')) {
						$file_data = $this->upload->data();
						$uploaded_files[] = $file_data['file_name'];
					} else {
						// Handle upload failure (if needed)
						set_alert('danger', 'File upload failed');
						redirect(admin_url('services/new_rental_agreement'));
					}
				}
			}
		}
		$data_service['report_files'] = json_encode($uploaded_files);


		// Continue with saving the rental agreement
		$field_operator_id = $data_service['field_operator'];
		$site_name = $data_service['site_name'];

		$serviceid = $this->input->post('serviceid', true);
		$service_price = $this->input->post('service_price', true);
		$edit_id = $this->input->post('edit_id', true);

		$service_rental_agreement_details_id_edit = $this->input->post('service_rental_agreement_details_id', true);

		for ($i = 0; $i < sizeof($serviceid); $i++) {
			if ($serviceid[$i] != null && $service_price[$i] != null) {
				$data_service_rental_agreement_details['service_info'][] = array('serviceid' => $serviceid[$i], 'price' => $service_price[$i], 'service_rental_agreement_details_id' => (isset($service_rental_agreement_details_id_edit[$i]) ? $service_rental_agreement_details_id_edit[$i] : ''));
			}
		}

		if (!empty($data_service['clientid'])) {
			//Entry into tbl_service_rental_agreement
			$service_rental_agreement = false;
			$message = '';
			if (empty($edit_id)) {
				$service_rental_agreement = $this->services_model->add_rental_agreement($data_service);
				$data_service['service_rental_agreement_id'] = $service_rental_agreement;
				if ($field_operator_id != null) {
					rental_agreement_notifications($field_operator_id, get_staff_user_id(), $data_service['service_rental_agreement_code'], 'field_operator_notice', $site_name);
				}
				$message = 'Service rental agreement added successfully';
			} else {
				$data_service['service_rental_agreement_id'] = $edit_id;
				unset($data_service['received_by']);

				$service_info = $this->db->where('service_rental_agreement_id', $edit_id)->get('tblservice_rental_agreement')->row();

				$service_rental_agreement = $this->services_model->edit_rental_agreement($data_service);
				if ($field_operator_id != null) {
					if ($service_info->field_operator != $field_operator_id) {
						rental_agreement_notifications($field_operator_id, get_staff_user_id(), $data_service['service_rental_agreement_code'], 'field_operator_notice', $site_name);
						if ($service_info->field_operator != null) {
							rental_agreement_notifications($service_info->field_operator, get_staff_user_id(), $data_service['service_rental_agreement_code'], 'field_operator_removal_notice', $service_info->site_name);
						}
					}
				}
				$message = 'Service rental agreement updated successfully';
			}

			if ($service_rental_agreement or $data_service_rental_agreement_details['service_info']) {
				// Entry into tbl_service_rental_agreement_details
				foreach ($data_service_rental_agreement_details['service_info'] as $item) {
					$data_service_details['service_rental_agreement_id'] = $data_service['service_rental_agreement_id'];
					$data_service_details['serviceid'] = $item['serviceid'];
					$data_service_details['price'] = $item['price'];
					if (empty($edit_id)) {
						$this->services_model->add_rental_agreement_details($data_service_details);
					} else {
						if (!empty($item['service_rental_agreement_details_id'])) {
							$this->services_model->edit_rental_agreement_details($data_service_details, $item['service_rental_agreement_details_id']);
						} else {
							$this->services_model->add_rental_agreement_details($data_service_details);
						}
					}
				}

				set_alert('success', $message);
				$url_red = '/1/' . $data_service['service_rental_agreement_code'];
			} else {
				set_alert('danger', 'Service rental agreement modification failed');
				$url_red = '';
			}
		} else {
			set_alert('warning', 'Kindly select a client!');
		}

		// Redirect to the rental agreement page
		redirect(admin_url('services/new_rental_agreement' . $url_red));
		$url_red = '';
	}


	public function view_rental_agreement($code = null, $view_reports = false)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		// Get the service rental agreement information based on the code
		$data['service_info'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();

		// Ensure $service_info exists before proceeding
		if (empty($data['service_info'])) {
			// Redirect or show an error if the service info is not found
			show_404();
		}

		// Handle AJAX request for viewing reports
		if ($view_reports && $this->input->is_ajax_request()) {
			$this->app->get_table_data(
				module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report'),
				array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id, 'service_rental_agreement_code' => $code)
			);
			exit;
		}

		// Fetch the invoice status based on the service info's invoice_rel_id
		$invoice_status = $this->db->select('status')
			->where('id', $data['service_info']->invoice_rel_id)
			->get('tblinvoices')
			->row();

		// Pass the invoice status to the view
		$data['invoice_status'] = $invoice_status;

		// Fetch the count of reports associated with this rental agreement
		$data['reports_count'] = total_rows('tblfield_report', array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id));

		// Calculate rental days and actual rental days
		$start = new DateTime($data['service_info']->start_date);
		$end = new DateTime($data['service_info']->end_date);
		$interval = date_diff($start, $end);
		$data['rental_days'] = $interval->format('%a') - $data['service_info']->discounted_days;
		$data['actual_rental_days'] = $interval->format('%a');

		// Fetch service rental agreement details
		$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_duration_check, tblservices_module.penalty_rental_price, tblservice_type.name as category_name, tblservice_type.service_typeid')
			->where('service_rental_agreement_id', $data['service_info']->service_rental_agreement_id)
			->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')
			->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')
			->get('tblservice_rental_agreement_details')->result();

		// Pass additional data to the view
		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_rental_agreement_client'] = $this->clients_model->get($data['service_info']->clientid);
		$data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', 'create');

		$data['service_info'] = $this->db->where('service_rental_agreement_code', $code)
			->get('tblservice_rental_agreement')
			->row();

		// Set the page title
		$data['title'] = 'Service Rental Agreement View';

		// Load the view with the data
		$this->load->view('admin/services/view_rental_agreement', $data);
	}



	public function update_status()
	{
		// Check if the request is an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_404(); // Show a 404 error if not an AJAX request
		}

		// Get the POST data
		$service_rental_agreement_id = $this->input->post('service_rental_agreement_id');
		$status = $this->input->post('status');

		// Log incoming data for debugging
		log_message('info', 'Updating status for ID: ' . $service_rental_agreement_id . ' with status: ' . $status);

		// Check if ID and status are provided
		if ($service_rental_agreement_id && $status) {
			// Update the status in the database
			$this->db->where('service_rental_agreement_id', $service_rental_agreement_id);
			$update_status = $this->db->update('tblservice_rental_agreement', ['status' => $status]);

			// Check if the update was successful
			if ($update_status) {
				// Return a success response
				echo json_encode(['status' => 'success', 'message' => 'Status updated successfully!']);
			} else {
				// Log the failure for debugging
				log_message('error', 'Update status failed for ID: ' . $service_rental_agreement_id);
				echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
			}
		} else {
			// Return an error response for invalid input data
			echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
		}
	}

	/*** Service Rental Agreement Edit Field Operator  ***/
	public function service_rental_agreement_reasign_field_operator()
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		$service_rental_agreement_id = $this->input->post('service_rental_agreement_id', true);
		$field_operator_id = $this->input->post('field_operator', true);
		$service_info = $this->db->where('service_rental_agreement_id', $service_rental_agreement_id)->get('tblservice_rental_agreement')->row();

		$data = array();
		$data['service_rental_agreement_id'] = $service_rental_agreement_id;
		$data['service_rental_agreement_code'] = $service_info->service_rental_agreement_code;
		$data['field_operator'] = $field_operator_id;

		if ($service_info->status == 3 or $service_info->status == 0) {
			$service_rental_agreement = $this->services_model->edit_rental_agreement($data);
			if ($service_rental_agreement && $field_operator_id != null) {
				if ($service_info->field_operator != $field_operator_id) {
					rental_agreement_notifications($field_operator_id, get_staff_user_id(), $service_info->service_rental_agreement_code, 'field_operator_notice', $service_info->site_name);
					if ($service_info->field_operator != null) {
						rental_agreement_notifications($service_info->field_operator, get_staff_user_id(), $service_info->service_rental_agreement_code, 'field_operator_removal_notice', $service_info->site_name);
					}
				}
			}
		}

		if ($this->db->affected_rows() > 0) {
			set_alert('success', _l('field_operator') . ' change successful');
			redirect(admin_url('services/view_rental_agreement/' . $service_info->service_rental_agreement_code));
		} else {
			redirect(admin_url('services/view_rental_agreement/' . $service_info->service_rental_agreement_code));
		}
	}

	/*** Service Rental Agreement Reconfirmation  ***/
	public function service_rental_agreement_re_confirmation()
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}
		$data['status'] = $this->input->post('status', true);

		$service_rental_agreement_id = $this->input->post('service_rental_agreement_id', true);
		$service_rental_agreement_code = $this->input->post('service_rental_agreement_code', true);

		if ($data['status'] == 3 or $data['status'] == 2 or $data['status'] == 1 or $data['status'] == 0) {
			//cancel service
			$this->db->where('service_rental_agreement_id', $service_rental_agreement_id)->update('tblservice_rental_agreement', $data);
		}

		if ($this->db->affected_rows() > 0) {
			set_alert('success', 'Status change successful');
			redirect(admin_url('services/view_rental_agreement/' . $service_rental_agreement_code));
		} else {
			redirect(admin_url('services/view_rental_agreement/' . $service_rental_agreement_code));
		}
	}

	public function rental_agreement_invoice_generation($code = null, $invoiceid = null)
	{

		$this->load->model('invoices_model');
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		//Get service
		$service_rental_agreement = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();

		//rental_agreement details
		$service_details = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.penalty_rental_price, tblservices_module.service_code, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $service_rental_agreement->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();

		$start = new DateTime($service_rental_agreement->start_date);
		$end = new DateTime($service_rental_agreement->end_date);
		$interval = date_diff($start, $end);
		$rental_days = $interval->format('%a') - $service_rental_agreement->discounted_days;

		// //if Invoiceid
		// if (!empty($invoiceid)) {
		// 	$this->db->where('rel_id', $invoiceid);
		// 	$this->db->where('rel_type', 'invoice');
		// 	$this->db->delete('tblitems_in');
		// }

		$i = 1;
		$newitems = array();
		$subtotal = 0;
		$rate = 0;
		foreach ($service_details as $key => $value) {
			$newitems[$i] = array(
				"order" => $i,
				"description" => $value->name,
				"long_description" => $value->category_name . ', Service Code => ' . $value->service_code,
				"serial" => "",
				"product_id" => "",
				"qty" => $rental_days,
				"unit" => "days",
				"rate" => $value->price,
				"taxable" => "1",
				"item_for" => "3",
			);
			$subtotal += $value->price * $rental_days;
			$rate += $value->penalty_rental_price;
			$i++;
		}
		//Extra days
		if ($service_rental_agreement->extra_days > 0) {
			$newitems[$i] = array(
				"order" => $i,
				"description" => 'Extra Days',
				"long_description" => 'Accumulated Equipment cost for the extra days',
				"serial" => "",
				"product_id" => "",
				"qty" => $service_rental_agreement->extra_days,
				"unit" => "days",
				"rate" => $rate,
				"taxable" => "1",
				"item_for" => "3",
			);

			$subtotal = $subtotal + ($rate * $service_rental_agreement->extra_days);
		}

		//Client Details
		$service_rental_agreement_client = $this->clients_model->get($service_rental_agreement->clientid);

		//Invoice Number
		$next_invoice_number = get_option('next_invoice_number');
		$format = get_option('invoice_number_format');
		$prefix = get_option('invoice_prefix');
		if ($format == 1) {
			// Number based
			$__number = $next_invoice_number;
		} else {
			$__number = $next_invoice_number;
			$prefix = $prefix . '<span id="prefix_year">' . date('Y') . '</span>/';
		}
		$_invoice_number = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);

		//Payment Modes
		$this->load->model('payment_modes_model');
		$payment_modes = $this->payment_modes_model->get('', array(
			'expenses_only !=' => 1,
		));
		$allowed_payment_modes = array();
		if (count($payment_modes) > 0) {
			foreach ($payment_modes as $mode) {
				if ($mode['selected_by_default'] == 1) {
					$allowed_payment_modes[] = $mode['id'];
				}
			}
		}

		//Generate Invoice Data
		$invoice_data = array(
			"save_as_draft" => "true",
			"clientid" => $service_rental_agreement_client->userid,
			"invoice_for" => "3",
			"project_id" => "",
			"billing_street" => $service_rental_agreement_client->billing_street,
			"billing_city" => $service_rental_agreement_client->billing_city,
			"billing_state" => $service_rental_agreement_client->billing_state,
			"billing_zip" => $service_rental_agreement_client->billing_zip,
			"billing_country" => $service_rental_agreement_client->billing_country,
			//"include_shipping" => "on",
			"show_shipping_on_invoice" => "on",
			"shipping_street" => $service_rental_agreement_client->shipping_street,
			"shipping_city" => $service_rental_agreement_client->shipping_city,
			"shipping_state" => $service_rental_agreement_client->shipping_state,
			"shipping_zip" => $service_rental_agreement_client->shipping_zip,
			"shipping_country" => $service_rental_agreement_client->shipping_country,
			"number" => $_invoice_number,
			"date" => _d(date('Y-m-d')),
			"duedate" => get_option('invoice_due_after') != 0 ? _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d'))))) : "",
			"allowed_payment_modes" => $allowed_payment_modes,
			"currency" => get_default_currency('id'),
			"sale_agent" => "",
			"recurring" => "0",
			"discount_type" => "",
			"repeat_every_custom" => "1",
			"repeat_type_custom" => "day",
			"adminnote" => "",
			"task_select" => "",
			"show_quantity_as" => "1",
			"description" => "",
			"long_description" => "",
			"quantity" => "1",
			"unit" => "",
			"rate" => "",
			"newitems" => $newitems,
			"subtotal" => $subtotal,
			"discount_percent" => "0",
			"discount_total" => "0",
			"adjustment" => "0.00",
			"total" => $subtotal,
			"task_id" => "",
			"expense_id" => "",
			"clientnote" => get_option('predefined_clientnote_invoice'),
			"terms" => get_option('predefined_terms_invoice'),
		);
		//echo '<pre>'.json_encode($invoice_data); exit;

		if (empty($invoiceid)) {
			$id = $this->invoices_model->add($invoice_data);
			if ($id) {
				$this->db->where('service_rental_agreement_id', $service_rental_agreement->service_rental_agreement_id)->update('tblservice_rental_agreement', array('invoice_rel_id' => $id));
				set_alert('success', _l('added_successfully', _l('invoice')));
				redirect(admin_url('services/view_rental_agreement/' . $code));
			}
		} else {
			unset($invoice_data['save_as_draft']);
			$id = $this->invoices_model->update($invoice_data, $invoiceid);
			return $id;
		}
	}


	// Helper function to get default payment modes
	private function get_default_payment_modes()
	{
		$this->load->model('payment_modes_model');
		$payment_modes = $this->payment_modes_model->get('', ['expenses_only !=' => 1]);

		$allowed_payment_modes = [];
		if (count($payment_modes) > 0) {
			foreach ($payment_modes as $mode) {
				if ($mode['selected_by_default'] == 1) {
					$allowed_payment_modes[] = $mode['id'];
				}
			}
		}

		return $allowed_payment_modes;
	}


	/*** Delete service rental agreement price ***/
	public function delete_service_rental_agreement_price($id, $code)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}
		$deleted = $this->services_model->delete_rental_agreement_details($id, $code);
		if ($deleted) {
			set_alert('success', 'Successfully deleted service');
		} else {
			set_alert('warning', 'Failed to delete service');
		}
		redirect(admin_url('services/new_rental_agreement/1/' . $code));
	}

	public function return_rental($code, $invoiceid)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		$data_service['extra_days'] = $this->input->post('extra_days', true);
		$data_service['discounted_days'] = $this->input->post('discounted_days', true);
		$data_service['actual_date_returned'] = to_sql_date($this->input->post('actual_date_returned', true));
		$switch_to_inv = $this->input->post('switch_to_inv', true);

		//echo $switch_to_inv; exit;

		$this->db->where('service_rental_agreement_code', $code)->update('tblservice_rental_agreement', $data_service);

		if ($this->db->affected_rows() > 0) {

			//Change THE STATUS of Rental Equipments
			//######################################################################################

			$service_ID = $this->db->select('tblservice_rental_agreement_details.serviceid', false)
				->join('tblservice_rental_agreement', 'tblservice_rental_agreement.service_rental_agreement_id = tblservice_rental_agreement_details.service_rental_agreement_id', 'left')
				->where('tblservice_rental_agreement.invoice_rel_id', $invoiceid)
				->get('tblservice_rental_agreement_details')->result();
			//Set to Not-Hired
			$rental_status = 'Not-Hired';
			if (!empty($service_ID)) {
				foreach ($service_ID as $key => $value) {
					$this->db->where('serviceid', $value->serviceid);
					$this->db->update('tblservices_module', array('rental_status' => $rental_status));
				}
			}

			//######################################################################################

			set_alert('success', _l('updated_successfully', _l('rental_agreement')));
			if (empty($switch_to_inv)) {
				redirect(admin_url('services/view_rental_agreement/' . $code));
			} else {
				redirect(admin_url('invoices/list_invoices#' . $invoiceid));
			}
		} else {
			if (empty($switch_to_inv)) {
				redirect(admin_url('services/view_rental_agreement/' . $code));
			} else {
				redirect(admin_url('invoices/list_invoices#' . $invoiceid));
			}
		}
	}

	/*** Rental Calender  ***/
	public function rental_calendar()
	{
		$data['rental_details'] = $this->services_model->get_calendar_rental_details();
		//echo '<pre>'.json_encode($data); exit;
		$data['title'] = 'Rental Calender';
		$this->load->view('admin/services/rental_agreements_calendar', $data);
	}

	public function rental_agreement_pdf($code = null)
	{
		if (!has_permission(BIZIT_SERVICES_MSL . '_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL . '_rental_agreement');
		}

		if (empty($code)) {
			redirect(admin_url('services/view_rental_agreement/' . $code));
		}

		$rental_agreement_number = get_option('service_rental_agreement_prefix') . $code;

		//get service
		$data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
		//rental_agreement details
		$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();
		$data['service_info'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();

		//$data['currency_symbol'] = get_default_currency( 'symbol' );
		$data['service_rental_agreement_client'] = $this->clients_model->get($data['service_rental_agreement']->clientid);

		try {
			$pdf = service_rental_agreement_pdf1($data);
		} catch (Exception $e) {
			$message = $e->getMessage();
			echo $message;
			if (strpos($message, 'Unable to get the size of the image') !== FALSE) {
				show_pdf_unable_to_get_image_size_error();
			}
			die;
		}

		$type = 'I';
		if ($this->input->get('print')) {
			$type = 'I';
		}

		$pdf->Output('SERVICE RENTAL AGREEMENT FORM' . mb_strtoupper(slug_it($rental_agreement_number)) . '.pdf', $type);
	}

	public function manage_field_report()
	{
		if ($this->input->post()) {
			$data = $this->input->post();

			// Initialize an array to store the files
			$uploaded_files = [];

			// Add existing files from the form
			if (isset($data['report_files']) && is_array($data['report_files'])) {
				$uploaded_files = $data['report_files']; // Retain existing files
			}

			// Check if new files are uploaded
			if (isset($_FILES['service_files']) && !empty($_FILES['service_files']['name'][0])) {
				$files = $_FILES['service_files'];

				// Configure upload settings
				$config['upload_path'] = './modules/bizit_services_msl/uploads/reports/';
				$config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx'; // Allowed file types
				$config['max_size'] = 2048; // Max file size in KB
				$this->load->library('upload', $config);

				// Loop through and upload each file
				for ($i = 0; $i < count($files['name']); $i++) {
					$_FILES['file']['name'] = $files['name'][$i];
					$_FILES['file']['type'] = $files['type'][$i];
					$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
					$_FILES['file']['error'] = $files['error'][$i];
					$_FILES['file']['size'] = $files['size'][$i];

					// Generate a unique name for the file
					$config['file_name'] = time() . '_' . $_FILES['file']['name'];
					$this->upload->initialize($config);

					if ($this->upload->do_upload('file')) {
						$upload_data = $this->upload->data();
						$uploaded_files[] = $upload_data['file_name']; // Append new file name
					} else {
						// Handle upload error
						$upload_error = $this->upload->display_errors();
						set_alert('danger', $upload_error);
						redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
						return;
					}
				}
			}

			// Save files as JSON in the `report_files` column
			$data['report_files'] = json_encode($uploaded_files);

			// Save or update record
			if (!isset($data['field_report_id'])) {
				// Insert new report
				$id = $this->services_model->add_field_report($data);
				if ($id) {
					set_alert('success', _l('added_successfully', _l('field_report')));
					redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
				}
			} else {
				// Update existing report
				$success = $this->services_model->edit_field_report($data);
				if ($success) {
					set_alert('success', _l('updated_successfully', _l('field_report')));
					redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
				}
			}
		}
	}

	public function upload_file($type, $type_id, $upload = false)
	{
		$data['report_code'] = get_field_value('tblfield_report', array('field_report_id' => $type_id), 'report_code');
		$data['field_report_info'] = $this->services_model->get_field_report($data['report_code']);

		if (!$upload) {
			if ($this->input->is_ajax_request()) {
				$this->load->view('admin/services/report_files', $data);
			}
		} else {
			handle_service_report_attachments($type, $type_id);
		}
	}

	/**
	 * [manage_files description]
	 * @param  [type] $purchase_id [description]
	 * @return [type]              [description]
	 */
	public function manage_files($type, $type_id)
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_report_files', array('type' => $type, 'type_id' => $type_id)));
		}
		return false;
	}

	/**
	 * [delete_file description]
	 * @param  [type] $id      [description]
	 * @param  string $preview [description]
	 * @return [type]          [description]
	 */
	public function delete_file($id, $type)
	{
		$this->db->where('id', $id);
		$this->db->where('rel_type', $type);
		$file = $this->db->get('tblfiles')->row();
		$success = false;
		$message = null;

		if ($file->staffid == get_staff_user_id() || is_admin()) {
			$success = $this->services_model->delete_file($id, $type, $file->rel_id, $file->file_name);
			if ($success) {
				$message = _l('deleted', _l($type) . ' file');
			} else {
				$message = _l('problem_deleting', _l($type) . ' file');
			}
		} else {
			$message = _l('problem_deleting', _l($type) . ' file');
		}

		echo json_encode(array(
			'success' => $success,
			'message' => $message,
		));
	}

	/**
	 * [delete_field_report description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function delete_field_report($id, $code)
	{
		$success = $this->services_model->delete_field_report($id);
		if ($success) {
			$message = _l('deleted', _l('field_report'));
			set_alert('success', $message);
		} else {
			$message = _l('problem_deleting', _l('field_report'));
			set_alert('warning', $message);
		}

		redirect(admin_url('services/view_rental_agreement/' . $code));
	}

	public function manage_field_report_appr_rej()
	{
		if (has_permission(BIZIT_SERVICES_MSL . '_rental_agreement_field_report', '', 'view')) {
			if ($this->input->post()) {
				$data = $this->input->post();

				if (isset($data['aprv_rej']) && $data['aprv_rej'] == "1") {
					$data['approved_by'] = get_staff_user_id();
					$data['approval_remarks'] = $data['aprv_rej_remark'];
					$data['status'] = 4;
				} else if (isset($data['aprv_rej']) && $data['aprv_rej'] == "0") {
					$data['rejected_by'] = get_staff_user_id();
					$data['rejection_remarks'] = $data['aprv_rej_remark'];
					$data['status'] = 3;
				}

				unset($data['aprv_rej']);
				unset($data['aprv_rej_remark']);

				$success = $this->services_model->edit_field_report($data);
				if ($success) {
					$message = _l('updated_successfully', _l('field_report'));
					set_alert('success', $message);
					redirect(admin_url('services/field_report/view/' . $data['report_code']), 'refresh');
				}
			}
		} else {
			header('HTTP/1.0 400 Bad error');
			echo _l('access_denied');
			die;
		}
	}

	public function test($id)
	{
		$data = array();
		$this->load->view('admin/services/html_certificate', $data);
	}
}
