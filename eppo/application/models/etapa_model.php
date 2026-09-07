<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapa_model extends CI_Model {
    
    protected $periodo;
    protected $etapas;
    protected $correlativo = 0;
    
    public function listar()
	{
		$query = $this->db->order_by('periodo', 'DESC');
		$query = $this->db->order_by('limite', 'ASC');
		$query = $this->db->get('etapa');
		
		return $query->result_array();
	}
	    
    public function listar_por_periodo($periodo)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->order_by('limite', 'ASC');
		$query = $this->db->get('etapa');
		
		return $query->result_array();
	}
    
    public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('etapa');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function verificar($periodo, $limite)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->where('limite', $limite);
		$query = $this->db->get('etapa');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function seleccionar_actual($periodo)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->where('limite >=', date('Y-m-d'));
		$query = $this->db->limit(1);		
		$query = $this->db->order_by('limite', 'ASC');		
		$query = $this->db->get('etapa');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function seleccionar_siguiente($periodo, $correlativo_etapa_actual)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->where('correlativo', $correlativo_etapa_actual + 1 );	
		$query = $this->db->get('etapa');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function seleccionar_anterior($periodo, $correlativo_etapa_actual)
	{
		$query = $this->db->where('periodo', $periodo);
		$query = $this->db->where('correlativo', $correlativo_etapa_actual -1 );	
		$query = $this->db->get('etapa');		
		
		if($query->num_rows() > 0) return $query->row_array(); 
	}
    
    public function insertar($array)
	{	
		if($this->db->insert('etapa', $array)) return $this->db->insert_id();
	}
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('etapa');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('etapa');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('etapa');
			return TRUE;
		}
	}
    
    public function actualizar_correlativos()
    {   
		$this->db->set('correlativo', NULL);
		$this->db->update('etapa');
        
        $this->etapas = $this->listar();
        
        foreach($this->etapas as $etapa)
        {
            if($this->periodo == $etapa['periodo'])
            {
                $this->correlativo = $this->correlativo + 1;
            }
            else
            {
                $this->periodo = $etapa['periodo'];
                $this->correlativo = 1;
            }
            
            $this->actualizar($etapa['id'], array('correlativo' => $this->correlativo));
        }
        
        if($this->db->affected_rows() > 0 ) return TRUE;
    }
}