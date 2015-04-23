<?php

/**
 * This file is part of the Xond package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Xond\Gen;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use Xond\Gen\BaseGen;
use Xond\Info\TableInfo;
use Xond\Info\ColumnInfo;
use Xond\Info\GroupInfo;
use Xond\Info\FieldsetInfo;
use Xond\Xond;

/**
 * This is a front end Controller generator class for building the extension
 * of basecontroller that is generated by the FrontEndGen class. It was also
 * generated under web/app/controller/ovd but it created conflicts when compiled.
 *
 * @author     Donny Fauzan <donny.fauzan@gmail.com> (Nufaza)
 * @version    $Revision$
 * @package    xond.gen
 */


class ControllerGen extends BaseGen
{
    public function menu(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app) {
        
        // Initialize
        $this->initialize($request, $app);
                
        $tables = $this->getTables(BaseGen::TABLES_STRING);
        $out = "Silakan pilih tabel yang mana yang akan digenerate controllernya: <br>\r\n";
        
        foreach ($tables as $t) {
            $out .= "<a href='/ControllerGen/$t'>$t</a><br>\r\n";
        }
        
        return $out;
    }
    
    public function generate(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app) {
        
        // Initialize
        $this->initialize($request, $app);
        
        // Get params
        $model = $request->get('model');
        $infoObj = Xond::createTableInfo($model, $this->appName);
        
        /** Prepare twig **/
        $templateRoot = realpath(__DIR__."/templates/js");
        
        // Loader path
        $loader = new \Twig_Loader_Filesystem($templateRoot);
        
        // The twig object
        // $twig = new \Twig_Environment($loader);
        $twig = new \Twig_Environment($loader, array(
            'debug' => true
        ));
        
        $templateFileName = 'controller-simple-template.js';
        $array = array(
            'appName' => $this->appname,
            'table' => $infoObj,
            'columns' => $infoObj->getColumns()
        );
         
        $tplStr = "<pre>".$twig->render($templateFileName, $array)."</pre>";
        
        return $tplStr;
    }
    
    public function controllerList(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app) {
    
        // Initialize
        $this->initialize($request, $app);
    
        $tables = $this->getTables(BaseGen::TABLES_STRING);
        $out = "Silakan pilih copas models berikut pada application.js (default commented for controllers): <br>\r\n";
        $out .= "<pre>\r\n";
        $out .= "    controllers: [\r\n";
        
        $i = 0;
        foreach ($tables as $t) {
            $i++;
            if ($i < sizeof($tables)) {
                $koma = ',';
            } else {
                $koma = '';
            }
            $out .= "        //'$t'$koma\r\n";
        }
        $out .= "    ],\r\n";
        $out .= "    stores: [\r\n";
        $i = 0;
        foreach ($tables as $t) {
            $i++;
            if ($i < sizeof($tables)) {
                $koma = ',';
            } else {
                $koma = '';
            }
            $out .= "        '$t'$koma\r\n";
        }
        $out .= "    ]</pre>";
        
        return $out;
    }
    
}