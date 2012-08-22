<?php
/**
 * ErrorController
 * Feel free to change this and customize your own error message
 *
 * @author darkredz
 */
class ErrorController extends DooController{

    public function index(){
        echo '<h1>ERROR 404 not found</h1>';
        echo '<p>This is handler by an internal Route as defined in common.conf.php $config[\'ERROR_404_ROUTE\']</p>
                
<p>Your error document needs to be more than 512 bytes in length. If not IE will display its default error page.</p>

<p>Give some helpful comments other than 404 :(
Also check out the links page for a list of URLs available in this demo.</p>';
    }
	

}
?>