<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
define("ACURL_METHOD", "POST");
define("ACURL_TIMEOUT", 30);
define("ACURL_HEADER", ["Cache-Control: no-cache", "Content-Type: application/x-www-form-urlencoded"]);

class Acurl{

	public function __construct(){
	}

    /**
     * a function curl_post of Acurl class
     *
	 * @param string  $url
	 * @param string  $postfields
     * @return array $response | Exception $e
     */
	public function curl_post($url, $postfields, $access_token=null){
        $response=null;
        $CI=&get_instance();

        if($access_token==null && $CI->_userOAuthToken!=null && array_key_exists('access_token', $CI->_userOAuthToken) && $CI->_userOAuthToken['access_token']!=null)
            $access_token=$CI->_userOAuthToken['access_token'];

        if($access_token!=null && !strpos('access_token', $postfields))
            $postfields = $postfields."&access_token=".$access_token;

        try{
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => ACURL_TIMEOUT,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => ACURL_METHOD,
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => ACURL_HEADER,
            ));
        
            $response = curl_exec($curl);
            $err = curl_error($curl);
        
            if($err) throw new Exception($err);
        }
        catch (Exception $e){
            //if (ENVIRONMENT != 'production') show_err($e);
            throw $e;
        }
        finally {
            curl_close($curl);
        }
        return $response;
	}

        /**
     * a function curl_post of Acurl class
     *
	 * @param string  $url
	 * @param array  $arrs
     * @return array $response | Exception $e
     */
	public function curl_post_arrs($url, $arrs, $access_token=null){
        $response=null;
        $postfields=null;
        $CI=&get_instance();

        if(count($arrs)>0){
            foreach ($arrs as $key => $value)
                $postfields .= "{$key}={$value}&";

            if($access_token==null && $CI->_userOAuthToken!=null && array_key_exists('access_token', $CI->_userOAuthToken) && $CI->_userOAuthToken['access_token']!=null)
                $access_token=$CI->_userOAuthToken['access_token'];

            if($access_token!=null && !strpos('access_token', $postfields))
                $postfields = $postfields."access_token=".$access_token;

            try{
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => ACURL_TIMEOUT,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => ACURL_METHOD,
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_HTTPHEADER => ACURL_HEADER,
                ));
            
                $response = curl_exec($curl);
                $err = curl_error($curl);
            
                if($err) throw new Exception($err);
            }
            catch (Exception $e){
                //if (ENVIRONMENT != 'production') show_err($e);
                throw $e;
            }
            finally {
                curl_close($curl);
            }
        }
        return $response;
    }
    
    /**
     * a function curl_post of Acurl class with basic auth by bearer token
     *
	 * @param string  $url
	 * @param string  $postfields
     * @return array $response | Exception $e
     */
	public function curl_post_basic_auth($url, $postfields, $access_token=null){
        $response=null;
        $Acurl_header = ACURL_HEADER;
        $CI=&get_instance();

        if($access_token==null && $CI->_userOAuthToken!=null && array_key_exists('access_token', $CI->_userOAuthToken) && $CI->_userOAuthToken['access_token']!=null)
            $access_token=$CI->_userOAuthToken['access_token'];

        if($access_token!=null) {
            array_push($Acurl_header , 'Authorization: Bearer '.$access_token);

            try{
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => ACURL_TIMEOUT,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => ACURL_METHOD,
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_HTTPHEADER => $Acurl_header ,
                ));
            
                $response = curl_exec($curl);
                $err = curl_error($curl);
            
                if($err) throw new Exception($err);
            }
            catch (Exception $e){
                //if (ENVIRONMENT != 'production') show_err($e);
                throw $e;
            }
            finally {
                curl_close($curl);
            }
        }

        return $response;
    }
    
    /**
     * a function curl_post of Acurl class with basic auth by bearer token
     *
	 * @param string  $url
	 * @param string  $arrs
     * @return array $response | Exception $e
     */
	public function curl_post_arr_basic_auth($url, $arrs, $access_token=null){
        $response=null;
        $postfields=null;
        $Acurl_header = ACURL_HEADER;
        $CI=&get_instance();

        if(count($arrs)>0){
            foreach ($arrs as $key => $value)
                $postfields .= "{$key}={$value}&";

            if($access_token==null && $CI->_userOAuthToken!=null && array_key_exists('access_token', $CI->_userOAuthToken) && $CI->_userOAuthToken['access_token']!=null)
                $access_token=$CI->_userOAuthToken['access_token'];

            if($access_token!=null) {
                array_push($Acurl_header , 'Authorization: Bearer '.$access_token);

                try{
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => ACURL_TIMEOUT,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => ACURL_METHOD,
                        CURLOPT_POSTFIELDS => $postfields,
                        CURLOPT_HTTPHEADER => $Acurl_header ,
                    ));
                
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                
                    if($err) throw new Exception($err);
                }
                catch (Exception $e){
                    //if (ENVIRONMENT != 'production') show_err($e);
                    throw $e;
                }
                finally {
                    curl_close($curl);
                }
            }
        }

        return $response;
    }
}