<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	public function __construct()

	{
		Parent::__construct();
		is_logged_in();
	}

	//menampilkan halaman utama
	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('template_oznet/header', $data);
		$this->load->view('template_oznet/sidebar', $data);
		$this->load->view('administrator/admin/index', $data);
		$this->load->view('template_oznet/footer');
	}

	public function role()
	{
		$data['title'] = 'Role';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get('user_role')->result_array();
		$this->load->view('template_oznet/header', $data);
		$this->load->view('template_oznet/sidebar', $data);
		$this->load->view('administrator/admin/role', $data);
		$this->load->view('template_oznet/footer');
	}

	public function roleAdd()
	{
		$data['title'] = 'Role';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get('user_role')->result_array();
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/admin/role', $data);
			$this->load->view('template_oznet/footer');
		} else {
			$this->db->insert('user_role', ['role' => $this->input->post('role')]);
			$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">New Menu added</div>');
			redirect('admin/role');
		}
	}


	public function roleedit()
	{

		$data['title'] = 'Role';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get('user_role')->result_array();
		$this->form_validation->set_rules('role', 'Role', 'required');

		if ($this->form_validation->run() == false) {
			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/admin/role', $data);
			$this->load->view('template_oznet/footer');
		} else {
			$data = ['role' => $this->input->post('role')];
			$this->db->where('id', $this->input->post('id'));
			$this->db->update('user_role', $data);
			$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Role edited</div>');
			redirect('admin/role');
		}
	}

	public function hapus_role($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('user_role');
		$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Menu Deleted</div>');
		redirect('admin/role');
	}

	public function roleaccess($role_id)
	{
		$data['title'] = 'Role Access';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
		$this->db->where('id !=', 1);
		$data['menu'] = $this->db->get('user_menu')->result_array();
		$data['acess_menu'] = $this->db->get('user_access_menu')->result_array();
		$this->load->view('template_oznet/header', $data);
		$this->load->view('template_oznet/sidebar', $data);
		$this->load->view('administrator/admin/role_access', $data);
		$this->load->view('template_oznet/footer');
	}

	public function changeaccess()
	{
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');
		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id,
		];
		$result = $this->db->get_where('user_access_menu', $data);
		if ($result->num_rows() < 1) {
			$this->db->insert('user_access_menu', $data);
		} else {
			$this->db->delete('user_access_menu', $data);
		}
		$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Access Changed</div>');
	}

	public function view_web()
	{
		redirect('client');
	}
}
