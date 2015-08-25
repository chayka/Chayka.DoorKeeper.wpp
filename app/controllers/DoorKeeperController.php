<?php

namespace Chayka\DoorKeeper;

use Chayka\WP\Models\PostModel;
use Chayka\WP\MVC\Controller;

class DoorKeeperController extends Controller{

    public function init(){
    }

    public function doorClosedAction(){
        $this->view->assign('title', OptionHelper::getOption('title'));
        $this->view->assign('message', OptionHelper::getOption('message'));
        $this->view->assign('useHeaderFooter', !!OptionHelper::getOption('useHeaderFooter'));
        $imageId = OptionHelper::getOption('image');
        if($imageId){
            $image = PostModel::selectById($imageId);
            if($image) {
                $imageData = $image->loadImageData( 'large' );
                $this->view->assign('image', $imageData);
            }
        }
    }
}