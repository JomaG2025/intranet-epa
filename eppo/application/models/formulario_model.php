<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formulario_model extends CI_Model {
    
    public function seleccionar($id)
	{
        $query = $this->db->select('formulario.*, estadoformulario.nombre AS estadoformulario_nombre, estadoformulario.descripcion AS estadoformulario_descripcion, tiponivel.nombre AS tiponivel_nombre, tipocontrato.nombre AS tipocontrato_nombre');
        $query = $this->db->join('estadoformulario', 'formulario.estadoformulario = estadoformulario.abrev');
        $query = $this->db->join('tiponivel', 'formulario.tiponivel = tiponivel.abrev', 'left');
        $query = $this->db->join('tipocontrato', 'formulario.tipocontrato = tipocontrato.abrev', 'left');
		$query = $this->db->where('formulario.id', $id);
		$query = $this->db->get('formulario');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
	
    public function seleccionar_por_usuario_periodo($usuario, $periodo)
	{
        $query = $this->db->select('formulario.*, estadoformulario.nombre AS estadoformulario_nombre, estadoformulario.descripcion AS estadoformulario_descripcion, tiponivel.nombre AS tiponivel_nombre, tipocontrato.nombre AS tipocontrato_nombre');
        $query = $this->db->join('estadoformulario', 'formulario.estadoformulario = estadoformulario.abrev');
        $query = $this->db->join('tiponivel', 'formulario.tiponivel = tiponivel.abrev', 'left');
        $query = $this->db->join('tipocontrato', 'formulario.tipocontrato = tipocontrato.abrev', 'left');
		$query = $this->db->where('formulario.usuario', $usuario);
		$query = $this->db->where('formulario.periodo', $periodo);
		$query = $this->db->get('formulario');		
		
		if($query->num_rows() > 0) return $query->row_array();
	}
    
    public function listar($usuario = NULL, $periodo = FALSE)
	{
        if($usuario) $query = $this->db->where('formulario.usuario', $usuario);
        if($periodo) $query = $this->db->where('formulario.periodo', $periodo);
        
		$query = $this->db->select('formulario.*, estadoformulario.nombre AS estadoformulario_nombre, estadoformulario.descripcion AS estadoformulario_descripcion, resultado.puntaje AS resultado_puntaje, resultadoapelacion.puntaje AS resultadoapelacion_puntaje');
        $query = $this->db->join('resultado', 'resultado.formulario = formulario.id', 'left');
        $query = $this->db->join('resultadoapelacion', 'resultadoapelacion.formulario = formulario.id', 'left');
        $query = $this->db->join('estadoformulario', 'formulario.estadoformulario = estadoformulario.abrev');
        $query = $this->db->join('usuario', 'formulario.usuario = usuario.usuario');
		$query = $this->db->where('usuario.eliminado', 0);
		$query = $this->db->get('formulario');
		
		return $query->result_array();
	}
    
    public function listar_por_evaluador($usuario, $periodo)
    {
        $query = $this->db->select('formulario.*, evaluado.usuario, estadoformulario.nombre AS estadoformulario_nombre, estadoformulario.descripcion AS estadoformulario_descripcion, resultado.puntaje AS resultado_puntaje, resultadoapelacion.puntaje AS resultadoapelacion_puntaje');
        $query = $this->db->join('evaluado', 'evaluador.id = evaluado.evaluador');
        $query = $this->db->join('formulario', 'formulario.usuario = evaluado.usuario AND '. $this->db->dbprefix .'formulario.periodo = '.  $periodo  .'', 'left');
        $query = $this->db->join('resultado', 'resultado.formulario = formulario.id', 'left');
        $query = $this->db->join('resultadoapelacion', 'resultadoapelacion.formulario = formulario.id', 'left');
        $query = $this->db->join('estadoformulario', 'formulario.estadoformulario = estadoformulario.abrev', 'left');
        $query = $this->db->join('usuario', 'evaluado.usuario = usuario.usuario');
        $query = $this->db->where('evaluador.periodo', $periodo);
        $query = $this->db->where('evaluador.usuario', $usuario);
        $query = $this->db->where('usuario.eliminado', 0);
        $query = $this->db->get('evaluador');
        
        return $query->result_array();
    }
    
    public function actualizar($id, $array, $forzar_timestamp = FALSE)
	{
		$this->db->where('id', $id);
		$this->db->set($array);
		$this->db->update('formulario');
		
		if(($this->db->affected_rows() > 0) OR ($forzar_timestamp))
		{	
			$this->db->where('id', $id);
            $this->db->set('modificado', date('Y-m-d H:i:s'));
            $this->db->update('formulario');
			return TRUE;
		}
	}
    
    public function crear($array)
    {
        if($this->db->insert('formulario', $array)) return $this->db->insert_id();
    }
    
    public function eliminar($id)
	{
		$this->db->db_debug = FALSE;
		
		$this->db->where('id', $id);
		$this->db->delete('formulario');
		
		if ($this->db->_error_number() == 1451) return FALSE;
		else return TRUE;
	}
}