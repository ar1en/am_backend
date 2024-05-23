<?php


namespace App\Utils\Helpers\Http;


use Illuminate\Http\JsonResponse;

/**
 * Class HResponse
 *
 * Вспомогательные функции для формирования ответа сервера
 *
 * @package App\Utils\Helpers\Http
 */
class HResponse {


    /**
     * Send success json response.
     *
     * @param mixed       $data
     * @param string|null $message
     * @param array       $additional
     * @return JsonResponse
     */
    public static function json($data = null, ?string $message = null, array $additional = []) {
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
        foreach ($additional as $key => $item) {
            $response[$key] = $item;
        }
        return response()->json($response, 200);
    }


    /**
     * Send json with error 520 - Unknown error.
     *
     * @param string $error
     * @param array  $errorMessages
     * @param int    $code
     * @return JsonResponse
     */
    public static function jsonError(string $error, array $errorMessages = [], int $code = 520) {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

}
