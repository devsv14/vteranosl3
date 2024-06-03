<?php



require_once("../config/conexion.php");
class ScanActasL1 extends Conectar
{

    public function getActas()
    {
        $conectar = parent::conexion();
        $sql = "SELECT o.id_orden,o.codigo,o.dui,o.paciente,o.fecha,o.sucursal,o.telefono,o.upload_acta FROM orden_lab_lc1 as o order by o.id_orden desc";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    /* public function getActasPorSucursal(){
        $conectar=parent::conexion();
        $sql="select*from orden_lab_lc1 where sucursal=?;";
        $sql=$conectar->prepare($sql);
        $sql->bindValue(1, $_POST["sucursal"]);
        $sql->execute();
        //echo $_POST["sucursal"];
       
        return $resultado=$sql->fetchAll();
    } */

    public function comprobarExisteAmpo($sucursal, $ampo)
    {
        $conectar = parent::conexion();
        $sql2 = "select*from ampos where nombre=? and ampo=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $sucursal);
        $sql2->bindValue(2, $ampo);
        $sql2->execute();
        return $resultado = $sql2->fetchAll(PDO::FETCH_ASSOC);
    }

    public function uploadImages($client, $dui, $id_orden)
    {

        $conectar = parent::conexion();
        date_default_timezone_set('America/El_Salvador');
        $hoy = date("Y-m-d");
        $hora = date("H:i:s");
        $service = new Google_Service_Drive($client);

        $parentFolderId = '1EGkxS2k93Byf54Z-my6vPt_yBtCSDTpm'; // ID de la carpeta en Google Drive donde se subirán los archivos
        $fileIds = array(); // Array para almacenar los IDs de los archivos subidos
        $fieldNames = ['receta', 'expediente', 'acta'];

        $uploadedCount = 0; // Contador para contar las imágenes subidas correctamente
        $rowAfected = 0;
        for ($i = 1; $i <= 3; $i++) {
            $fieldName = $fieldNames[$i - 1];

            if ($_FILES[$fieldName]['size']) {
                $img = imagecreatefromjpeg($_FILES[$fieldName]["tmp_name"]);
                $quality = 25;

                $exif = exif_read_data($_FILES[$fieldName]["tmp_name"]);

                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {

                        case 3:
                            $img = imagerotate($img, 180, 0);
                            break;

                        case 6:
                            $img = imagerotate($img, -90, 0);
                            break;

                        case 8:
                            $img = imagerotate($img, 90, 0);
                            break;
                    }
                }

                $temp_file = tempnam(sys_get_temp_dir(), 'img');
                imagejpeg($img, $temp_file, $quality);

                $content = file_get_contents($temp_file);

                $fileMetadata = new Google_Service_Drive_DriveFile(array(
                    'name' => $dui . "-" . $_FILES[$fieldName]["name"],
                    'parents' => array($parentFolderId),
                    'description' => 'Esta es una imagen subida desde PHP',
                    'mimeType' => 'image/jpeg'
                ));

                $file = $service->files->create($fileMetadata, array(
                    'data' => $content,
                    'mimeType' => 'image/jpeg',
                    'uploadType' => 'media'
                ));


                $fileIds[] = $file->getId();
                $fileId = end($fileIds);
                $imageUrl = "https://drive.google.com/uc?id=" . $fileId;

                $uploadedCount++;
                /////Insertar en BD
                $sql = 'insert into upload_actas_l1 values(null,?,?,?,?,?,?,?)';
                $sql = $conectar->prepare($sql);
                $sql->bindValue(1, $dui);
                $sql->bindValue(2, $id_orden);
                $sql->bindValue(3, $fieldName);
                $sql->bindValue(4, $imageUrl);
                $sql->bindValue(5, $hoy);
                $sql->bindValue(6, $hora);
                $sql->bindValue(7, $_SESSION["id_user"]);
                $sql->execute();
                if ($sql->rowCount() > 0) {
                    $rowAfected++;
                }
                unlink($temp_file);
            }
        }

        if ($uploadedCount === $rowAfected) {
            //Update campo upload_acta tabla acta set 1
            $sqlActa = "UPDATE `orden_lab_lc1` SET `upload_acta` = 1 WHERE `orden_lab_lc1`.`id_orden` = ?;";
          $sqlActa = $conectar->prepare($sqlActa);
          $sqlActa->bindValue(1,$id_orden);
          $sqlActa->execute();
            echo 'insertadas';
        } else {
            echo 'Error insert';
        }
    }
    public function getScanActasUpload($id_orden)
    {
        $conectar = parent::conexion();
        $sql = "SELECT up.id_upload, up.id_orden,up.tipo_expediente, up.url_expediente, o.paciente, o.fecha as fecha_impresion, up.dui as dui_paciente, DATE_FORMAT(up.fecha_scan,'%d-%m-%Y') as fecha_scan, us.nombres as usuario FROM `upload_actas_l1` as up inner join orden_lab_lc1 as o on up.id_orden=o.id_orden inner join usuarios as us on us.id_usuario=up.id_usuario where up.id_orden=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_orden);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        return count($result) > 0 ? $result : [];
    }
    //method para verificar existencia de expediente cargados
    public function verifyExistsActasFir($id_orden, $dui)
    {
        $conectar = parent::conexion();
        $sql = "SELECT * FROM `upload_actas_l1` WHERE id_orden=? and dui=?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $id_orden);
        $sql->bindValue(2, $dui);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
        return  count($result) > 0 ? true : false;
    }
    public function deleteUploadActa($id_orden)
    {
        $conectar = parent::conexion();
        $sUploadActa = "select * from upload_actas_l1 where id_orden=?";
        $sUploadActa = $conectar->prepare($sUploadActa);
        $sUploadActa->bindValue(1, $id_orden);
        $sUploadActa->execute();
        $result = $sUploadActa->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $url_image = $row['url_expediente'];
            //Delete image google drive
            $bolDeleteImage = $this->deleteImageGoogleDrive($url_image);
        }

