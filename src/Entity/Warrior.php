<?php

namespace App\Entity;


abstract class Warrior
{
    protected $name;
    protected $health;
    protected $plague = false;
    protected $degats;

    public function __construct(string $name, int $health)
    {
        $this->name = $name;
        $this->health = $health;
    }


    /**
     * Get the value of health
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set the value of health
     *
     * @return  self
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of plague
     */
    public function getPlague()
    {
        return $this->plague;
    }

    /**
     * Set the value of plague
     *
     * @return  self
     */
    public function setPlague()
    {
        $this->plague = true;
        return $this;
    }


    public function getDamage(int $degats)
    {
        $this->health -= $degats;
        return $this;
    }
}
