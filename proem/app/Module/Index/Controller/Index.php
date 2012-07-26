<?php

namespace Module\Index\Controller;

class Index extends \Proem\Controller\Standard
{
    public function indexAction()
    {   
        if ($this->assets->has('response')) {
            $this->assets->get('response')
		->appendToBody('<html><head><title>Hello World</title></head><body><h1>Hello World</h1></body></html>');
        }   
    }   
}
