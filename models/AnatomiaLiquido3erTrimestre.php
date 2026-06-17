<?php
require_once __DIR__ . '/../core/Database.php';

class AnatomiaLiquido3erTrimestre
{
    private $db;

    public function __construct() { $this->db = Database::getInstance()->getConnection(); }

    public function getByEvaluacion($id) {
        $stmt = $this->db->prepare("SELECT * FROM anatomia_liquido_3er_trimestre WHERE evaluacion_id = ?");
        $stmt->execute([$id]); return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO anatomia_liquido_3er_trimestre (
            evaluacion_id, circular_cordon_cuello, liquido_amniotico_mm, metodo_medicion_liquido, diagnostico_liquido,
            estructuras_normales,
            craneo_snc_normal, cara_cuello_normal, corazon_normal, torax_diafragma_normal,
            abdomen_normal, genitourinario_normal, columna_normal, extremidades_normal,
            detalles_anatomia,
            ventriculomegalia_leve, quistes_plexos_coroideos, pliegue_nucal_aumentado, hueso_nasal_ausente,
            foco_ecogenico_cardiaco, intestino_hiperecogenico, femur_corto, arteria_umbilical_unica
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['evaluacion_id'],
            $data['circular_cordon_cuello']??'Negativo',
            $data['liquido_amniotico_mm']??null,
            $data['metodo_medicion_liquido']??'Bolsillo Maximo',
            $data['diagnostico_liquido']??'Normal',
            $data['estructuras_normales']??1,
            $data['craneo_snc_normal']??1,
            $data['cara_cuello_normal']??1,
            $data['corazon_normal']??1,
            $data['torax_diafragma_normal']??1,
            $data['abdomen_normal']??1,
            $data['genitourinario_normal']??1,
            $data['columna_normal']??1,
            $data['extremidades_normal']??1,
            $data['detalles_anatomia']??null,
            $data['ventriculomegalia_leve']??0,
            $data['quistes_plexos_coroideos']??0,
            $data['pliegue_nucal_aumentado']??0,
            $data['hueso_nasal_ausente']??0,
            $data['foco_ecogenico_cardiaco']??0,
            $data['intestino_hiperecogenico']??0,
            $data['femur_corto']??0,
            $data['arteria_umbilical_unica']??0
        ]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE anatomia_liquido_3er_trimestre SET
            circular_cordon_cuello=?, liquido_amniotico_mm=?, metodo_medicion_liquido=?, diagnostico_liquido=?,
            estructuras_normales=?,
            craneo_snc_normal=?, cara_cuello_normal=?, corazon_normal=?, torax_diafragma_normal=?,
            abdomen_normal=?, genitourinario_normal=?, columna_normal=?, extremidades_normal=?,
            detalles_anatomia=?,
            ventriculomegalia_leve=?, quistes_plexos_coroideos=?, pliegue_nucal_aumentado=?, hueso_nasal_ausente=?,
            foco_ecogenico_cardiaco=?, intestino_hiperecogenico=?, femur_corto=?, arteria_umbilical_unica=?
            WHERE evaluacion_id=?");
        return $stmt->execute([
            $data['circular_cordon_cuello']??'Negativo',
            $data['liquido_amniotico_mm']??null,
            $data['metodo_medicion_liquido']??'Bolsillo Maximo',
            $data['diagnostico_liquido']??'Normal',
            $data['estructuras_normales']??1,
            $data['craneo_snc_normal']??1,
            $data['cara_cuello_normal']??1,
            $data['corazon_normal']??1,
            $data['torax_diafragma_normal']??1,
            $data['abdomen_normal']??1,
            $data['genitourinario_normal']??1,
            $data['columna_normal']??1,
            $data['extremidades_normal']??1,
            $data['detalles_anatomia']??null,
            $data['ventriculomegalia_leve']??0,
            $data['quistes_plexos_coroideos']??0,
            $data['pliegue_nucal_aumentado']??0,
            $data['hueso_nasal_ausente']??0,
            $data['foco_ecogenico_cardiaco']??0,
            $data['intestino_hiperecogenico']??0,
            $data['femur_corto']??0,
            $data['arteria_umbilical_unica']??0,
            $data['evaluacion_id']
        ]);
    }
}
