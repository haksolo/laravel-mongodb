<?php

namespace Khronos\MongoDB\Foundation\Http\Middleware;

use Closure;
use MongoDB\BSON\ObjectId;

class ConvertToObjectId
{
    /**
     * The regex that should match the string.
     *
     * @var string
     */
    const OBJECTID_REGEX = '/^[0-9a-f]{24}$/';

    /**
     * The attributes that should not be converted to ObjectId.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();

        foreach ($route->parameters() as $key => $parameter) {
            if (in_array($key, $this->except, true)) {
                return $parameter;
            }

            if (preg_match(self::OBJECTID_REGEX, $parameter)) {
                $route->setParameter($key, new ObjectId($parameter));
            }
        }

        return $next($request);
    }
}
