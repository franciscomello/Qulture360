<?php
if ( ! function_exists('is_https'))
{
	/**
	 * Is HTTPS?
	 *
	 * Determines if the application is accessed via an encrypted
	 * (HTTPS) connection.
	 *
	 * @return	bool
	 */
	function is_https()
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return TRUE;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		{
			return TRUE;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return TRUE;
		}

		return FALSE;
	}
}

$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST']
					.substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
define('BASE_URL', $base_url);					

define('BASE_PATH', __DIR__ . '/');
define('CONTROLLER_DIR', dirname(__FILE__).'/controllers/');
define('MODELS_DIR', dirname(__FILE__).'/models/');
define('TEMPLATE_PATH', __DIR__ . '/templates/');
define('VENDOR_PATH', __DIR__ . '/vendor/');
define('MEEKRODB_PATH', VENDOR_PATH.'sergeytsalkov/meekrodb/');
define('ASSETS_PATH', BASE_URL.'static');
