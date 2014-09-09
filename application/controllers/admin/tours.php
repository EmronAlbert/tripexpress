<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tours extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}
	function index(){
		$this->list_tours();
	}
	function list_tours(){
		$this->load->model('tour');
		$data['tours'] = $this->tour->show_tours();
		$data['main_content'] = 'backend/tours/tours';
		$data['title'] = 'Tours';
		$this->load->view('includes/template', $data);
	}
	function add_tour(){
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('from', 'Departure city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('to', 'Arrival city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('from_start_date', 'Start date', 'trim|required');
		$this->form_validation->set_rules('from_start_time', 'Start time', 'trim|required');
		$this->form_validation->set_rules('return_start_date', 'Return date', 'trim|required');
		$this->form_validation->set_rules('return_start_time', 'Return time', 'trim|required');
		$this->form_validation->set_rules('available_seats', 'Available seats', 'trim|required');
		$this->form_validation->set_rules('start_price', 'Start price', 'trim|required');
		$this->form_validation->set_rules('return_price', 'Return Price', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model('tour');
			$data['cities'] = $this->tour->list_cities();
			$data['main_content'] = 'backend/tours/add_tour';
			$data['title'] = 'Create tour';
			$this->load->view('includes/template', $data);
		}
		else
		{
			$this->load->model('tour');
			$data = $this->input->post();
			$this->tour->create_tour($data);
			$this->session->set_flashdata('message', 'Tour successfully created');
			redirect('admin/tours', 'refresh');
		}
	}
	function edit_tour(){

	}
	function delete_tour(){
		
	}
	
	public function _citynull_check($str)
	{
		if ($str == '0')
		{
			$this->form_validation->set_message('_citynull_check', 'The %s field can not be empty');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	private function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if (!isset($is_logged_in) || $is_logged_in != true) {
			echo 'login please';
			die();
		}
	}
}
?>