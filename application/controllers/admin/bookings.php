<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookings extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}
	function index(){
		$this->list_bookings();
	}
	function pdf($id)
	{
	     $this->load->helper('dompdf');
	     $this->load->helper('file'); 
	     $this->load->model('booking');
	     $data['booking'] = $this->booking->get_booking($id);
	     $data['booking_returned'] = $this->booking->get_booking_returned($id);
	     $data['company_info'] = $this->booking->get_company_info();
	     $data['id'] = $id;     
	     $html = $this->load->view('backend/bookings/pdf_generator', $data, true);
	     pdf_create($html, 'filename', TRUE);
	     
	     //$data = pdf_create($html, 'sss', TRUE);
	     //write_file('name', $data);
	     //if you want to write it to disk and/or send it as an attachment    
	}
	function list_bookings(){
		$this->load->library('pagination');
		$config['base_url'] = base_url().'admin/bookings/list_bookings';
		$config['total_rows'] = $this->db->count_all('bookings');
		$config['per_page'] = 10; 
		$config["uri_segment"] = 4;
		//pagination styling
		$config['num_tag_open'] = '<li>'; $config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href"#">'; $config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>'; $config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>'; $config['prev_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo;';
		$config['next_link'] = '&raquo;';
		//pagination styling
		$this->pagination->initialize($config);
		$this->load->model('booking');
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		//$data['bookings'] = $this->booking->show_bookings();
		$data['bookings'] = $this->booking->show_bookings($config['per_page'], $page);
		$data['links'] = $this->pagination->create_links();

		$data['main_content'] = 'backend/bookings/bookings';
		$data['title'] = 'Bookings';
		$this->load->view('includes/template', $data);
	}

	function ajax_check_tours()
	{   
		$this->load->model('booking');
		//$data['from_tour'] = $this->booking->check_available_tours();
	    if($_POST['from'] == "")
	    {
	      echo $message = "You can't send empty text";
	    }
	    else
	    {	
	    	//header('Content-type: application/json');
	        $message = $this->booking->check_available_tours($_POST['from'], $_POST['to']);
	        $data_json = json_encode($message);
       		 echo $data_json;
	    }
	    
	}
	function ajax_check_tours_back()
	{   
		$this->load->model('booking');
		//$data['from_tour'] = $this->booking->check_available_tours();
	    if($_POST['to'] == "")
	    {
	      echo $message = "You can't send empty text";
	    }
	    else
	    {	
	    	//header('Content-type: application/json');
	        $message = $this->booking->check_available_tours_back($_POST['to'],$_POST['selected_back']);
	        $data_json = json_encode($message);
       		 echo $data_json;
	    }
	    
	}

	function add_ticket(){
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('from', 'Departure city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('to', 'Arrival city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('client_firstname', 'First name', 'trim|required');
		$this->form_validation->set_rules('client_lastname', 'Last name', 'trim|required');
		$this->form_validation->set_rules('identification_nr', 'Identification number', 'trim|required');
		$this->form_validation->set_rules('booked_seats', 'Seats to book', 'trim|required');
		$this->form_validation->set_rules('returning', 'Returning ticket', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model('booking');
			$data['cities'] = $this->booking->list_cities();
			$data['main_content'] = 'backend/bookings/add_ticket';
			$data['company_info'] = $this->booking->get_company_info();
			$data['title'] = 'Book a ticket';
			$this->load->view('includes/template', $data);
		}
		else
		{
			$this->load->model('booking');
			$data = $this->input->post();
			$this->booking->create_booking($data);
			$this->session->set_flashdata('message', 'Ticket successfully created');
			redirect('admin/bookings', 'refresh');
		}
	}
	function edit_booking($id)
	{
		$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
		$this->form_validation->set_rules('from', 'Departure city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('to', 'Arrival city', 'trim|required|callback__citynull_check');
		$this->form_validation->set_rules('client_firstname', 'First name', 'trim|required');
		$this->form_validation->set_rules('client_lastname', 'Last name', 'trim|required');
		$this->form_validation->set_rules('identification_nr', 'RIdentification number', 'trim|required');
		$this->form_validation->set_rules('booked_seats', 'Seats to book', 'trim|required');
		$this->form_validation->set_rules('returning', 'Returning ticket', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->load->model('booking');
			$data['cities'] = $this->booking->list_cities();
			$data['booking'] = $this->booking->get_booking($id);
			$data['company_info'] = $this->booking->get_company_info();
			$data['main_content'] = 'backend/bookings/edit_booking';
			$data['title'] = 'Edit ticket';
			$this->load->view('includes/template', $data);
		}
		else
		{
			$id = $this->uri->segment(4);
			$this->load->model('booking');
			$data = $this->input->post();
			$this->booking->save_booking($data, $id);
			$this->session->set_flashdata('message', 'Ticket successfully edited');
			redirect('admin/bookings', 'refresh');
		}
	}
	function delete_booking($id){
		$this->load->model('booking');
		$this->session->set_flashdata('message', 'Ticket successfully deleted');
		$this->booking->delete_booking($id);
		redirect('admin/bookings', 'refresh');
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