<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 06/11/17 10:00
 */
namespace Modules\Projector\Controllers;

use Modules\Projector\Components\Scheme;
use Phact\Controller\Controller;

class ProjectorController extends Controller
{
    public function index()
    {
        $scheme = new Scheme();
        if ($this->request->getIsPost() && $_POST['models']) {
            $models = json_decode($_POST['models'], true);
            $scheme->sync($models, isset($_POST['sync']));
        }
        echo $this->render('projector/index.tpl', [
            'data' => $scheme->getData()
        ]);
    }
}