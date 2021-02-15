<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class AuthenticateNote
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $action
     * @return mixed
     */
    public function handle($request, Closure $next, $action = null)
    {
        $token = $request->input("token");
        if(empty($token)){
            $token = $request->header("token");
        }
        $slug = $request->route('slug');
        if (!empty($slug)) {
            $results = DB::table('notes')->where('slug', $slug)->first();
            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $request->merge(["note" => $results]);
                $header_token = $this->encryptPassword($token);
                $item_password = $results->password;
                $item_type = $results->type;
                $request->merge(["headerToken" => $header_token]);

                if ($action == "read") {
                    if ($item_type == "Public" || $item_type == "Protected" || ($item_type == "Private" && $item_password == $header_token)) {
                        return $next($request);
                    }
                } else if ($action == "write") {
                    if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {
                        return $next($request);
                    }
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }

        $response = [
            "code" => 100,
            "error" => "Unautheticated or re-authenticate yourself",
            "content" => null,
            "request_at" => time()
        ];
        return response()->json($response, 401);
    }
    private function encryptPassword($pass)
    {
        $masala = env("APP_SECRET_MASALA");
        return hash("sha256", $masala . $pass);
    }
}
