<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria_model extends CI_Model {
		
	public function insertar($auditoria)
	{	
		$auditoria['usuario'] = $this->session->userdata('usuario');
		$auditoria['ip'] = $this->input->ip_address();
	
		if($query = $this->db->insert('auditoria', $auditoria)) return $this->db->insert_id();
	}
	
	public function listar($filtro = FALSE, $valor1 = FALSE, $valor2 = FALSE)
	{
		if($filtro == 'rango')
		{	
			$query = $this->db->where('fecha >=', $valor1);
			$query = $this->db->where('fecha <=', $valor2);
		}
		
		if($filtro == 'dia')
		{
			$dia = date('Y-m-d', strtotime($valor1));
			$query = $this->db->where('DATE(fecha) =', $dia);
		}
		
		if($filtro == 'mes')
		{
			$mes = explode('-', $valor1);
			$query = $this->db->where('MONTH(fecha)', $mes[0]);
			$query = $this->db->where('YEAR(fecha)', $mes[1]);
		}
		
		$query = $this->db->select('auditoria.*, evento.nombre AS evento_nombre');
		$query = $this->db->join('evento', 'auditoria.evento = evento.abrev');
		$query = $this->db->order_by('fecha', 'DESC');
		$query = $this->db->get('auditoria');
		
		return $query->result_array();
	}
	
	public function listar_por_contrato($id)
	{
		$p   = $this->db->dbprefix;
		$sql = "SELECT a.*, IFNULL(e.nombre, a.evento) AS evento_nombre
		        FROM {$p}auditoria a
		        LEFT JOIN {$p}evento e ON a.evento = e.abrev
		        WHERE a.recurso = 'contrato'
		          AND a.instancia = ?
		        ORDER BY a.fecha DESC";
		return $this->db->query($sql, array((int) $id))->result_array();
	}

	public function listar_ano()
	{
		$query = $this->db->select('year(fecha) as ano');
		$query = $this->db->distinct('year(fecha)');
		$query = $this->db->group_by('year(fecha)');
		$query = $this->db->order_by('fecha', 'DESC');
		$query = $this->db->get('auditoria');
		
		return $query->result_array();
	}
}