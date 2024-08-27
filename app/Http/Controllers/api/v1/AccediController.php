<?php

namespace App\Http\Controllers\api\v1;

use App\Helpers\AppHelpers;
use App\Http\Controllers\Controller;
use App\Models\Accesso;
use App\Models\Auth;
use App\Models\Configurazione;
use App\Models\Password;
use App\Models\Sessione;
use AppHelpers as GlobalAppHelpers;
use Illuminate\Http\Request;
use Stringable;
use Illuminate\Support\Str;

class AccediController extends Controller
{
    // Cerco l'hash dello user nel DB
    public function searchMail($utente){
        $tmp = (Auth::esisteUtente($utente)) ? true : false;
        return AppHelpers::rispostaCustom($tmp);
    }

    public static function show($utente, $hash = null)
    {
        if ($hash == null) {
            return AccediController::controlloUtente($utente);
        } else {
            return AccediController::controlloPassword($utente, $hash);
        }
    }

    public static function testToken(){
        $utente = hash("sha512", trim("Admin@Utente"));
        $password = hash("sha512", trim("Password123!"));
        $sale = hash("sha512", trim("Sale"));
        $sfida = hash("sha512", trim("Sfida"));
        $secretJWT = hash("sha512", trim("Secret"));
        $auth = Auth::where('user', $utente)->firstOrFail();
        if ($auth != null) {
            $auth->inizioSfida = time();
            //$auth->sfida = $sfida;
            $auth->secretJWT = $secretJWT;
            $auth->save();

            $recordPassword = Password::passwordAttuale($auth->idContatto);
            if ($recordPassword != null) {
                $recordPassword->sale = $sale;
                $recordPassword->psw = $password;
                $recordPassword->save();
                //$cipher = AppHelpers::creaPasswordCifrata($password, $sale, $sfida);
                $cipher = AppHelpers::nascondiPassword($password, $sale);
                $tk = AppHelpers::creaTokenSessione($auth->idContatto, $secretJWT);
                $dati = array("token" => $tk, "xLogin" => $cipher);
                $sessione = Sessione::where('idContatto', $auth->idContatto)->firstOrFail();
                $sessione->token = $tk;
                $sessione->inizioSessione = time();
                $sessione->save();
                return AppHelpers::rispostaCustom($dati);
            }
        }
    }


    public static function testLogin() {
        $hashpassword = "16272a5dd83c63010e9f67977940e871";
        $hashUtente = "b7548e908a98b534628a940d2f004cd8";
        return AccediController::controlloPassword($hashUtente, $hashpassword);
    }


    public static function verificaToken($token){
        $rit = null;
        $sessione = Sessione::datiSessione($token);
        if ($sessione != null) {
            $inizioSessione = $sessione->inizioSessione;
            $durataSessione = Configurazione::leggiValore('durataSessione');
            $scadenzaSessione = $inizioSessione + $durataSessione;
            //echo ("PUNTO 2<br>");
            if (time() < $scadenzaSessione) {
                //echo ("PUNTO 2<br>");
                $auth = Auth::where("idContatto", $sessione->idContatto)->first();
                if ($auth != null) {
                    //echo ("PUNTO 3<br>");
                    $secretJWT = $auth->secretJWT;
                    $payload = AppHelpers::validaToken($token, $secretJWT, $sessione);
                    if ($payload != null) {
                        //echo ("PUNTO 4<br>");
                        $rit = $payload;
                    } else {
                        abort(403, "TK_0006");
                    }
                } else {
                    abort(403, "TK_0005");
                }
            } else {
                abort(403, "TK_0004");
            }
        } else {
            abort(403, "TK_0003");
        }
        return $rit;
    }

    public static function controlloUtente($utente) {
        // $sfida = hash("sha512", trim(Str::random(200)));
        $sale = hash("sha512", trim(Str::random(200)));
        if (Auth::esisteUtenteValidoPerLogin($utente)) {
            //esiste
            $auth = Auth::where('user', $utente)->first();
            //$auth->sfida = $sfida;
            $auth->secretJWT = hash("sha512", trim(Str::random(200)));
            $auth->inizioSfida = time();
            $auth->save();
            $recordPassword = Password::passwordAttuale($auth->idContatto);
            $recordPassword->sale = $sale;
            $recordPassword->save();
        } else {
            //non esiste, quindi invento sfida o sale per confondere le idee
        }
        //$dati = array("sfida" => $sfida, "sale" => $sale);
        $dati = array("sale" => $sale);
        return AppHelpers::rispostaCustom($dati);
    }
    public static function controlloPassword($utente, $hashClient){
        if (Auth::esisteUtenteValidoPerLogin($utente)) {
            //esiste
            $auth = Auth::where("user", $utente)->first();
            //$sfida = $auth->sfida;
            $secretJWT = $auth->secretJWT;
            $inizioSfida = $auth->inizioSfida;
            $durataSfida = Configurazione::leggiValore("durataSfida");
            $maxTentativi = Configurazione::leggiValore("maxLoginErrati");
            $scadenzaSfida = $inizioSfida + $durataSfida;
            if (time() < $scadenzaSfida) {
                $tentativi = Accesso::contaTentativi($auth->idContatto);
                if ($tentativi < $maxTentativi - 1) {
                    //proseguo
                    $recordPassword = Password::passwordAttuale($auth->idContatto);

                    $password = $recordPassword->psw;
                    $sale = $recordPassword->sale;
                    //$hashFinaleDB = AppHelpers::creaPasswordCifrata($password, $sale, $sfida);
                    $passwordNascostaDB = AppHelpers::nascondiPassword($password, $sale);

                    //$passwordClient = AppHelper::decifra($hashClient, $secretJWT);
                    if ($hashClient == $passwordNascostaDB) {
                        //login corretto quindi creo token
                        $tk = AppHelpers::creaTokenSessione($auth->idContatto, $secretJWT);
                        Accesso::eliminaTentativi($auth->idContatto);
                        $accesso = Accesso::aggiungiAccesso($auth->idContatto);

                        Sessione::eliminaSessione($auth->idContatto);
                        Sessione::aggiornaSessione($auth->idContatto, $tk);

                        $dati = array("tk" => $tk);
                        return AppHelpers::rispostaCustom($dati);
                    } else {
                        Accesso::aggiungiTentativoFallito($auth->idContatto);
                        abort(403, "ERR L004");
                    }
                } else {
                    abort(403, "ERR L003");
                }
            } else {
                Accesso::aggiungiTentativoFallito($auth->idContatto);
                abort(403, "ERR L002");
            }
        } else {
            abort(403, "ERR L001");
        }
    }
}
