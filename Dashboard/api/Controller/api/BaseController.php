<?php
require_once PROJECT_ROOT_PATH . "/Utils/jwt_utils.php";

class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     * 
     * @return array
     */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );

        return $uri;
    }

    /**
     * Get querystring params.
     * 
     * @return array
     */
    protected function getQueryStringParams()
    {
		parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }

    /**
     * Send API output.
     *
     * @param mixed  $data
     * @param string $httpHeader
     */
    protected function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }
	
	protected function write_to_log_file($to_show)
	{
		$run_time=time();
		$fp=fopen("C:/tmp/log_file.txt","a");
		if(is_bool($to_show)){
			$to_show = var_export($to_show,true);
		}
		fwrite($fp,date("d/m/y H:i:s",$run_time)."\r\n".print_r($to_show,true)."\r\n");
		fclose($fp);
	}
}