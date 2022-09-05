<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_Supplier extends CI_Controller
{
	public function __construct()
	{
		Parent::__construct();
		is_logged_in();
		$this->load->model('Model_Supplier','supplier');
	}

	public function index()
	{
		$data['title'] = 'Supplier List';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		//konek model
		$data['supplier'] = $this->supplier->get_supplier();
	

			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);
			$this->load->view('administrator/supplier/index', $data);
			$this->load->view('template_oznet/footer');
	
	}


	public function get_data()
	{
		// Datatables Variables
		$draw = intval($this->input->get("draw"));


		$this->db->order_by("id_supplier", "desc");
		$query = $this->db->get("tb_supplier");
		$data = [];
		$no = 0;

		foreach ($query->result() as $r) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $r->supplier_name;
			$row[] = $r->supplier_address;
			$row[] = $r->supplier_phone;
			$row[] = $r->supplier_fax;
			$row[] = $r->supplier_email;
			$row[] = $r->remark;

			//add html for action
			$row[] = '<a class="badge badge-primary" href="javascript:void(0)" title="Edit" onclick="edit_data(' . "'" . $r->id_supplier . "'" . ')"><i class="fas fa-edit"></i> Edit</a>
            <a class="badge badge-danger" href="javascript:void(0)" title="Hapus" onclick="deleted(' . "'" . $r->id_supplier . "'" . ')"><i class="fas fa-trash"></i> Delete</a>';
			$data[] = $row;
		};

		$result = array(
			"draw" => $draw,
			"recordsTotal" => $query->num_rows(),
			"recordsFiltered" => $query->num_rows(),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}



	public function remove($id)
	{
		//delete fil
		$this->supplier->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}



	public function add_supplier()
	{
		$data['title'] = 'Add Supplier';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['supplier'] = $this->supplier->get_supplier();
		

			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/supplier/add_supplier', $data);
			$this->load->view('template_oznet/footer');
		
	}

	public function add_data(){

		$data = [
			'supplier_name' => $this->input->post('supplier_name'),
			'supplier_address' => $this->input->post('supplier_address'),
			'supplier_phone' => $this->input->post('supplier_phone'),
			'supplier_fax' => $this->input->post('supplier_fax'),
			'supplier_email' => $this->input->post('supplier_email'), 
			'supplier_attention' => $this->input->post('supplier_attention'),
			'remark' => $this->input->post('remark')
		];

		$this->db->insert('tb_supplier', $data);

		
		$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Data added</div>');
		redirect('controller_supplier');

	}

	public function delete_supplier($id)
	{
		$this->supplier->delete_suppliers($id);
		$this->session->set_flashdata('message', '<div class= "alert alert-info" role="alert">Data Deleted</div>');
		redirect('controller_supplier');
	}


	public function edit_supplier($id)
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = 'Edit supplier';

		$data['supplier'] = $this->supplier->getID($id);

		$this->form_validation->set_rules('supplier_phone', 'supplier Code', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/supplier/edit_supplier',$data);
			$this->load->view('template_oznet/footer');
		} else {

			$data = [
				'supplier_name' => $this->input->post('supplier_name'),
				'supplier_address' => $this->input->post('supplier_address'),
				'supplier_phone' => $this->input->post('supplier_phone'),
				'supplier_fax' => $this->input->post('supplier_fax'),
				'supplier_email' => $this->input->post('supplier_email'),
				'supplier_attention' => $this->input->post('supplier_attention'),
				'remark' => $this->input->post('remark')
			];


			$this->db->where('id_supplier', $this->input->post('id_supplier'));
			$this->db->update('tb_supplier', $data);
			$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">supplier edited</div>');
			redirect('controller_supplier');
		}
	}


}