<?php

namespace Chayka\DoorKeeper;

use Chayka\Helpers\Util;
use Chayka\WP;
use Chayka\WP\Helpers\AclHelper;
use Chayka\WP\Helpers\JsonHelper;

class Plugin extends WP\Plugin{

    /* chayka: constants */
    
    public static $instance = null;

    public static function init(){
        if(!static::$instance){
            static::$instance = $app = new self(__FILE__, array(
                /* chayka: init-controllers */
                'door-keeper'
            ));
            $app->dbUpdate(array());
	        $app->addSupport_UriProcessing();
	        $app->addSupport_ConsolePages();


            /* chayka: init-addSupport */
        }
    }

    public function inspectUri($uri){
        if(!OptionHelper::getOption('enabled')){
            return $uri;
        }
        if(AclHelper::userHasRole(OptionHelper::getOption('minUserLevel', 'administrator'))){
            return $uri;
        }

        $allowedRoutes = array(
            'wp-login.php',
            'auth'
        );
        $customAllowedRoutes = preg_split('/\s+/m', OptionHelper::getOption('allowedRoutes'));
        $allowedRoutes = array_merge($allowedRoutes, $customAllowedRoutes);
        $m=array();
    //            die('['.$uri.']');
        preg_match('%^(\/api)?\/([^\/\?]*)%i', $uri, $m);

        $isApi = Util::getItem($m, 1);
        $route = Util::getItem($m, 2);
        if(in_array($route, $allowedRoutes)){
            return $uri;
        }

        header("HTTP/1.1 503 Service Unavailable");
        if($isApi){
            JsonHelper::respondError(OptionHelper::getOption('message'), 'site_blocked');
        }

        $uri = '/door-keeper/door-closed';
        return OptionHelper::getOption('useHeaderFooter')?$uri:'/api'.$uri;
    }


    /**
     * Register your action hooks here using $this->addAction();
     */
    public function registerActions() {
    	/* chayka: registerActions */
    }

    /**
     * Register your action hooks here using $this->addFilter();
     */
    public function registerFilters() {
		/* chayka: registerFilters */
        $this->addFilter('Chayka.WP.Query.parseRequest', 'inspectUri');
    }

    /**
     * Register scripts and styles here using $this->registerScript() and $this->registerStyle()
     *
     * @param bool $minimize
     */
    public function registerResources($minimize = false) {
        $this->registerBowerResources(true);

        $this->setResSrcDir('src/');
        $this->setResDistDir('dist/');

		/* chayka: registerResources */
    }

    /**
     * Registering console pages
     */
    public function registerConsolePages(){
        $this->addConsolePage('DoorKeeper', 'update_core', 'doorkeeper', '/admin/doorkeeper', 'dashicons-lock', '80.48294395627454');

        /* chayka: registerConsolePages */
    }

    /**
     * Routes are to be added here via $this->addRoute();
     */
    public function registerRoutes() {
        $this->addRoute('default');
    }
}