<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Services extends AdminController {
	public function __construct() {
		parent::__construct();
		$this->load->model('services_model');
		$this->load->model('clients_model');
	}

	/* List all available items */
	public function index() {

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

	public function sales_list() {

		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_sales_list'));;
		}

		$data['title'] = _l('als_services_sales_list');
		$this->load->view('admin/services/manage_sales_list', $data);
	}

	/* Edit or update services / ajax request /*/
	public function manage() {
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
	public function delete($id) {
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

	/* Edit or update category / ajax request /*/
	public function category_manage() {
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
	public function delete_category($id) {
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

	public function service_category() {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/service_category'));;
		}
	}

	/* Change services status / active / inactive */
	public function change_service_status($id, $status) {
		if ($this->input->is_ajax_request()) {
			$this->services_model->change_service_status($id, $status);
		}
	}

	/* Change service category status / active / inactive */
	public function change_service_category_status($id, $status) {
		if ($this->input->is_ajax_request()) {
			$this->services_model->change_service_category_status($id, $status);
		}
	}

	//Get Services
	public function get_services($id_code) {
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
	public function get_service_by_code($service_code) {
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
	public function get_service_by_id($serviceid) {
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
	public function getNextServiceCode($id_code = null) {
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
	public function getNextServiceCategoryCode() {
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
	public function requests() {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_requests'));;
		}

		$data['title'] = _l('als_services_requests');
		$this->load->view('admin/services/manage_requests', $data);
	}

/*** New Request ***/
	public function new_request($flag = null, $code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('Services');
		}
		if (!is_null($flag) and !is_numeric($flag)) {
			redirect(admin_url('services/new_request'));
		}

		if (empty($flag)) {
			$request_session = array('service_request_code' => '');
			$this->session->unset_userdata($request_session);
			$random_number = rand(10000000, 99999);

			$q = $this->db->get('tblservice_request')->result();
			if (!empty($q)) {
				$last = end($q);
				$new_index = $last->service_request_id;
				$random_number = $random_number . $new_index;
			}

			$service_request_code = array(
				'service_request_code' => $random_number,
			);
			$this->session->set_userdata($service_request_code);
		}

		$data['all_services'] = $this->services_model->get_all_services('002');
		$data['currency_symbol'] = get_default_currency('symbol');
		//echo '<pre>'.json_encode($data); exit;
		if ($code != null) {
			$data['request'] = $this->services_model->get_request($code);
			$data['request_details'] = $this->services_model->get_request_details($data['request']->service_request_id);
			$service_request_code = array(
				'service_request_code' => $data['request']->service_request_code,
			);
			$this->session->set_userdata($service_request_code);
			if (($data['request']->status == 1 or $data['request']->status == 2) or !empty($data['request']->invoice_rel_id) and $data['request']->invoice_rel_id > 0) {
				redirect(admin_url('services/new_request'));
			}
		}
		// view page
		$data['title'] = _l('add_service_request');
		$this->load->view('admin/services/service_requests_form', $data);
	}

/*** Save request ***/
	public function save_request() {

		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('Services');
		}
		$data_service['service_request_code'] = $this->input->post('service_request_code', true);
		$data_service['clientid'] = $this->input->post('clientid', true);
		$data_service['drop_off_date'] = to_sql_date($this->input->post('drop_off_date', true));
		$data_service['collection_date'] = to_sql_date($this->input->post('collection_date', true));
		$data_service['condition'] = $this->input->post('condition', true);
		$data_service['item_type'] = $this->input->post('type', true);
		$data_service['item_model'] = $this->input->post('model', true);
		$data_service['item_make'] = $this->input->post('make', true);
		$data_service['serial_no'] = $this->input->post('serial_no', true);
		$data_service['service_note'] = strip_tags($this->input->post('service_note', true));
		$data_service['received_by'] = get_staff_user_id();
		$data_service['status'] = 0;

		$serviceid = $this->input->post('serviceid', true);
		$service_price = $this->input->post('service_price', true);
		$edit_id = $this->input->post('edit_id', true);

		$service_request_details_id_edit = $this->input->post('service_request_details_id', true);

		for ($i = 0; $i < sizeof($serviceid); $i++) {
			if ($serviceid[$i] != null && $service_price[$i] != null) {
				$data_service_request_details['service_info'][] = array('serviceid' => $serviceid[$i], 'price' => $service_price[$i], 'service_request_details_id' => (isset($service_request_details_id_edit[$i]) ? $service_request_details_id_edit[$i] : ''));
			}
		}
		//echo '<pre>'.json_encode($data_service_request_details); exit;
		if (!empty($data_service['clientid'])) {
			//Entry into tbl_service_request
			$service_request = false;
			$message = '';
			if (empty($edit_id)) {
				$service_request = $this->services_model->add_request($data_service);
				$data_service['service_request_id'] = $service_request;
				$message = 'Service request added successfully';
			} else {
				$data_service['service_request_id'] = $edit_id;
				$service_request = $this->services_model->edit_request($data_service);
				$message = 'Service request updated successfully';
			}
			if ($service_request or $data_service_request_details['service_info']) {
				//Entry into tbl_service_request_details
				foreach ($data_service_request_details['service_info'] as $item) {
					$data_service_details['service_request_id'] = $data_service['service_request_id'];
					$data_service_details['serviceid'] = $item['serviceid'];
					$data_service_details['price'] = $item['price'];
					if (empty($edit_id)) {
						$this->services_model->add_request_details($data_service_details);
					} else {
						if (!empty($item['service_request_details_id'])) {
							$this->services_model->edit_request_details($data_service_details, $item['service_request_details_id']);
						} else {
							$this->services_model->add_request_details($data_service_details);
						}
					}
				}
				set_alert('success', $message);
				$url_red = '/1/' . $data_service['service_request_code'];
			} else {
				set_alert('danger', 'Service request modification failed');
				$url_red = '';
			}
		} else {
			set_alert('warning', 'Kindly select a client!');
		}
		//display request
		redirect(admin_url('services/new_request' . $url_red));
		$url_red = '';
	}

/*** Delete service price ***/
	public function delete_service_price($id, $code) {
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

	public function view_request($code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}
		//get service
		$data['service_info'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();
		//request details
		$data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $data['service_info']->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();

		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_request_client'] = $this->clients_model->get($data['service_info']->clientid);
		if (empty($data['service_info'])) {
			//redirect manage requests
		}
		//echo '<pre>'.json_encode($data); exit;
		$data['title'] = 'Service Request View';
		$this->load->view('admin/services/view_request', $data);
	}

/*** Service Request Reconfirmation  ***/
	public function service_re_confirmation() {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}
		$data['status'] = $this->input->post('status', true);

		$service_request_id = $this->input->post('service_request_id', true);
		$service_request_code = $this->input->post('service_request_code', true);

		if ($data['status'] == 3 or $data['status'] == 1 or $data['status'] == 0) {
			//cancel service
			$this->db->where('service_request_id', $service_request_id)->update('tblservice_request', $data);

		}

		if ($this->db->affected_rows() > 0) {
			set_alert('success', 'Status change successful');
			redirect(admin_url('services/view_request/' . $service_request_code));
		}

	}

	public function report($flag = null, $code = null) {

		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		if (empty($flag)) {
			redirect(admin_url('services/requests'));
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
			if (is_numeric($flag) == false) {
				redirect(admin_url('services/requests'));
			}
			$data['service_info'] = $this->db->where(array('service_request_code' => $code))->get('tblservice_request')->row();
			$data['service_request_code'] = $code;
		}

		//echo '<pre>'.json_encode($data); exit;
		if ($flag == 'view') {
			$data['title'] = 'View Service Calibration Report';
			$this->load->view('admin/services/report_calibration_view', $data);
		} else if ($flag == 'pdf') {
			$data['title'] = 'Service Calibration Report PDF';
			$request_number = get_option('service_request_prefix') . $code;
			try {
				$pdf = service_request_report_pdf($data);
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

			$pdf->Output('SERVICE REPORT' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);
		} else if ($flag == 'edit') {
			$data['title'] = 'Edit Service Calibration Report';
			$this->load->view('admin/services/new_report', $data);
		} else {
			$data['title'] = 'Add New Service Calibration Report';
			$this->load->view('admin/services/new_report', $data);
		}
	}

/*** Save request ***/
	public function save_calibration() {

		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create') and !has_permission(BIZIT_SERVICES_MSL, '', 'edit')) {
			access_denied('Services');
		}

		$calibration_instrument = $this->input->post('calibration_instrument', true);

		//Total Station or Theodolite
		if ($calibration_instrument == 'Total Station' or $data['calibration_instrument'] == 'Theodolite'):

			$data['i_edm_a_1'] = $this->input->post('i_edm_a_1');
			$data['i_edm_a_2'] = $this->input->post('i_edm_a_2');
			$data['i_edm_a_3'] = $this->input->post('i_edm_a_3');
			$data['i_edm_b_1'] = $this->input->post('i_edm_b_1');
			$data['i_edm_b_2'] = $this->input->post('i_edm_b_2');
			$data['i_edm_b_3'] = $this->input->post('i_edm_b_3');
			$data['ii_edm_a_1'] = $this->input->post('ii_edm_a_1');
			$data['ii_edm_a_2'] = $this->input->post('ii_edm_a_2');
			$data['ii_edm_a_3'] = $this->input->post('ii_edm_a_3');
			$data['ii_edm_b_1'] = $this->input->post('ii_edm_b_1');
			$data['ii_edm_b_2'] = $this->input->post('ii_edm_b_2');
			$data['ii_edm_b_3'] = $this->input->post('ii_edm_b_3');

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

		endif;

		//Level
		if ($calibration_instrument == 'Level'):
			$data['i_backsight_a'] = $this->input->post('i_backsight_a', true);
			$data['i_foresight_b'] = $this->input->post('i_foresight_b', true);

			$data['ii_backsight_a'] = $this->input->post('ii_backsight_a', true);
			$data['ii_foresight_b'] = $this->input->post('ii_foresight_b', true);

			$data['iii_backsight_a'] = $this->input->post('iii_backsight_a', true);
			$data['iii_foresight_b'] = $this->input->post('iii_foresight_b', true);

			$data['iv_backsight_a'] = $this->input->post('iv_backsight_a', true);
			$data['iv_foresight_b'] = $this->input->post('iv_foresight_b', true);
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
		if ($service_calibration) {
			set_alert('success', 'Calibration report ' . $for . ' successful');
		} else {
			set_alert('warning', 'Calibration report failed');
		}

		//display request
		redirect(admin_url('services/report/edit/' . $service_code));
	}

	public function request_pdf($code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		if (empty($code)) {
			redirect(admin_url('services/requests'));
		}

		$request_number = get_option('service_request_prefix') . $code;

		//get service
		$data['service_request'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();
		//request details
		$data['service_details'] = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $data['service_request']->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();

		//$data['currency_symbol'] = get_default_currency( 'symbol' );
		$data['service_request_client'] = $this->clients_model->get($data['service_request']->clientid);

		try {
			$pdf = service_request_pdf($data);
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

		$pdf->Output('SERVICE REQUEST FORM' . mb_strtoupper(slug_it($request_number)) . '.pdf', $type);

	}

	public function request_invoice_generation($code = null) {

		$this->load->model('invoices_model');
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'create')) {
			access_denied('invoices');
		}

		//Get service
		$service_request = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		//request details
		$service_details = $this->db->select('tblservice_request_details.*, tblservices_module.name, tblservices_module.quantity_unit, tblservices_module.service_code, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_request_id', $service_request->service_request_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_request_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_request_details')->result();
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

		//Client Details
		$service_request_client = $this->clients_model->get($service_request->clientid);

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
			"clientid" => $service_request_client->userid,
			"invoice_for" => "3",
			"project_id" => "",
			"billing_street" => $service_request_client->billing_street,
			"billing_city" => $service_request_client->billing_city,
			"billing_state" => $service_request_client->billing_state,
			"billing_zip" => $service_request_client->billing_zip,
			"billing_country" => $service_request_client->billing_country,
			//"include_shipping" => "on",
			"show_shipping_on_invoice" => "on",
			"shipping_street" => $service_request_client->shipping_street,
			"shipping_city" => $service_request_client->shipping_city,
			"shipping_state" => $service_request_client->shipping_state,
			"shipping_zip" => $service_request_client->shipping_zip,
			"shipping_country" => $service_request_client->shipping_country,
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
			"recurring_ends_on" => "",
			"adminnote" => "",
			//"barcode" => "",
			"product_select" => "",
			"item_select" => "",
			"task_select" => "",
			"show_quantity_as" => "1",
			"service_select" => "",
			"invoice_services" => "",
			"description" => "",
			"long_description" => "",
			"serial" => "",
			"product_id" => "",
			"quantity" => "1",
			"unit" => "",
			"rate" => "",
			"taxable" => "",
			"item_for" => "",
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

		$id = $this->invoices_model->add($invoice_data);
		if ($id) {
			$this->db->where('service_request_id', $service_request->service_request_id)->update('tblservice_request', array('invoice_rel_id' => $id));
			set_alert('success', _l('added_successfully', _l('invoice')));
			redirect(admin_url('services/view_request/' . $code));
		}
	}

	public function certificate_pdf($code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL, '', 'view')) {
			access_denied('Services');
		}

		if (empty($code)) {
			redirect(admin_url('services/requests'));
		}

		$request_number = get_option('service_request_prefix') . $code;

		//get service
		$data['service_request'] = $this->db->where('service_request_code', $code)->get('tblservice_request')->row();

		$html = $this->load->view('admin/services/html_certificate', $data, true);
		$filename = 'CALIBRATION CERTIFICATE-' . mb_strtoupper(slug_it($request_number));
		$this->dpdf->pdf_create($html, $filename, 'view');

	}

//==========================================================
	//  RENTAL AGREEMENT
	//==========================================================

	public function rental_agreements() {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_rental_agreements'));;
		}

		$data['title'] = _l('als_services_for_hire');
		$this->load->view('admin/services/manage_rental_agreements', $data);
	}

	/*** New rental_agreement ***/
	public function new_rental_agreement($flag = null, $code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'create')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
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
			$service_rental_agreement_code = array(
				'service_rental_agreement_code' => $data['rental_agreement']->service_rental_agreement_code,
			);
			$this->session->set_userdata($service_rental_agreement_code);
			if (($data['rental_agreement']->status == 1 or $data['rental_agreement']->status == 2) or !empty($data['rental_agreement']->invoice_rel_id) and $data['rental_agreement']->invoice_rel_id > 0) {
				redirect(admin_url('services/new_rental_agreement'));
			}
		}

		$data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', 'create');
		//echo json_encode($data['staff']); exit;
		// view page
		$data['title'] = _l('add_service_rental_agreement');
		$this->load->view('admin/services/service_rental_agreements_form', $data);
	}

/*** Save Rental Agreement ***/
	public function save_rental_agreement() {

		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'create')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
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
		//echo '<pre>'.json_encode($data_service_rental_agreement_details); exit;
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

				##########################################################################################################################
				/**
				 * Update Rental Agreement Details
				 * @var [type]
				 */
				$service_details_arr = $this->db->where('service_rental_agreement_id', $edit_id)->get('tblservice_rental_agreement_details')->result();
				//echo json_encode($service_details_arr); exit;
				//Set to Not-Hired
				foreach ($service_details_arr as $keys => $item_service) {
					$this->db->where('serviceid', $item_service->serviceid);
					$this->db->update('tblservices_module', array('rental_status' => 'Not-Hired'));
				}
				##########################################################################################################################
				//echo json_encode($data_service_rental_agreement_details['service_info']); exit;

				//Entry into tbl_service_rental_agreement_details
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
		//display rental_agreement
		redirect(admin_url('services/new_rental_agreement' . $url_red));
		$url_red = '';
	}

	public function view_rental_agreement($code = null, $view_reports = false) {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}

		//get service
		$data['service_info'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();

		if ($view_reports && $this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path(BIZIT_SERVICES_MSL, 'table/services_field_report', array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id, 'service_rental_agreement_code' => $code)));
			exit;
		}

		$data['reports_count'] = total_rows('tblfield_report', array('service_rental_agreement_id' => $data['service_info']->service_rental_agreement_id));

		$start = new DateTime($data['service_info']->start_date);
		$end = new DateTime($data['service_info']->end_date);
		$interval = date_diff($start, $end);
		$data['rental_days'] = $interval->format('%a') - $data['service_info']->discounted_days;
		$data['actual_rental_days'] = $interval->format('%a');

		//rental_agreement details
		$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name,tblservices_module.rental_duration_check,  tblservices_module.penalty_rental_price, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_info']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();

		$data['currency_symbol'] = get_default_currency('symbol');
		$data['service_rental_agreement_client'] = $this->clients_model->get($data['service_info']->clientid);
		if (empty($data['service_info'])) {
			//redirect manage rental_agreements
		}
		$data['staff'] = get_staff_with_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', 'create');
		//echo '<pre>'.json_encode($data); exit;
		$data['title'] = 'Service Rental Agreement View';
		$this->load->view('admin/services/view_rental_agreement', $data);
	}

	/*** Service Rental Agreement Edit Field Operator  ***/
	public function service_rental_agreement_reasign_field_operator() {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
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
	public function service_rental_agreement_re_confirmation() {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}
		$data['status'] = $this->input->post('status', true);

		$service_rental_agreement_id = $this->input->post('service_rental_agreement_id', true);
		$service_rental_agreement_code = $this->input->post('service_rental_agreement_code', true);

		if ($data['status'] == 3 or $data['status'] == 1 or $data['status'] == 0) {
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

	public function rental_agreement_invoice_generation($code = null, $invoiceid = null) {

		$this->load->model('invoices_model');
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}

		//Get service
		$service_rental_agreement = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();

		//rental_agreement details
		$service_details = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.penalty_rental_price, tblservices_module.service_code, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $service_rental_agreement->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();

		$start = new DateTime($service_rental_agreement->start_date);
		$end = new DateTime($service_rental_agreement->end_date);
		$interval = date_diff($start, $end);
		$rental_days = $interval->format('%a') - $service_rental_agreement->discounted_days;

		//if Invoiceid
		if (!empty($invoiceid)) {
			$this->db->where('rel_id', $invoiceid);
			$this->db->where('rel_type', 'invoice');
			$this->db->delete('tblitems_in');
		}

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

/*** Delete service rental agreement price ***/
	public function delete_service_rental_agreement_price($id, $code) {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}
		$deleted = $this->services_model->delete_rental_agreement_details($id, $code);
		if ($deleted) {
			set_alert('success', 'Successfully deleted service');
		} else {
			set_alert('warning', 'Failed to delete service');
		}
		redirect(admin_url('services/new_rental_agreement/1/' . $code));
	}

	public function return_rental($code, $invoiceid) {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'edit')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}

		$data_service['extra_days'] = $this->input->post('extra_days', true);
		$data_service['discounted_days'] = $this->input->post('discounted_days', true);
		$data_service['actual_date_returned'] = to_sql_date($this->input->post('actual_date_returned', true));
		$switch_to_inv = $this->input->post('switch_to_inv', true);

		//echo $switch_to_inv; exit;

		$this->db->where('service_rental_agreement_code', $code)->update('tblservice_rental_agreement', $data_service);

		if ($this->db->affected_rows() > 0) {
			$inv_update = $this->rental_agreement_invoice_generation($code, $invoiceid);

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
	public function rental_calendar() {
		$data['rental_details'] = $this->services_model->get_calendar_rental_details();
		//echo '<pre>'.json_encode($data); exit;
		$data['title'] = 'Rental Calender';
		$this->load->view('admin/services/rental_agreements_calendar', $data);
	}

	public function rental_agreement_pdf($code = null) {
		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement');
		}

		if (empty($code)) {
			redirect(admin_url('services/view_rental_agreement/' . $code));
		}

		$rental_agreement_number = get_option('service_rental_agreement_prefix') . $code;

		//get service
		$data['service_rental_agreement'] = $this->db->where('service_rental_agreement_code', $code)->get('tblservice_rental_agreement')->row();
		//rental_agreement details
		$data['service_details'] = $this->db->select('tblservice_rental_agreement_details.*, tblservices_module.name, tblservices_module.rental_serial, tblservice_type.name as category_name, tblservice_type.service_typeid')->where('service_rental_agreement_id', $data['service_rental_agreement']->service_rental_agreement_id)->join('tblservices_module', 'tblservices_module.serviceid = tblservice_rental_agreement_details.serviceid')->join('tblservice_type', 'tblservices_module.service_type_code = tblservice_type.type_code')->get('tblservice_rental_agreement_details')->result();

		//$data['currency_symbol'] = get_default_currency( 'symbol' );
		$data['service_rental_agreement_client'] = $this->clients_model->get($data['service_rental_agreement']->clientid);

		try {
			$pdf = service_rental_agreement_pdf($data);
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

	public function field_report($flag = null, $code = null, $approval = false) {

		if (!has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'view')) {
			access_denied(BIZIT_SERVICES_MSL.'_rental_agreement_field_report');
		}

		if ($this->input->is_ajax_request()) {
			if (isset($code)) {

				for ($index = 1; $index < 20; $index++) {
					if (total_rows('tblfield_report', array('report_code' => $code . '-' . $index)) == 0) {
						echo $code . '-' . $index;
						exit;
					}
				}
			}
		} else {
			if (empty($flag)) {
				redirect(admin_url('services/rental_agreements'));
			}

			if ($flag == '1' or $flag == 'edit' or $flag == 'view' or $flag == 'pdf') {
				if (empty($code)) {
					redirect(admin_url('services/rental_agreements'));
				}

				// if ($flag == "1") {
				// 	$this->services_model->add_field_report(['rental_agreement_code' => $code]);
				// }

				$data['field_report_info'] = $this->services_model->get_field_report($code);
				$data['service_request_client'] = $this->clients_model->get($data['field_report_info']->clientid);
				$code_arr = explode('-', $code);
				$data['service_info'] = $this->services_model->get_rental_agreement($code_arr[0]);
			} else {
				redirect(admin_url('services/rental_agreements'));
			}

			//echo '<pre>'.json_encode($data); exit;
			if ($flag == 'view') {
				$data['title'] = 'View Service Field Report';
				if ($approval and in_array(get_staff_user_id(), unserialize(get_option('field_report_approvers')))) {
					$data['report_approval'] = true;
				} else {
					$data['report_approval'] = false;
				}
				$this->load->view('admin/services/field_report_view', $data);
			} else if ($flag == 'pdf') {
				$data['title'] = 'Service Field Report PDF';
				echo "Not yet ready! <br>Coming Soon";
				/*$request_number = get_option('service_request_prefix').$code;
					            try {
					                $pdf            = service_request_report_pdf($data);
					            } catch ( Exception $e ) {
					                $message = $e->getMessage();
					                echo $message;
					                if ( strpos( $message, 'Unable to get the size of the image' ) !== FALSE ) {
					                    show_pdf_unable_to_get_image_size_error();
					                }
					                die;
					            }

					            $type           = 'I';
					            if ( $this->input->get( 'print' ) ) {
					                $type = 'I';
					            }

				*/
			} else {
				$data['title'] = 'Edit Service Field Report ' . get_option('service_rental_agreement_prefix') . $code;

				$this->load->view('admin/services/new_field_report', $data);
			}
		}

	}

	/* Edit or update services field report / ajax request /*/
	public function manage_field_report() {
		if (has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'view')) {
			if ($this->input->post()) {
				$data = $this->input->post();
				if (!isset($data['field_report_id'])) {
					//$this->check_if_exist('service_name','service',$data['service_name'],'Service Name');
					$id = $this->services_model->add_field_report($data);
					if ($id) {
						$message = _l('added_successfully', _l('field_report'));
						set_alert('success', $message);
						redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
					}
				} else {
					$success = $this->services_model->edit_field_report($data);
					if ($success) {
						$message = _l('updated_successfully', _l('field_report'));
						set_alert('success', $message);
						redirect(admin_url('services/field_report/edit/' . $data['report_code']), 'refresh');
					}
				}
			}
		} else {
			header('HTTP/1.0 400 Bad error');
			echo _l('access_denied');
			die;
		}
	}

	public function upload_file($type, $type_id, $upload = false) {
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
	public function manage_files($type, $type_id) {
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
	public function delete_file($id, $type) {
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
	public function delete_field_report($id, $code) {
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

	public function manage_field_report_appr_rej() {
		if (has_permission(BIZIT_SERVICES_MSL.'_rental_agreement_field_report', '', 'view')) {
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

	public function test($id) {
		$data = array();
		$this->load->view('admin/services/html_certificate', $data);
	}

}
