<?php

// Factorisation pour casser un algo RSA. Trouver les deux nombre premiers facteurs de N.

$N = 30301;

echo "Algorithme de Shor pour N = " . $N . "\n\n";

// Étape 1
// Prendre un nombre aléatoire tel que a < N
$pasTermine = true;
while ($pasTermine) {
    $a = rand(1, $N - 1);
    echo "Etape 1 : Prendre un nombre (a) aléatoire tel que a < N : " . $a . "\n";

// Etape 2 : Calculer le PGCD de a et N

    echo "Etape 2 : Calculer le PGCD de a ($a) et N ($N) \n";

    $PGCD = intval(pgcd($a, $N));

    echo "Etape 3 : PGCD = " . $PGCD . "\n";

    if ($PGCD !== 1) {
        $p = $PGCD;
        $q = $N / $p;
        echo "Facteurs : p = $p et q = $q \n";
        die();
    }

    echo "Etape 4 : Trouver la période\n";

    $x = 1;
    $i = [];
    while (true) {
        $c = bcmod(bcpow($a, $x), $N);
        echo " ($a ^ $x) = " . bcpow($a, $x) . " =>  % $N = $c" . "\n";
        $i[] = $c;
        if ($c == 1) {
            break;
        }

        if ($x > 16) {
            break;
        }

        $x++;
    }

    echo "  Période : " . implode(", ", $i) . "\n";
    echo "  r = $x \n";
    $r = $x;

    echo "Étape 5 : Vérifier r \n";
    if ($r % 2 == 1) {
        // Nombre impair
        echo "  r est impair \n\nOn retourne à l'étape 1\n\n";
        $pasTermine = true;
    } else {
      echo "Étape 6 : Vérifier a ^ (r/2) mod N != 0 \n";
      echo   bcmod(bcpow($a, $r / 2) + 1, $N) . " \n";
      if(bcmod(bcpow($a, $r / 2) + 1, $N)== 0) {
          echo "Malheureusement si! On retourne à l'étape 1\n\n";
          $pasTermine = true;
        }
        else 
        {
          $p = pgcd((bcpow($a, $r / 2) + 1), $N);
          $q = pgcd((bcpow($a, $r / 2) - 1), $N);
          echo "Facteurs : p = $p et q = $q \n";
          $pasTermine = false;
        }
    }
}
//var_dump($diviseursA);
function pgcd( $a , $b ){
  if (( $a <= 0 ) || ( $b <= 0 )) return;
   while ($b > 0) 
   { 
   $r = bcmod($a, $b); 
   $a = $b; 
   $b = $r; 
   } 
   return $a; 
 }
function diviseurs($n)
{
    $diviseurs = [];
    for ($i = 1; $i < $n; $i++) {
        if ($n % $i == 0) {
            $diviseurs[] = $i;
        }
    }
    return $diviseurs;
}
