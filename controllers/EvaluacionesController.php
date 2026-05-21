<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Evaluacion1erTrimestre.php';
require_once __DIR__ . '/../models/Evaluacion2doTrimestre.php';
require_once __DIR__ . '/../models/Evaluacion3erTrimestre.php';
require_once __DIR__ . '/../helpers/Auth.php';

class EvaluacionesController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $ev1Model = new Evaluacion1erTrimestre();
        $ev2Model = new Evaluacion2doTrimestre();
        $ev3Model = new Evaluacion3erTrimestre();

        $this->render('evaluaciones/index', [
            'evaluaciones1' => $ev1Model->getAll(),
            'evaluaciones2' => $ev2Model->getAll(),
            'evaluaciones3' => $ev3Model->getAll(),
        ]);
    }
}
