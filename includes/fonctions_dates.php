<?php
/* isLastDayOfYearWednesday
   Fonction pour déterminer si le 31 décembre de l'année en cours est un mercredi
   afin de gérer le cas où on afficherait "N.C." pour 31/12 qui est effectivement une date de sortie
*/
function isLastDayOfYearWednesday($year){
    $lastDay = strtotime($year . '-12-31');

    if(date('D', $lastDay) === 'Wed')
        return true;
    else
        return false;

}

/* formatDateForDisplay
   Les dates sont stockées au format AAAAMMJJ. Cette fonction renvoie la date au format
   JJ/MM/AAAA pour l'affichage. Si elle ne comporte pas 8 caractères, on renvoie l'argument
*/
function formatDateForDisplay($date){
    if (strlen($date) == 8)
        return substr($date, 6, 2) . '/' . substr($date, 4, 2) . '/' . substr($date, 0, 4);
    else
        return $date;
}

/* formatTimeForDisplay
   Les heures sont stockées au format HHMMSS. Cette fonction renvoie l'heure au format
   HH:MM:SS pour l'affichage. Si elle ne comporte pas 6 caractères, on renvoie l'argument
*/
function formatTimeForDisplay($time){
    if (strlen($time) == 6)
        return substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2);
    else
        return $time;
}

/* formatDateForInsert
   Les dates sont stockées au format AAAAMMJJ. Cette fonction renvoie la date au format
   AAAAMMJJ pour l'insertion en base. Si elle ne comporte pas 8 caractères, on renvoie l'argument
*/
function formatDateForInsert($date){
    if (strlen($date) == 10)
        return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    else
        return $date;
}

/* isBlankDate
    <=> si on affiche "N.C." dans le tableau pour une date inconnue (laissée à blanc)
   Retourne vrai si la date est 31/12 et que la date n'est pas un mercredi
   ou si la date est 30/12 et que le dernier jour de l'année est un mercredi
   Retourne faux sinon
*/
function isBlankDate($date){
    $isLastDayWednesday = isLastDayOfYearWednesday(date('Y'));
    $thirtiethOfDecember = date ('Y') . '1230';
    $thirtyFirstOfDecember = date ('Y') . '1231';

    if (($date == $thirtyFirstOfDecember && !$isLastDayWednesday)
       || ($date == $thirtiethOfDecember && $isLastDayWednesday))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>