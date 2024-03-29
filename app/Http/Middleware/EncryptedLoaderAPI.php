<?php

namespace App\Http\Middleware;

use App\Models\Loader;
use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class EncryptedLoaderAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has("data")) {
            abort(403);
        }
        $version = $request->header('loader_version');
        $loader = Loader::where('version', '=', $version)->get()->first();

        if (!$loader) { //
            abort(403); // Loader is not in our DB so they are forbidden
        }
        if (!$loader->enabled) {
            abort(401);
        }

        $privateKeyLoc = $loader->encryption_key_private;
        $privateKey = Storage::get($privateKeyLoc);

        openssl_private_decrypt(base64_decode($request->get('data')), $decrypted, openssl_get_privatekey($privateKey));

        $data = json_decode($decrypted, true);
        $request->replace($data);
        return $next($request);

        //$publicKey = file_get_contents('/mnt/x/Development/CERT/publickey.pem');


        //$plaintext = json_encode($request->all());
        //openssl_public_encrypt($plaintext, $encrypted, openssl_get_publickey($publicKey));
        //dd(base64_encode($encrypted));
    }
}
