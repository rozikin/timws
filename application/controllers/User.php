<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function __construct()

	{
		parent::__construct();
		is_logged_in();
	}


	public function index()
	{
		$data['title'] = 'My Profile';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->load->view('template_oznet/header', $data);
		$this->load->view('template_oznet/sidebar', $data);
		$this->load->view('administrator/user/index', $data);
		$this->load->view('template_oznet/footer');
	}
	public function edit()
	{
		$data['title'] = 'Edit Profile';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		if ($this->form_validation->run() == false) {
			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/user/edit', $data);
			$this->load->view('template_oznet/footer');
		} else {
			$email = $this->input->post('email');
			$name = $this->input->post('name');
			//cek jika ada gambar yang akan diupload
			$upload_image = $_FILES['image']['name'];
			if ($upload_image) {
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['max_size']      = '20148';
				$config['upload_path']   = './assets/img/profile';

				$this->load->library('upload', $config);
				if ($this->upload->do_upload('image')) {
					$old_image = $data['user']['image'];
					if ($old_image != 'default.jpg') {
						unlink(FCPATH . 'assets/img/profile/' . $old_image);
					}
					$new_image = $this->upload->data('file_name');
					$this->db->set('image', $new_image);
				} else {
					echo $this->upload->display_errors();
				}
			}
			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');
			$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Edited</div>');
			redirect('user');
		}
	}

	public function changepwd()
	{
		$data['title'] = 'Change Password';
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$this->form_validation->set_rules('current_password', 'Current_password', 'required|trim');
		$this->form_validation->set_rules('new_password1', 'New_password1', 'required|trim|min_length[3]|matches[new_password2]');
		$this->form_validation->set_rules('new_password2', 'New_password2', 'required|trim|min_length[3]|matches[new_password1]');

		if ($this->form_validation->run() == false) {
			$this->load->view('template_oznet/header', $data);
			$this->load->view('template_oznet/sidebar', $data);

			$this->load->view('administrator/user/changepwd', $data);
			$this->load->view('template_oznet/footer');
		} else {
			$current_password = $this->input->post('current_password');
			$new_password = $this->input->post('new_password1');
			if (!password_verify($current_password, $data['user']['password'])) {
				$this->session->set_flashdata('message', '<div class= "alert alert-danger" role="alert">wrong current password</div>');
				redirect('user/changepwd');
			} else {
				if ($current_password == $new_password) {
					$this->session->set_flashdata('message', '<div class= "alert alert-danger" role="alert">New Password cannot be the same as current password</div>');
					redirect('user/changepwd');
				} else {
					$password_hash = password_hash($new_password, PASSWORD_DEFAULT);
					$this->db->set('password', $password_hash);
					$this->db->where('email', $this->session->userdata('email'));
					$this->db->update('user');
					$this->session->set_flashdata('message', '<div class= "alert alert-success" role="alert">Password Edited</div>');
					redirect('user/changepwd');
				}
			}
		}
	}
}
