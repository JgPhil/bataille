<?php

require "vendor/autoload.php";

use App\Entity\Orc;
use App\Entity\Witch;
use App\Entity\Goblin;

$goblin = new Goblin('Goblin');
$witch = new Witch('Witch');
$orc = new Orc('Orc');
$playersAlive = [$goblin, $witch, $orc];

while (count($playersAlive) > 1) {
    var_dump(count($playersAlive));
    for ($i = 0; $i < count($playersAlive); $i++) {
        //----- si empoisonné: -3 PV par tour
        if ($playersAlive[$i]->getPlague()) {
            $playersAlive[$i]->getDamage(3);
        }
        if ($playersAlive[$i]->getHealth() <= 0) {
            echo $playersAlive[$i]->getName() . " vient de trépasser &#9760 <br>";
            $offset = array_search($random_target, $playersAlive);
            array_splice($playersAlive, $offset, 1);
        }
        //----------- actions possibles de l'entité
        $action_methods = preg_grep('/_action/', get_class_methods($playersAlive[$i]));
        //---------------------- methode aléatoire
        $random_method = $action_methods[rand(0, count($action_methods) - 1)];
        //---------------------- cible aléatoire
        $targets = array_slice($playersAlive, 0);
        $selfOffset =  array_search($playersAlive[$i], $playersAlive);
        //---------------------- tableau intermédiare pour retirer le jour de la liste des cibles
        array_splice($targets, $selfOffset, 1);
        $random_target =  count($targets) > 0 ? $targets[rand(0, count($targets) - 1)] : $targets[0];
        //echo ("  joueur:  " . $playersAlive[$i]->getName() . "  cible: " . $random_target->getName() . "  action  :" . $random_method."<br>");
        //---------------------- Récupération des infos santé
        $initialHealth = $random_target->getHealth();        
        $damages = $initialHealth - $random_target->getHealth();
        //---------------------- ATTAQUE aléatoire 
        if ($random_method == 'heal_action') {
            $playersAlive[$i]->$random_method($playersAlive[$i]);
            echo $playersAlive[$i]->getName(), " se soigne de 5 points de vie <br>";
        } else {
            $playersAlive[$i]->$random_method($random_target);
            echo $playersAlive[$i]->getName() . ' attaque ' .
                $random_target->getName() . ' avec '
                . $random_method . ' et lui inflige '
                . $damages, " points de dégats <br>";
        }
        if ($random_target->getHealth() <= 0) {
            echo $random_target->getName() . " vient de trépasser :( <br>";
            $offset = array_search($random_target, $playersAlive);
            array_splice($playersAlive, $offset, 1);
        }
    }
    $rows = '';
    foreach ($playersAlive as $player) {
        $poison =  $player instanceof Witch ? "/" : ($player->getPlague() ? "oui" : "non");
        $rows .= '<tr><td>' . $player->getName() . '</td><td>' . $player->getHealth() . '</td> <td>' . $poison . '</td> </tr>';
    }
    echo    '<table class="table">
                <thead>
                    <tr>
                        <th>Persos</th>
                        <th>Points de vie</th>
                        <th>poison</th>
                    </tr>
                </thead>
                <tbody>
                    
                        ' . $rows . '
                   
                </tbody>
            </table>';
}
echo "Le vainqueur est " . $playersAlive[0]->getName() . ' il lui reste ' . $playersAlive[0]->getHealth() . ' PV';
