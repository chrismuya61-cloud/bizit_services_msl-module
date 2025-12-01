<?php defined('BASEPATH') or exit('No direct script access allowed');

class Compensation_model extends App_Model {

    public function get_all_compensation_rates() {
        return $this->db->select('t1.*, t2.name as service_name, CONCAT(t3.firstname, " ", t3.lastname) as staff_name')
            ->from('tblstaff_service_rates t1')
            ->join('tblservices_module t2', 't2.serviceid = t1.serviceid')
            ->join('tblstaff t3', 't3.staffid = t1.staffid')
            ->get()->result_array();
    }

    public function update_compensation_rate($data) {
        $staff_ids = is_array($data['staffid']) ? $data['staffid'] : [$data['staffid']];
        foreach ($staff_ids as $staffid) {
            $d = ['staffid'=>$staffid, 'serviceid'=>$data['serviceid'], 'rate'=>$data['rate'], 'allowance_type'=>$data['allowance_type'] ?? 'unit'];
            if (isset($data['rate_id']) && count($staff_ids)===1) { $this->db->where('id', $data['rate_id'])->update('tblstaff_service_rates', $d); continue; }
            
            $exists = $this->db->where('staffid',$staffid)->where('serviceid',$data['serviceid'])->get('tblstaff_service_rates')->row();
            if($exists) $this->db->where('id',$exists->id)->update('tblstaff_service_rates', $d);
            else $this->db->insert('tblstaff_service_rates', $d);
        }
        return true;
    }

    public function get_staff_compensation_data($start, $end) {
        $services = $this->db->query("SELECT t1.staffid, t3.price AS revenue, t3.serviceid, t5.name FROM ".db_prefix()."staff t1 JOIN tblservice_request t2 ON t2.received_by=t1.staffid JOIN tblservice_request_details t3 ON t3.service_request_id=t2.service_request_id JOIN tblservices_module t5 ON t5.serviceid=t3.serviceid JOIN tblinvoices t4 ON t4.id=t2.invoice_rel_id WHERE t4.status=2 AND t2.drop_off_date BETWEEN '$start' AND '$end'")->result_array();
        $rentals = $this->db->query("SELECT t1.staffid, t4.total AS revenue, (DATEDIFF(t2.end_date, t2.start_date)+1) as days, t3.serviceid, t6.name FROM ".db_prefix()."staff t1 JOIN tblservice_rental_agreement t2 ON t2.field_operator=t1.staffid JOIN tblservice_rental_agreement_details t3 ON t3.service_rental_agreement_id=t2.service_rental_agreement_id JOIN tblservices_module t6 ON t6.serviceid=t3.serviceid JOIN tblinvoices t4 ON t4.id=t2.invoice_rel_id JOIN tblfield_report t5 ON t5.service_rental_agreement_id=t2.service_rental_agreement_id WHERE t4.status=2 AND t5.status>=2 AND t2.start_date BETWEEN '$start' AND '$end'")->result_array();

        $rates = []; foreach($this->get_all_compensation_rates() as $r) $rates[$r['staffid']][$r['serviceid']] = $r['rate'];
        $data = [];
        
        foreach($services as $s) {
            $sid=$s['staffid']; $rate=$rates[$sid][$s['serviceid']]??0;
            if(!isset($data[$sid])) $data[$sid] = ['staffid'=>$sid, 'service_allowance'=>0, 'rental_allowance'=>0, 'total_allowance'=>0, 'service_units'=>0, 'rental_days'=>0];
            $data[$sid]['service_allowance'] += $rate;
            $data[$sid]['total_allowance'] += $rate;
            $data[$sid]['service_units']++;
        }
        foreach($rentals as $r) {
            $sid=$r['staffid']; $rate=$rates[$sid][$r['serviceid']]??0;
            if(!isset($data[$sid])) $data[$sid] = ['staffid'=>$sid, 'service_allowance'=>0, 'rental_allowance'=>0, 'total_allowance'=>0, 'service_units'=>0, 'rental_days'=>0];
            $data[$sid]['rental_allowance'] += ($rate * $r['days']);
            $data[$sid]['total_allowance'] += ($rate * $r['days']);
            $data[$sid]['rental_days'] += $r['days'];
        }
        
        $CI=&get_instance(); $CI->load->model('staff_model');
        foreach($data as &$d) { 
            $staff = $CI->staff_model->get($d['staffid']); 
            $d['full_name'] = $staff->firstname.' '.$staff->lastname; 
        }
        return array_values($data);
    }

    public function get_analytics_data($start, $end) {
        $popular_services = $this->db->query("SELECT m.name, COUNT(d.serviceid) as total_requests FROM tblservice_request_details d JOIN tblservices_module m ON m.serviceid = d.serviceid JOIN tblservice_request r ON r.service_request_id = d.service_request_id WHERE r.drop_off_date BETWEEN '$start' AND '$end' GROUP BY d.serviceid ORDER BY total_requests DESC LIMIT 5")->result_array();
        $popular_rentals = $this->db->query("SELECT m.name, SUM(DATEDIFF(r.end_date, r.start_date)+1) as total_days FROM tblservice_rental_agreement_details d JOIN tblservices_module m ON m.serviceid = d.serviceid JOIN tblservice_rental_agreement r ON r.service_rental_agreement_id = d.service_rental_agreement_id WHERE r.start_date BETWEEN '$start' AND '$end' GROUP BY d.serviceid ORDER BY total_days DESC LIMIT 5")->result_array();
        return ['services' => $popular_services, 'rentals' => $popular_rentals];
    }

    public function get_dashboard_widgets() {
        $today = date('Y-m-d');
        $next_30 = date('Y-m-d', strtotime('+30 days'));

        return [
            'cal_overdue' => $this->db->where('next_calibration_date <', $today)->count_all_results('tblservices_calibration'),
            'cal_approaching' => $this->db->where('next_calibration_date >=', $today)->where('next_calibration_date <=', $next_30)->count_all_results('tblservices_calibration'),
            
            'rental_open' => $this->db->group_start()->where('status', 0)->or_where('status', 3)->group_end()->count_all_results('tblservice_rental_agreement'),
            'rental_closed' => $this->db->where('status', 2)->count_all_results('tblservice_rental_agreement'),
            
            'report_draft' => $this->db->where('status', 1)->count_all_results('tblfield_report'),
            'report_submitted' => $this->db->where('status', 2)->count_all_results('tblfield_report'),
            'report_approved' => $this->db->where('status', 4)->count_all_results('tblfield_report'),
        ];
    }
}