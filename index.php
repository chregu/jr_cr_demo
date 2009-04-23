<?php

try {
    define("JACK_PROJECT_DIR",dirname(__FILE__).'/');
    ini_set("include_path",JACK_PROJECT_DIR."/inc/:".ini_get("include_path"));
    
    spl_autoload_register('autoload');
    
    $config = array ('transport'=> 'davex', 'url' => 'http://localhost:8080/server','user' => 'admin','pass' => 'admin');
    $session = getJRSession($config);
    $rn = $session->getRootNode();
    print $rn->getPath . "<br/>";
    foreach($rn->getNodes() as $node) {
        print $node->getPath() ."<br/>";
    }
    
    
} catch (Exception $e) {
    print "<pre>";
    var_dump($e);
}

function getJRSession($config) {
    if (empty($config['url'])) {
        return false;
    }
    if (empty($config['workspace'])) {
        $config['workspace'] = "default";
    }
    
    $repository = jr_cr::lookup($config['url'], $config['transport']);
    if (isset($config['pass'])) {
        $credentials = new jr_cr_simplecredentials($config['user'], $config['pass']);
        return $repository->login($credentials, $config['workspace']);
        
    } else {
        return $repository->login(null, $config['workspace']);
    }
}


function autoload($class) {
    $incFile = str_replace("_", DIRECTORY_SEPARATOR, $class).".php";
    
    if (@fopen($incFile, "r", TRUE)) {
        include($incFile);
        return $incFile;
    }
    
    return FALSE;
    
}

