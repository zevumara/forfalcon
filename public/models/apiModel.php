<?php
class testModel extends Model
{	
	function __construct()
	{
		parent::__construct();
	}
	
	public function newRecord($fecha, $hora, $estado)
	{
		$this->db->query("INSERT INTO registro_de_ubicacion (fecha, hora, estado) VALUES ('$fecha', '$hora', $estado)");

		return $this->db->lastInsertId();
	}
	
	public function getRecords()
	{
		$record = $this->db->query("SELECT
				record.id AS id,
				record.fecha AS fecha,
				record.hora AS hora,
				record.estado AS estado
				FROM
				registro_de_ubicacion record");

		return $record->fetch();
	}
}
?>