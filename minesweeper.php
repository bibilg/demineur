<?php 

/* FP1 : Initialiser */
function Initialiser(&$tableau_information, &$tableau_affichage, &$nb_cases_horizontales, &$nb_cases_verticales, &$nb_bombes)
{
    echo ' Quel niveau voulez-vous choisir ?' . PHP_EOL;
    echo ' Pour le niveau débutant tapez 1, pour le niveau intermédiaire tapez 2, pour le niveau expert tapez 3' . PHP_EOL;

    $choix = readline();

    if($choix == 2)
    {
        Initialisation_intermediaire($nb_cases_horizontales, $nb_cases_verticales, $nb_bombes);

    }
    elseif($choix == 3)
    {
        Initialisation_expert($nb_cases_horizontales, $nb_cases_verticales, $nb_bombes);

    }
    else // Si il rentre 1 ou autre chose on fais le niveau débutant
    {
        Initialisation_debutant($nb_cases_horizontales, $nb_cases_verticales, $nb_bombes);
    }

    Initialisation_tableaux($nb_cases_horizontales, $nb_cases_verticales, $tableau_information, $tableau_affichage);

    $premier_coup_x=null;
    $premier_coup_y=null;

    Position_premier_coup($premier_coup_x, $premier_coup_y, $nb_cases_horizontales, $nb_cases_verticales);

    Placement_bombes($tableau_information, $nb_cases_horizontales, $nb_cases_verticales, $premier_coup_x, $premier_coup_y, $nb_bombes);

    //Pour tester à supprimer 
    Affichage_tableau($tableau_information, $nb_cases_horizontales, $nb_cases_verticales);
}

/* FS 1.1 : Initialisation_debutant  */
function Initialisation_debutant(&$nb_cases_horizontales, &$nb_cases_verticales, &$nb_bombes)
{
    $nb_cases_horizontales = 9;
    $nb_cases_verticales = 9;

    $nb_bombes = 10;
}

/* FS 1.2 : Initialisation_intermediaire  */
function Initialisation_intermediaire(&$nb_cases_horizontales, &$nb_cases_verticales, &$nb_bombes)
{
    $nb_cases_horizontales = 16;
    $nb_cases_verticales = 16;

    $nb_bombes = 40;
}

/* FS 1.3 : Initialisation_expert  */
function Initialisation_expert(&$nb_cases_horizontales, &$nb_cases_verticales, &$nb_bombes)
{
    $nb_cases_horizontales = 40;
    $nb_cases_verticales = 16;

    $nb_bombes = 99;
}

//FS 1.4 : Initialisation_tableaux 
function Initialisation_tableaux($nb_cases_horizontales, $nb_cases_verticales, &$tableau_information, &$tableau_affichage)
{
    // Initialisation tableau_information
    for( $y =0 ; $y < $nb_cases_verticales + 2 ; $y++) // +2 
    {
        for( $x =0 ; $x < $nb_cases_horizontales + 2 ; $x++) // +2 
        {
            $tableau_information[$y][$x] = 0;
        }
    }
    
    // Initialisation tableau_affichage
    for( $y =0 ; $y < $nb_cases_verticales; $y++) // +2 
    {
        for( $x =0 ; $x < $nb_cases_horizontales; $x++) // +2 
        {
            $tableau_affichage[$y][$x] = 'M';
        }
    }
}

// FS 1.5 : Position_premier_coup
function Position_premier_coup(&$premier_coup_x, &$premier_coup_y, $nb_cases_horizontales, $nb_cases_verticales)
{
    echo 'Quel est la position en X de votre premier coup ? (Maximum ' . $nb_cases_horizontales . ')' . PHP_EOL;
    $premier_coup_x = readline();

    echo 'Quel est la position en Y de votre premier coup ? (Maximum ' . $nb_cases_verticales . ')' . PHP_EOL;
    $premier_coup_y = readline();
}

// FS 1.6 : Placement_bombes
function Placement_bombes(&$tableau_information, $nb_cases_horizontales, $nb_cases_verticales, $premier_coup_x, $premier_coup_y, $nb_bombes)
{
    for( $i=0 ; $i < $nb_bombes ; $i++)
    {
        $x = rand(1 , $nb_cases_horizontales);
        $y = rand(1, $nb_cases_verticales);

        if ($tableau_information[$x][$y] == -1)
        {
            $i--;
        }
        elseif( ($x == $premier_coup_x) && ($y == $premier_coup_y)) // A regarder avec tableau 0 ( rajouter +1 ?)
        {
            $i--;
        }
        else
        {
            $tableau_information[$x][$y] = -1;
            Incrementer_autour_bombe($tableau_information, $x , $y);
        }
    }
}

function Incrementer_autour_bombe(&$tableau_information, $x, $y)
{
    for( $a = -1 ; $a < 2 ; $a++)
    {
        for( $b = -1 ; $b < 2 ; $b++)
        {
            if( $tableau_information[$x+$b][$y+$a] != -1 )
            {
                $tableau_information[$x+$b][$y+$a]++;
            }
        }
    }
}

/* Pour tester à supprimer */
function Affichage_tableau($tab,$nb_cases_horizontales, $nb_cases_verticales)
{
    for( $y =0 ; $y < $nb_cases_verticales+4; $y++)
    {
        for( $x =0 ; $x < $nb_cases_horizontales +4; $x++)
        {
            if($tab[$x][$y] == -1)
            {
                echo 'B ';
            }
            else
            {
                echo $tab[$x][$y] . ' ';
            }
            
        }
        echo PHP_EOL;
    }
}



$tableau_information=array();
$tableau_affichage= array();
$nb_cases_horizontales=null;
$nb_cases_verticales = null;
$nb_bombes=null;

Initialiser($tableau_information, $tableau_affichage, $nb_cases_horizontales, $nb_cases_verticales, $nb_bombes);

