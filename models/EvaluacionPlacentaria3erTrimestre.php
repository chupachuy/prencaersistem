<?php
require_once __DIR__ . '/../core/Database.php';

class EvaluacionPlacentaria3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM evaluacion_placentaria_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO evaluacion_placentaria_3er_trimestre (evaluacion_id, localizacion_placentaria, distancia_oci_mm, grosor_placentario_mm, grado_madurez, ecogenicidad, lagunas_vasculares, interfase_miometrial, vasos_puente, zona_retroplacentaria, protrusion_placentaria, vascularizacion_anomala_doppler, insercion_cordon, numero_vasos_umbilicales, calcificaciones, perfusion_vi, perfusion_fi, perfusion_vfi, acretismo_figo_pas, morfologia_uterina_eshre, miomas_visibles, miomas_figo_tipo, miomas_dimensiones_mm, miomas_obstruyen_canal) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([$data['evaluacion_id'], $data['localizacion_placentaria']??null, $data['distancia_oci_mm']??null, $data['grosor_placentario_mm']??null, $data['grado_madurez']??null, $data['ecogenicidad']??null, $data['lagunas_vasculares']??'Ausentes/minimas', $data['interfase_miometrial']??'Intacta', $data['vasos_puente']??0, $data['zona_retroplacentaria']??null, $data['protrusion_placentaria']??0, $data['vascularizacion_anomala_doppler']??null, $data['insercion_cordon']??null, $data['numero_vasos_umbilicales']??null, $data['calcificaciones']??null, $data['perfusion_vi']??null, $data['perfusion_fi']??null, $data['perfusion_vfi']??null, $data['acretismo_figo_pas']??'Grado 0', $data['morfologia_uterina_eshre']??null, $data['miomas_visibles']??0, $data['miomas_figo_tipo']??null, $data['miomas_dimensiones_mm']??null, $data['miomas_obstruyen_canal']??0]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE evaluacion_placentaria_3er_trimestre SET localizacion_placentaria=?, distancia_oci_mm=?, grosor_placentario_mm=?, grado_madurez=?, ecogenicidad=?, lagunas_vasculares=?, interfase_miometrial=?, vasos_puente=?, zona_retroplacentaria=?, protrusion_placentaria=?, vascularizacion_anomala_doppler=?, insercion_cordon=?, numero_vasos_umbilicales=?, calcificaciones=?, perfusion_vi=?, perfusion_fi=?, perfusion_vfi=?, acretismo_figo_pas=?, morfologia_uterina_eshre=?, miomas_visibles=?, miomas_figo_tipo=?, miomas_dimensiones_mm=?, miomas_obstruyen_canal=? WHERE evaluacion_id=?");
        return $stmt->execute([$data['localizacion_placentaria']??null, $data['distancia_oci_mm']??null, $data['grosor_placentario_mm']??null, $data['grado_madurez']??null, $data['ecogenicidad']??null, $data['lagunas_vasculares']??'Ausentes/minimas', $data['interfase_miometrial']??'Intacta', $data['vasos_puente']??0, $data['zona_retroplacentaria']??null, $data['protrusion_placentaria']??0, $data['vascularizacion_anomala_doppler']??null, $data['insercion_cordon']??null, $data['numero_vasos_umbilicales']??null, $data['calcificaciones']??null, $data['perfusion_vi']??null, $data['perfusion_fi']??null, $data['perfusion_vfi']??null, $data['acretismo_figo_pas']??'Grado 0', $data['morfologia_uterina_eshre']??null, $data['miomas_visibles']??0, $data['miomas_figo_tipo']??null, $data['miomas_dimensiones_mm']??null, $data['miomas_obstruyen_canal']??0, $data['evaluacion_id']]);
    }
}
