<?php

namespace App\Interfaces;

interface StatsRepositoryInterface
{
    /**
     * Récupère les statistiques des cours.
     *
     * @return array
     */
    public function getCourseStats();

    /**
     * Récupère les statistiques des catégories.
     *
     * @return array
     */
    public function getCategoryStats();

    /**
     * Récupère les statistiques des tags.
     *
     * @return array
     */
    public function getTagStats();
}