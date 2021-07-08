<?php
/**
 * Created by PhpStorm.
 * User: Yoakim
 * Date: 10/27/2018
 * Time: 8:25 AM
 */

namespace App\Traits;


use App\Models\NoiseawareConnection;
use App\Models\NoiseawareSetting;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait MakesRemoteHttpRequests
{
    private $baseUrl;

    /**
     * @param string $url
     * @param array $query
     * @param array $headers
     *
     * @return array
     * @throws Exception
     */
    protected function get(string $url, $query = [], $headers = [])
    {
        $response = Http::withHeaders($headers)->get($url, $query);
        if (!$response->ok())
        {
            throw new Exception($response->body());
        }
        return $response->json();
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     *
     * @return array
     * @throws Exception
     */
    protected function post($url, $body = [], $headers = [])
    {
        $response = Http::withHeaders($headers)->post($url, $body);
        if (!$response->ok())
        {
            throw new Exception($response->body());
        }
        return $response->json();
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     *
     * @return array
     * @throws Exception
     */
    protected function put($url, $body = [], $headers = [])
    {
        $response = Http::withHeaders($headers)->put($url, $body);
        if (!$response->ok())
        {
            throw new Exception($response->body());
        }
        return $response->json();
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     *
     * @return array
     * @throws Exception
     */
    protected function patch($url, $body = [], $headers = [])
    {
        $response = Http::withHeaders($headers)->patch($url, $body);
        if (!$response->ok())
        {
            throw new Exception($response->body());
        }
        return $response->json();
    }

    /**
     * Fetch user token from NA
     * Endpoint: http://portal.apps.noiseaware.io/token
     * @return mixed|null
     * @throws Exception
     */
    public function getNoiseawareToken($fsid)
    {
        $connection = NoiseawareConnection::firstWhere(['FSID' => $fsid]);
        if (!$connection)
        {
            throw new Exception("No PM with FSID {$fsid} has connected to noiseaware!");
        }
        if ($connection->hasNoToken() || $connection->hasExpiredToken())
        {
            // we will get a new token
            $data = [
                'clientID' => $connection->client_id,
                'accessCode' => $connection->access_code,
            ];
            Log::info("HITTING NEW TOKEN ENDPOINT {$this->baseUrl}/token", $data);
            $response = $this->post("{$this->baseUrl}/token", $data);
            Log::info("NEW TOKEN ENDPOINT HIT", $response);
            $expiresAt = explode(".", $response['data']['expiresAt'])[0];
            $connection->access_token = $response['data']['userToken'];
            $connection->refresh_token = $response['data']['refreshToken'];
            $connection->expires_at = $expiresAt;
            $connection->save();
        }
        return $connection->access_token;
    }

    private function loginFuturestayToNoiseaware()
    {
        $data = [
            'email' => env('NOISEAWARE_EMAIL'),
            'password' => env('NOISEAWARE_PASSWORD')
        ];
        $url = "{$this->baseUrl}/login";
        $response = $this->post($url, $data);
        $expiresAt = explode(".", $response['expiresAt'])[0];
        // save settings for subsequent requests within the next 15 minutes
        NoiseawareSetting::set('user_token', $response['userToken']);
        NoiseawareSetting::set('refresh_token', $response['refreshToken']);
        NoiseawareSetting::set('expires_at', $expiresAt);
        return $response['userToken'];
    }

}
