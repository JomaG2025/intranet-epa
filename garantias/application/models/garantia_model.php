<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garantia_model extends CI_Model {
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('garantia');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('garantia');
			return TRUE;
		}
	}
	
	public function listar($inicio = 0, $limit = FALSE, $estado = NULL, $proveedor = NULL)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
        if($estado) $query = $this->db->where('garantia.estado', $estado); 
        if($proveedor) $query = $this->db->where('garantia.proveedor', $proveedor); 
		
		$query = $this->db->select('garantia.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, institucion.nombre AS institucion_nombre, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'garantia.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->join('institucion', 'garantia.institucion = institucion.cod');
        $query = $this->db->join('moneda', 'garantia.moneda = moneda.cod');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('garantia');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
        $this->db->db_debug = FALSE;
        
		$this->db->where('id', $id);
		$this->db->delete('garantia');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('garantia', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('garantia');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
		
	public function contar()
	{
		$query = $this->db->get('garantia');
		return $query->num_rows();
	}
    
    public function seleccionar_por_alertar()
    {
        $query = $this->db->select('garantia.*, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->where('estado !=', 'ALE');
        $query = $this->db->where('estado !=', 'VEN');
        $query = $this->db->where('estado !=', 'DEV');
        $query = $this->db->where('estado !=', 'COB');
        $query = $this->db->where('DATE_SUB(termino, INTERVAL (10) DAY) <= CURDATE()');
        $query = $this->db->where('termino > CURDATE()');
        $query = $this->db->get('garantia');
        
        return $query->result_array();
    }
    
    public function seleccionar_por_vencer()
    {   
        $query = $this->db->select('garantia.*, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv');
        $query = $this->db->join('proveedor', 'garantia.proveedor = proveedor.rut');
        $query = $this->db->where('estado !=', 'VEN');
        $query = $this->db->where('estado !=', 'DEV');
        $query = $this->db->where('estado !=', 'COB');
        $query = $this->db->where('termino <= CURDATE()');
        $query = $this->db->get('garantia');
        
        return $query->result_array();
    }
    
    public function actualizar_estado_alertado()
    {
        $query = $this->db->where('estado !=', 'ALE');
        $query = $this->db->where('estado !=', 'VEN');
        $query = $this->db->where('estado !=', 'DEV');
        $query = $this->db->where('estado !=', 'COB');
        $query = $this->db->where('DATE_SUB(termino, INTERVAL (10) DAY) <= CURDATE()');
        $query = $this->db->where('termino > CURDATE()');
        $query = $this->db->set('estado', 'ALE');
        $query = $this->db->update('garantia');
        
        if($this->db->affected_rows() > 0 ) return TRUE;
    }    
    
    public function actualizar_estado_vencido()
    {
        $query = $this->db->where('estado !=', 'VEN');
        $query = $this->db->where('estado !=', 'DEV');
        $query = $this->db->where('estado !=', 'COB');
        $query = $this->db->where('termino <= CURDATE()');
		$query = $this->db->set('estado', 'VEN');
		$query = $this->db->update('garantia');
		
		if($this->db->affected_rows() > 0 ) return TRUE;
    }
}