<?php
class HomeModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEventos($sucursal, $categoria)
    {
        if ($categoria == "Admin") {
            $sql = "SELECT id_cita as id,concat(count(paciente),'-', sucursal) as title,fecha as start, color FROM citas where estado='0' group by fecha,sucursal;";
            return $this->selectAll($sql);
        } else {
            $sql = "SELECT id_cita as id,concat(count(paciente),'-', sucursal) as title,fecha as start, color FROM citas where estado='0' and sucursal='$sucursal' group by fecha,sucursal;";
            return $this->selectAll($sql);
        }
    }
}

?>


