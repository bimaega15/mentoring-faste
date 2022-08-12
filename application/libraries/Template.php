<?php
class Template
{
    protected $ci;
    public function __construct()
    {
        $this->ci = &get_instance();
    }
    public function login($template, $data = null)
    {
        $data['content'] = $this->ci->load->view($template, $data, true);
        $this->ci->load->view('template/login/template', $data);
    }
    public function admin($template, $data = null)
    {
        $data['sidebar'] = $this->ci->load->view('template/partialadmin/sidebar', null, true);
        $data['topbar'] = $this->ci->load->view('template/partialadmin/topbar', null, true);
        $data['breadcrumb'] = $this->ci->load->view('template/partialadmin/breadcrumb', null, true);
        $data['content'] = $this->ci->load->view($template, $data, true);
        $data['footer'] = $this->ci->load->view('template/partialadmin/footer', null, true);

        $this->ci->load->view('template/admin/main', $data);
    }
    public function siswa($template, $data = null)
    {
        $data['topbar'] =  $this->ci->load->view('template/partialsiswa/topbar', null, true);
        $data['content'] = $this->ci->load->view($template, $data, true);
        $data['footer'] = $this->ci->load->view('template/partialsiswa/footer', null, true);

        $this->ci->load->view('template/siswa/main', $data);
    }
    public function breadcrumbs($data = null)
    {
        $data['breadcrumb'] = $this->ci->load->view('template/partialadmin/breadcrumb', $data, true);
    }
    public function ujian($template, $data = null)
    {
        $data['content'] = $this->ci->load->view($template, $data, true);
        $this->ci->load->view('template/ujian/template_ujian', $data);
    }
}
