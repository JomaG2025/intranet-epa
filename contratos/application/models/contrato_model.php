<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contrato_model extends CI_Model {
	
	public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('contrato');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('contrato');
			return TRUE;
		}
	}
	
	public function listar($inicio = 0, $limit = FALSE, $estado = NULL, $proveedor = NULL, $ocultar_antiguos = NULL)
	{
		if($limit) $query = $this->db->limit($limit, $inicio);
        if($estado) $query = $this->db->where('contrato.estado', $estado); 
        if($proveedor) $query = $this->db->where('contrato.proveedor', $proveedor); 
        if($ocultar_antiguos)
        {
            $query = $this->db->where('contrato.termino IS NOT NULL', NULL); 
            $query = $this->db->where('contrato.termino <= DATE_ADD(NOW(), INTERVAL 1 MONTH)', NULL);
        }
		
		$query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->join('moneda', 'contrato.moneda = moneda.cod');
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->get('contrato');
		
		return $query->result_array();
	}	
	
	public function eliminar($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('contrato');
		
		if ($this->db->_error_number() == 1451)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function insertar($array)
	{	
		if($query = $this->db->insert('contrato', $array)) return $this->db->insert_id();
	}
	
	public function seleccionar($id)
	{
		$query = $this->db->where('id', $id);
		$query = $this->db->get('contrato');		
		
		if ($query->num_rows() > 0) return $query->row_array();
	}
		
	public function contar()
	{
		$query = $this->db->get('contrato');
		return $query->num_rows();
	}
    
    public function seleccionar_por_alertar()
    {
        $query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->where('termino IS NOT NULL', NULL, FALSE);
        $query = $this->db->where('estado', 'ING');
        $query = $this->db->where('DATE_SUB(termino, INTERVAL ('.  $this->db->dbprefix  .'contrato.diasalerta) DAY) <= CURDATE()');
        $query = $this->db->where('termino > CURDATE()');
        $query = $this->db->get('contrato');
        
        return $query->result_array();
    }
    
    public function seleccionar_por_vencer()
    {   
        $query = $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv');
        $query = $this->db->join('estado', 'contrato.estado = estado.abrev');
        $query = $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $query = $this->db->where('termino IS NOT NULL', NULL, FALSE);
        $query = $this->db->where('estado !=', 'VEN');
        $query = $this->db->where('termino <= CURDATE()');
        $query = $this->db->get('contrato');
        
        return $query->result_array();
    }
    
    public function actualizar_estado_alertado()
    {
        $query = $this->db->where('termino IS NOT NULL', NULL, FALSE);
        $query = $this->db->where('estado', 'ING');
        $query = $this->db->where('DATE_SUB(termino, INTERVAL ('.  $this->db->dbprefix  .'contrato.diasalerta) DAY) <= CURDATE()');
        $query = $this->db->where('termino > CURDATE()');
        $query = $this->db->set('estado', 'ALE');
        $query = $this->db->update('contrato');
        
        if($this->db->affected_rows() > 0 ) return TRUE;
    }    
    
    public function actualizar_estado_vencido()
    {
        $this->db
            ->where('termino IS NOT NULL', NULL, FALSE)
            ->where('estado !=', 'VEN')
            ->where('termino <= CURDATE()')
            ->set('estado', 'VEN')
            ->update('contrato');

        $afectadas = $this->db->affected_rows();
        if ($afectadas > 0) {
            log_message('info', "Contrato_model: marcar $afectadas contratos como vencidos.");
            return TRUE;
        }
        return FALSE;
    }

    public function contar_por_estado()
    {
        $this->db->select('estado, COUNT(*) AS total');
        $this->db->group_by('estado');
        return $this->db->get('contrato')->result_array();
    }

    public function contar_vencen_este_mes()
    {
        $this->db->where('termino IS NOT NULL', NULL, FALSE);
        $this->db->where('YEAR(termino)  = YEAR(CURDATE())',  NULL, FALSE);
        $this->db->where('MONTH(termino) = MONTH(CURDATE())', NULL, FALSE);
        return $this->db->count_all_results('contrato');
    }

    public function monto_total_por_moneda()
    {
        $this->db->select('moneda, SUM(monto) AS total, COUNT(*) AS contratos');
        $this->db->group_by('moneda');
        $this->db->order_by('total', 'DESC');
        return $this->db->get('contrato')->result_array();
    }

    public function top_proveedores($limit = 10)
    {
        $pre = $this->db->dbprefix;
        $sql = "SELECT prov.razon, COUNT(c.id) AS total
                FROM {$pre}contrato c
                JOIN {$pre}proveedor prov ON c.proveedor = prov.rut
                GROUP BY c.proveedor
                ORDER BY total DESC
                LIMIT ?";
        return $this->db->query($sql, array((int) $limit))->result_array();
    }

    public function vencimientos_por_mes($meses = 12)
    {
        $p   = $this->db->dbprefix;
        $sql = "SELECT DATE_FORMAT(termino, '%Y-%m') AS mes, COUNT(*) AS total
                FROM {$p}contrato
                WHERE termino IS NOT NULL
                  AND termino >= CURDATE()
                  AND termino <= DATE_ADD(CURDATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(termino, '%Y-%m')
                ORDER BY mes ASC";
        return $this->db->query($sql, array($meses))->result_array();
    }

    public function listar_por_creado($desde = NULL, $hasta = NULL)
    {
        $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo');
        $this->db->join('estado',    'contrato.estado = estado.abrev');
        $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $this->db->join('moneda',    'contrato.moneda = moneda.cod');
        $pre = $this->db->dbprefix;
        if ($desde) $this->db->where("DATE({$pre}contrato.creado) >= " . $this->db->escape($desde), NULL, FALSE);
        if ($hasta)  $this->db->where("DATE({$pre}contrato.creado) <= " . $this->db->escape($hasta),  NULL, FALSE);
        $this->db->order_by('contrato.id', 'DESC');
        return $this->db->get('contrato')->result_array();
    }

    public function listar_filtrado($filtros = array())
    {
        $this->db->select('contrato.*, estado.nombre AS estado_nombre, proveedor.razon AS proveedor_razon, proveedor.dv AS proveedor_dv, moneda.simbolo AS moneda_simbolo');
        $this->db->join('estado',    'contrato.estado = estado.abrev');
        $this->db->join('proveedor', 'contrato.proveedor = proveedor.rut');
        $this->db->join('moneda',    'contrato.moneda = moneda.cod');

        if (!empty($filtros['estado']))
            $this->db->where('contrato.estado', $filtros['estado']);

        if (!empty($filtros['proveedor']))
            $this->db->like('proveedor.razon', $filtros['proveedor']);

        if (!empty($filtros['termino_desde']))
            $this->db->where('contrato.termino >=', $filtros['termino_desde']);

        if (!empty($filtros['termino_hasta']))
            $this->db->where('contrato.termino <=', $filtros['termino_hasta']);

        if (!empty($filtros['responsable']))
            $this->db->where(
                'contrato.id IN (SELECT contrato FROM ' . $this->db->dbprefix . 'contraparte WHERE usuario = ' . $this->db->escape($filtros['responsable']) . ')',
                NULL, FALSE
            );

        $this->db->order_by('contrato.id', 'DESC');
        return $this->db->get('contrato')->result_array();
    }
}