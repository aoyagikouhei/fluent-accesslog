<?php
namespace Fluent;

class Accesslog
{
    /**
     * @var logger
     */
    protected $logger;

    /**
     * @var options
     *    host : host name (localhost)
     *    port : port (24224)
     *    tag : tag (accesslog)
     *    tag_with_date : postfix tag date format
     *    error_handler : error handler
     *    mask : mask parameter
     *    mask_value : masked value(xxx)
     *    remove : remove parameter
     *    request_key : request key
     *    server : $_SEVER keys and values
     */
    protected $options;

    /**
     * Constructor
     * @param  $options
     */
    public function __construct($options=null)
    {
        $this->options = array_merge(
            array(
                'host' => 'localhost',
                'port' => 24224,
                'tag' => 'accesslog',
                'mask' => array(),
                'mask_value' => 'xxx',
                'remove' => array(),
                'server' => array(),
                'request_key' => 'r'
            ), 
            is_null($options) ? array() : $options);
        if (isset($this->options['tag_with_date'])) {
            $ts = new \DateTime();
            $this->options['tag'] = 
                $this->options['tag'] . $ts->format($this->options['tag_with_date']);
        }
        $this->logger = new \Fluent\Logger\FluentLogger(
            $this->options['host'], 
            $this->options['port']
        );
        if (isset($this->options['error_handler'])) {
            $this->logger->registerErrorHandler($this->options['error_handler']);
        }
    }

    protected function getRequest() {
        return $_REQUEST;
    }

    /**
     * add accesslog
     * @param $others other parameter
     * @param $tagPrefix tag prefix
     * @param $tagPostfix tag postfix
     */
    public function add($others = null, $tagPrefix='', $tagPostfix='')
    {
        $request = $this->getRequest();
        foreach ($this->options['remove'] as $key) {
            if (isset($request[$key])) {
                unset($request[$key]);
            }
        }
        foreach ($this->options['mask'] as $key) {
            if (isset($request[$key])) {
                $request[$key] = $this->options['mask_value'];
            }
        }
        $server = array();
        foreach ($this->options['server'] as $key => $value) {
            if (isset($_SERVER[$key])) {
                $server[$value] = $_SERVER[$key];
            }
        }
        $params = array_merge(
            array($this->options['request_key'] => $request),
            $server,
            is_null($others) ? array() : $others
        );
        $this->logger->post(
            $tagPrefix . $this->options['tag'] .  $tagPostfix,
            $params
        );
    }
}