        //Validacion permiso eliminar
        if (in_array("del_acta_firmada", $_SESSION['names_permisos'])) {
            $sql = "DELETE FROM upload_actas_l1 WHERE `id_orden` = ?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $id_orden);
            $exe1 = $sql->execute();
            //Rollback table acta set upload_acta = 0
            $sqlActa = "UPDATE `orden_lab_lc1` SET `upload_acta` = 0 WHERE `id_orden` = ?;";
            $sqlActa = $conectar->prepare($sqlActa);
            $sqlActa->bindValue(1, $id_orden);
            $exe2 = $sqlActa->execute();
            return $exe1 && $exe2 && $bolDeleteImage ? true : false;
        }
        return false;
    }
    /**
     * Method para eliminar la imagen subida a google drive
     */
    public function deleteImageGoogleDrive($imageUrl)
    {
        $fileUrlParts = parse_url($imageUrl);
        parse_str($fileUrlParts['query'], $queryParts);

        if (isset($queryParts['id'])) {
            $fileId = $queryParts['id'];

            require_once '../api-drive/vendor/autoload.php';
            $client = new Google_Client();
            $client->setApplicationName('inabve actas');
            $client->setScopes(Google_Service_Drive::DRIVE);
            $client->setAuthConfig('../modelos/inabve-actas-40f976130a1b.json');
            $client->setAccessType('offline');

            $service = new Google_Service_Drive($client);
            try {
                $service->files->delete($fileId);
                //return "La imagen se ha eliminado correctamente de Google Drive.";
                return true;
            } catch (Google_Service_Exception $e) {
                //return "Error al eliminar la imagen de Google Drive: " . $e->getMessage();
                return false;
            } catch (Exception $e) {
                //return "Error inesperado: " . $e->getMessage();
                return false;
            }
        } else {
            //return "No se pudo obtener el ID del archivo de la URL proporcionada.";
            return false;
        }
    }
    /**GET AMPO */
    public function getAMPOActa()
    {
        $conectar = parent::conexion();
        $sql = "SELECT ua.id_acta,a.beneficiario as paciente,ua.ampo, a.dui_acta as dui, a.sucursal, a.upload_acta as estado FROM `upload_actas` as ua inner join actas as a on ua.dui_tpname=a.dui_acta group by a.dui_acta order by ua.id_acta desc;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getActasPorIdDui($value)
    {
        $conectar = parent::conexion();

        /* if (strpos($value, '-') == false) { */
        $sql = "select*from actas where dui_acta=? or id_acta=?;";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $value);
        $sql->bindValue(2, $value);
        $sql->execute();
        $data = $sql->fetchAll(PDO::FETCH_ASSOC);

        /*} else{
      $sql = "select dui as dui_acta,'S/A' as id_acta,paciente,sucursal,paciente as beneficiario
       from orden_lab_bk where dui=?;";
      $sql=$conectar->prepare($sql);
      $sql->bindValue(1, $value);
      $sql->execute();
      $data = $sql->fetchAll(PDO::FETCH_ASSOC);
  
    } */


        $sql2 = "select codigo,fecha,paciente,dui from orden_lab_bk where dui=?;";
        $sql2 = $conectar->prepare($sql2);
        $sql2->bindValue(1, $data[0]['dui_acta']);
        $sql2->execute();
        $data_ord = $sql2->fetchAll(PDO::FETCH_ASSOC);
        //Sucursal
        $sucursal = (count($data) > 0) ? $data[0]['sucursal'] : '';
        //Full DATA ACTAS
        $actas = [];
        $resData = [];
        if (count($data) > 0) {
            $sql = "select*from actas where sucursal=?;";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $sucursal);
            $sql->execute();
            $actas = $sql->fetchAll(PDO::FETCH_ASSOC);
            $resData = [
                "acta" => $data,
                "dataActas" => $actas,
                "dataOrd" => $data_ord
            ];
        }
        return $resData;
    }
}





//uploadImages();
