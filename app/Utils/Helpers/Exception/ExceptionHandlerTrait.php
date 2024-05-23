<?php


namespace App\Utils\Helpers\Exception;


use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;


/**
 * Trait ExceptionHandlerTrait
 *
 * Реализует набо функций, который делает из стандартного
 * хендлера ошибок Laravel кастомный.
 *
 *
 *
 * @package App\Utils\Helpers\Exception
 */
trait ExceptionHandlerTrait {

    /**
     * Перезаписываем метод подготовки ответа при возникновении ошибки
     *
     * @param           $request
     * @param Throwable $e
     * @return Application|ResponseFactory|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e) {

        // стандартно определяем какой ответ нужен json или http
        return $this->shouldReturnJson($request, $e)
            ? $this->prepareJsonResponse($request, $e)
            : $this->prepareResponse($request, $e);

    }

    /**
     * Готовим ответ json
     * @param Request   $request
     * @param Throwable $e
     * @return JsonResponse
     */
    public function prepareJsonResponse($request, Throwable $e) {
        $errorData = new ExceptionJsonResponse($e);
        return \response()->json($errorData,$errorData->code);
    }

    /**
     * Готовим ответ html.
     * Для html ответа под проект должна быть сделана форматированная страница.
     * Надо будет ее сюда вписать.
     *
     * @param Request   $request
     * @param Throwable $e
     * @return Application|ResponseFactory|Response
     */
    public function prepareResponse($request, Throwable $e) {
        $url = $request->url();
        return response("TODO - html page response ({$url}): "
            . $e->getMessage());
    }


    /**
     * Стандартно логируем все ошибки в отдельные файлы
     * @param Throwable $e
     */
    public function report(Throwable $e) {
        ExceptionFileLogger::logException($e);
    }

}
