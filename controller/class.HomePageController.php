<?php
require_once(ROOT_PATH."/controller/class.DPController.php");

class HomePageController extends DPController {
    
    public function go() {
        
        $this->setViewTemplate("home.tpl");
        $this->generateView();
        
    }
    
}