<?php


namespace WP;
class WPRestAPI
{
    private string $nameSpace;
    private string $route;
    private string $methods;
    private string $callbackFunction;

    /**
     * RestAPI constructor.
     */
    private function __construct(string $nameSpace, string $route, string $methods, string $callbackFunction)
    {
        $this->nameSpace = $nameSpace;
        $this->route = $route;
        $this->methods = $methods;
        $this->callbackFunction = $callbackFunction;
        add_action('rest_api_init', function () {
            register_rest_route($this->nameSpace, '/' . $this->route . '/', [
                'methods' => $this->methods,
                'callback' => $this->callbackFunction,
            ]);
        });
    }


    /**
     * https://site.com/api/v1/get_user/
     * @param string $nameSpace :: example -> v1
     * @param string $route :: example -> get_user
     * @param string $methods :: example -> GET
     * @param string $callbackFunction
     * @return WPRestAPI
     */
    public static function Create(string $nameSpace, string $route, string $methods, string $callbackFunction): WPRestAPI
    {
        return new self($nameSpace, $route, $methods, $callbackFunction);
    }

    /**
     * @param string $url https://site.com/api/method/get_user/
     * @param string $method :: get | post
     * @param array $data [ "Authorization" => "XYC6yzl6Vs9sUdHEKXybxf9x19e6KuypcdpsTCdo" ]
     * @return bool|string
     */
    public static function SendRequest(string $url, string $method, array $data)
    {
        $newData = ["Content-Type: application/json"];
        foreach ($data as $key => $value) {
            $newData['data'] = $key . ":" . $value;
        }

        $ch = curl_init();
        CURL_SETOPT($ch, CURLOPT_URL, $url);
        CURL_SETOPT($ch, CURLOPT_RETURNTRANSFER, True);
        CURL_SETOPT($ch, CURLOPT_POST, true); //Post request
        CURL_SETOPT($ch, CURLOPT_POSTFIELDS, $newData);

        return CURL_EXEC($ch);
    }

}
