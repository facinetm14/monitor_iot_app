<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Datastream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * liste les modules par ordre decroissant
     * et renvoie la page d'accueil
     */
    public function index(Module $modules)
    {
        $modules = Module::orderBy('id', 'desc')->get();
        $dataStreams = Datastream::orderBy('id', 'desc')->get();
        return view(
            'dashboard',
            [
                'modules' => $modules,
                'dataStreams' => $dataStreams,
            ]
        );
    }

    public function create(Request $request, Module $module)
    {
        $module->name = $request->name;
        $module->serie = $request->serie;
        $module->dimensions = $request->dimensions;
        $module->etat = $request->etat;
        $module->save();
        return redirect()->route('dashboard');

    }

    /**
     * simulation de mesure effectuées par les modules.
     * en generant des données aléatoires.
     */
    public function generateData()
    {
        $modules = Module::where('etat', 'ACTIF')->get();
        foreach ($modules as $module) {
            $dataStream = new Datastream();
            $dataStream->module = $module->id;
            $dataStream->temperature = rand(10, 50);
            $dataStream->pression = rand(50, 100);
            $dataStream->save();
        }
    }

    /**
    * permet de simuler le fonctionnement de modules
    * en alternant les differents etats
    */
    public function supervise()
    {
        $state = (!rand(0, 5)) ? 'ACTIF' : 'INACTIF';
        $modules = Module::where('etat', $state)->get();
        $idxSelected = rand(0, count($modules) - 1);
        foreach ($modules as $id => $module) {
            if ($id == $idxSelected) {
                $newSate = ($state == 'ACTIF') ? 'INACTIF' : 'ACTIF';
                DB::table('modules')->where('id', $module->id)->update(['etat' => $newSate]);
                return;
            }
        }
    }


    public function delete($id)
    {
        Module::destroy($id);
        return redirect()->route('dashboard');
    }
}
