<?php

namespace App;

use App\Entity\Orc;
use App\Entity\Witch;
use App\Entity\Goblin;
use App\Entity\Warrior;

class Battle
{

    private $status;
    private $summary = "";
    private $goblin;
    private $witch;
    private $orc;
    private $playersAlive;
    private $players;

    public function __construct()
    {
        $this->goblin = new Goblin('Goblin', 100);
        $this->witch = new Witch('Witch', 50);
        $this->orc = new Orc('Orc', 100);
        $this->playersAlive = [$this->goblin, $this->witch, $this->orc];
        $this->players = array_slice($this->playersAlive, 0);
    }


    public function nextTurn():array
    {
        if (count($this->playersAlive) > 1) {
            for ($i = 0; $i < count($this->playersAlive); $i++) {
                $this->plague($this->playersAlive[$i]);
                $this->maybeSuccumbs($this->playersAlive[$i]);
                $method = $this->getRandomMethod($this->playersAlive[$i]);
                $target = $this->searchRandomTarget($this->playersAlive[$i]);
                $this->fight($this->playersAlive[$i], $method, $target);
                $this->maybeSuccumbs($target);
            }
        }
        else {
            $this->summary .= "Le vainqueur est " . $this->playersAlive[0]->getName() . ' il lui reste ' . $this->playersAlive[0]->getHealth() . ' PV';
        }        
        return [$this->tablePlayers(), $this->summary];
    }

    public function maybeSuccumbs(Warrior $warrior): bool
    {
        if ($warrior->getHealth() <= 0) {
            $offset = array_search($warrior, $this->playersAlive);
            array_splice($playersAlive, $offset, 1);
            $this->summary .= $warrior . ' vient de trépasser &#9760 <br>';
            return true;
        }
        return false;
    }

    public function plague(Warrior $warrior): bool
    {
        if ($warrior->getPlague()) {
            $warrior->getDamage(3);
            $this->summary .= $warrior->getName() . ' est empoisonné, et a subi 3 points de dégats <br>';
            return true;
        }
        return false;
    }

    public function getRandomMethod(Warrior $warrior): string
    {
        //----------- actions possibles de l'entité
        $action_methods = preg_grep('/_action/', get_class_methods($warrior));
        //var_dump($playersAlive[$i]);
        //---------------------- methode aléatoire
        return $action_methods[rand(0, count($action_methods) - 1)];
    }

    public function searchRandomTarget(Warrior $warrior): Warrior
    {
        $targets = array_slice($this->playersAlive, 0); // copie du tableau
        $selfOffset =  array_search($warrior, $this->playersAlive); // recherche le propre index du joueur
        //---------------------- tableau intermédiare pour retirer le jour de la liste des cibles
        array_splice($targets, $selfOffset, 1);
        $random_target =  count($targets) > 0 ? $targets[rand(0, count($targets) - 1)] : $targets[0];
        return $random_target;
    }

    public function fight(Warrior $attacker, $randMethod, Warrior $victim)
    {
        //---------------------- Récupération des infos santé
        $initialVictimHealth = $victim->getHealth();
        if ($randMethod == 'heal_action') { // Witch self healing
            $attacker->$randMethod($attacker);
            $this->summary .= $attacker->getName() . " se soigne de 5 points de vie<br>";
        } else {
            $attacker->$randMethod($victim);
            $damages = $initialVictimHealth - $victim->getHealth();
            $this->summary .= $attacker->getName() . ' attaque ' .
                $victim->getName() . ' avec '
                . $randMethod . ' et lui inflige '
                . $damages . " points de dégats <br>";
        }
    }

    public function tablePlayers():string
    {
        $rows = '';
        foreach ($this->players as $player) {
            $poison =  $player instanceof Witch ? "/" : ($player->getPlague() ? "oui" : "non");
            $health = $player->getHealth() > 0 ? $player->getHealth() : 0;
            $rows .= '<tr><td>' . $player->getName() . '</td><td>' . $health . '</td> <td>' . $poison . '</td> </tr>';
        }
        return    '<h3><b>Bilan du tour</b></h3>
        <table class="table">
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
                </table>
                -----------------------------------------';
    }

    public function getSummary()
    {
        return $this->summary;
    }
}
