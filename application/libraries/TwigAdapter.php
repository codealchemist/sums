<?php
/**
 * Provides easy access to Twig instance.
 * 
 * @author Alberto Miranda <alberto.php@gmail.com>
 */
class TwigAdapter {
    public function __construct() { //called when autoloading by CI
        $CI =& get_instance();
        $config = $CI->config->item('twig');
        $loader = new Twig_Loader_Filesystem($config['templatesPath']);
        $twig = new Twig_Environment($loader, array(
            //'cache' => $config['cachePath'] //TODO: enable cache
        ));
        
        $CI->twig = $twig;
    }
}
