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

    Position_premier_coup($premier_coup_x, $premier_coup_y, $nb_cases_horizontales, $nb_cases_verticales, $tableau_affichage);

    Placement_bombes($tableau_information, $nb_cases_horizontales, $nb_cases_verticales, $premier_coup_x, $premier_coup_y, $nb_bombes);
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
function Position_premier_coup(&$premier_coup_x, &$premier_coup_y, $nb_cases_horizontales, $nb_cases_verticales, &$tableau_affichage)
{
    echo 'Quel est la position en X de votre premier coup ? (Maximum ' . $nb_cases_horizontales . ')' . PHP_EOL;
    $premier_coup_x = readline();

    echo 'Quel est la position en Y de votre premier coup ? (Maximum ' . $nb_cases_verticales . ')' . PHP_EOL;
    $premier_coup_y = readline();

    $tableau_affichage[$premier_coup_x-1][$premier_coup_y-1]='R';
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


// FP2 : Coup_joueur
function Coup_joueur(&$tableau_affichage, $tableau_information, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales, &$partie_perdue, &$partie_gagnee)
{
    echo 'Quel est la position en X de votre coup ? (Maximum ' . $nb_cases_horizontales . ')' . PHP_EOL;
    $coup_x = readline();

    echo 'Quel est la position en Y de votre coup ? (Maximum ' . $nb_cases_verticales . ')' . PHP_EOL;
    $coup_y = readline();

    
    if(Est_dans_limite($coup_x, $coup_y, $nb_cases_horizontales, $nb_cases_verticales))
    {
        echo 'Si vous voulez placer un drapeau, tapez 1' . PHP_EOL;
        echo 'Si vous voulez enlever un drapeau, tapez 2' . PHP_EOL;
        echo 'Si vous voulez réveler la case, tapez 3' . PHP_EOL;

        $choix=readline();

        switch($choix)
        {
            case 1:
               Placement_drapeau($coup_x, $coup_y, $tableau_affichage);
                break;
            case 2:
                Enlever_drapeau($coup_x, $coup_y, $tableau_affichage);
                break;
            case 3:
                Reveler_case($coup_x, $coup_y, $tableau_affichage, $tableau_information, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales, $partie_perdue, $partie_gagnee);
                break;
        }

        Affichage_tableau($tableau_affichage, $nb_cases_horizontales, $nb_cases_verticales);
    }
}

// FS 2.1 : Est_dans_limite
function Est_dans_limite($coup_x, $coup_y, $nb_cases_horizontales, $nb_cases_verticales)
{
    if($coup_x > 0 && $coup_x <= $nb_cases_horizontales)
    {
        if($coup_y > 0 && $coup_y <= $nb_cases_verticales)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    return false;
}

// FS 2.2 : Placement_drapeau
function Placement_drapeau($coup_x, $coup_y, &$tableau_affichage)
{
    if($tableau_affichage[$coup_x-1][$coup_y-1] == 'M') // -1 car tableau commence à 0 en php 
    {
        $tableau_affichage[$coup_x-1][$coup_y-1] = 'F';
    }
}

// FS 2.3 : Enlever_drapeau
function Enlever_drapeau($coup_x, $coup_y, &$tableau_affichage)
{
    if($tableau_affichage[$coup_x-1][$coup_y-1] == 'F') // -1 car tableau commence à 0 en php 
    {
        $tableau_affichage[$coup_x-1][$coup_y-1] = 'M';
    }
}

// FS 2.4 : Reveler_case()
function Reveler_case($coup_x, $coup_y, &$tableau_affichage, $tableau_information, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales, &$partie_perdue, &$partie_gagnee)
{
    $partie_perdue=false;
    $partie_gagnee=false;

    if( $tableau_affichage[$coup_x-1][$coup_y-1]=='M' )
    {
        $tableau_affichage[$coup_x-1][$coup_y-1]='R';

        if( $tableau_information[$coup_x][$coup_y]== -1 )
        {
            $partie_perdue=true;
        } 
        elseif( $tableau_information[$coup_x][$coup_y]== 0 )
        {
            Reveler_cases_vides_autour($coup_x-1, $coup_y-1, $tableau_affichage, $tableau_information, $nb_cases_horizontales, $nb_cases_verticales);// -1 car php
        }

        Partie_gagnee($tableau_affichage, $partie_gagnee, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales);
    }
}

// FS 2.4.1 : Reveler_cases_vides_autour
function Reveler_cases_vides_autour($coup_x, $coup_y, &$tableau_affichage, $tableau_information, $nb_cases_horizontales, $nb_cases_verticales)
{
    $x = $coup_x;
    $y = $coup_y;

    for( $a = -1 ; $a < 2 ; $a++)
    {
        for( $b = -1 ; $b < 2 ; $b++)
        {
            if( ($a!=0) && ($b!=0) )
            {
                if ( Est_dans_limite($x+$a, $y+$b, $nb_cases_horizontales, $nb_cases_verticales) )
                {
                    if( $tableau_information[$x+$a+1][$y+$b+1] == 0 )
                    {
                        if($tableau_affichage[$x+$a][$y+$b] == 'M')
                        {
                            $tableau_affichage[$x+$a][$y+$b] == 'R';
                            Reveler_cases_vides_autour($x+$a, $y+$b, $tableau_affichage, $tableau_information, $nb_cases_horizontales, $nb_cases_verticales);
                        }
                    }
                }
            }

        }
    }
}

// FS 2.4.2 : Partie_gagnee
function Partie_gagnee($tableau_affichage, &$partie_gagnee, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales)
{
    $nb_cases_masquees = 0;

    for( $y =0 ; $y < $nb_cases_verticales; $y++)
    {
        for( $x =0 ; $x < $nb_cases_horizontales ; $x++)
        {
            if( $tableau_affichage[$x][$y] == 'M' )
            {
                $nb_cases_masquees++;
            }
        }
    }

    if( $nb_cases_masquees == $nb_bombes)
    {
        $partie_gagnee=true;
    }
}

// FP3 : Affichage()
function Affichage($tableau_affichage, $tableau_information, $partie_perdue, $partie_gagnee, $nb_cases_horizontales, $nb_cases_verticales)
{
    if($partie_perdue==true)
    {
        echo 'Partie perdue' . PHP_EOL;
        Afficher_grille_entiere($tableau_information, $nb_cases_horizontales, $nb_cases_verticales);
    }
    elseif($partie_gagnee==true)
    {
        echo 'Partie gagnee' . PHP_EOL;
        Afficher_grille_entiere($tableau_information, $nb_cases_horizontales, $nb_cases_verticales);
    }
    else
    {
        Afficher_grille_selon_deroulement($tableau_information, $tableau_affichage, $nb_cases_horizontales, $nb_cases_verticales);
    }
}

// FP 3.1 : Afficher_grille_entiere()
function Afficher_grille_entiere($tableau_information, $nb_cases_horizontales, $nb_cases_verticales)
{
    for( $y =1 ; $y < $nb_cases_verticales+1; $y++)
    {
        for( $x =1 ; $x < $nb_cases_horizontales+1; $x++)
        {
            if($tableau_information[$x][$y] == -1)
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

// FP 3.2 : 
function Afficher_grille_selon_deroulement($tableau_information, $tableau_affichage, $nb_cases_horizontales, $nb_cases_verticales)
{
    for( $y =0 ; $y < $nb_cases_verticales; $y++)
    {
        for( $x =0 ; $x < $nb_cases_horizontales; $x++)
        {
            if($tableau_affichage[$x][$y] == 'F')
            {
                echo 'F ';
            }
            elseif ( $tableau_affichage[$x][$y] == 'M' )
            {
                echo 'M ';
            }
            else
            {
                echo $tableau_information[$x+1][$y+1] . ' ';
            }
        }
        echo PHP_EOL;
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
$partie_perdue=null;
$partie_gagnee=null;

Initialiser($tableau_information, $tableau_affichage, $nb_cases_horizontales, $nb_cases_verticales, $nb_bombes);
Affichage($tableau_affichage, $tableau_information, $partie_perdue, $partie_gagnee, $nb_cases_horizontales, $nb_cases_verticales);

do
{
    Coup_joueur($tableau_affichage, $tableau_information, $nb_bombes, $nb_cases_horizontales, $nb_cases_verticales,$partie_perdue, $partie_gagnee);
    Affichage($tableau_affichage, $tableau_information, $partie_perdue, $partie_gagnee, $nb_cases_horizontales, $nb_cases_verticales);

    $partie_terminee=false;

    if( ($partie_gagnee==true) || ($partie_perdue==true) )
    {
        $partie_terminee=true;
    }

} while($partie_terminee=false );
 