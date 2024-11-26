<?php if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

/**
 * Class NF_Nobi_Actions_Nobi
 */
final class NF_Nobi_Actions_Nobi extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'nobi';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'late';

    /**
     * @var int
     */
    protected $_priority = '10';

    /**
     * @var array
     */
    protected $_debug = array();

    /**
     * Constructor
     */
    public function __construct()
{
    parent::__construct();

    $this->_nicename = __( 'Nobi', 'nobi_connect' );

    add_action( 'admin_init', array( $this, 'init_settings' ) );

    add_action( 'ninja_forms_builder_templates', array( $this, 'builder_templates' ) );

}

    /*
    * PUBLIC METHODS
    */

    public function save( $action_settings )
    {
    
    }

    public function init_settings()
    {
        $settings = NF_Nobi::config( 'ActionNobiSettings' );
        $this->_settings = array_merge( $this->_settings, $settings );

    }

    public function builder_templates()
    {

        NF_Nobi::template( 'args-repeater-row.html.php' );
    }


    public function process( $action_settings, $form_id, $data )
    {

        $nb_args         = $action_settings[ 'nb-args' ];

        $args = array();
        foreach ( $nb_args as $arg_data ) {
            $args[ $arg_data[ 'key' ] ] = $arg_data[ 'value' ];
        }

        $options = get_option('nobi_connect_options');

        if (!isset($options) || !isset($options['domain']) || !isset($options['apikey'])) return;
    
        $lead = new NobitaLead($args);
        $callback = 'https://'.$options['domain'].'/public-api/leads/createLead';

        wp_remote_post($callback, array(
            'headers'     => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'ApiKey' => $options['apikey']
            ),
            'body'  => $lead->to_json(),
            'method'      => 'POST',
            'data_format' => 'body'
        ));
        

        $data[ 'actions' ][ 'nobi' ][ 'args' ] = $args;

        return $data;
    }
}
