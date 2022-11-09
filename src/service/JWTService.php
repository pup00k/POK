<?php 

namespace App\service;

use DateTimeImmutable;



class JWTService{

    // On génère le token pour verif mail 
   

    /**
     * Génération du JWT 
     * @param array $header
     * @param array $payload
     * @param string $secret
     * @param int $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string{

        if($validity > 0){
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }


        // On encode en base64, parce que les JSON web token c'est de l'encodage en base64 

        $base64Header = base64_encode(json_encode($header)); // JSON en code mon header doit etre transformer en json puis en 64 pour être une chaine de caractère
        $base64Payload = base64_encode(json_encode($payload));

        // On nettoie les valeurs encodées ( retrait des +, / et = ) car quand on fait un base 64 on en aura et dans les json web toekn on les utilise pas, ça casse on remplace par tiret (+) 
        // underscord(/)

        $base64Header = str_replace(['+','/','='], ['-','_',''], $base64Header);
        $base64Payload = str_replace(['+','/','='], ['-','_',''], $base64Payload);

        // On génère la signature, pour ça il faut un secret ( on a notre secret qui a été passé, mais on doit le stocké quelque part ENV/PARAMETER DANS SERVICE YAML )

        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true );

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+','/','='], ['-','_',''], $base64Signature);

        // On créer enfin le token 

        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;



        return $jwt;
    }

    // On vérifié que le toekn est valide (correctement formé)

    public function isValid(string $token): bool{

        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token

        ) === 1;
    }

    // On récupère le Header pour vérifier si ça a expirer ou pas

    public function  getHeader( string $token): array {

        // On démonte le token 
        $array = explode('.', $token);

        //  ON décode le header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;

    }
        // On récupère le Header pour vérifier si ça a expirer ou pas

        public function  getPayload( string $token): array {

            // On démonte le token 
            $array = explode('.', $token);
    
            //  ON décode le header
            $payload = json_decode(base64_decode($array[1]), true);
    
            return $payload;
    
        }

    // On vérifie si le token a expiré 

    public function isExpired(string $token, int $validity = 10800): bool{

        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();
        // $exp = $now->getTimestamp() + $validity;
        // $payload['exp'] = $exp;
        return $payload['exp']< $now->getTimestamp();

    }

    // On vérifie la signature du Token  

    public function check(string $token, string $secret)
    {
        // On recupère le header et le payload 
        $header = $this->getHeader($token);
        $payload = $this->getPayLoad($token);


        // On génère un token 
        $verifToken = $this->generate($header, $payload, $secret, 0); // en mettant zero on ne va pas regéngerer d'autre date
    
        return $token === $verifToken;
    }
}