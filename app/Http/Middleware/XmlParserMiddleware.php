<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JsonException;

final class XmlParserMiddleware
{
    /**
     * @throws JsonException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isXmlHttpRequest() || $request->header('Content-Type') === 'text/xml') {
            $xml = simplexml_load_string($request->getContent());
            $json = json_encode($xml, JSON_THROW_ON_ERROR);
            $array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

            $request->merge(['xml' => $array]);
        }

        return $next($request);
    }
}
