<?php

namespace HelloAsso\V3;


use HelloAsso\V3\Api\Query;

/**
 * Point d'entrée de la bibliothèque HelloAsso
 *
 * @author fraoult
 * @license MIT
 */
class HelloAsso
{
    /**
     * Configurer l'ID de l'api et le mot de passe
     * @param string $id
     * @param string $password
     */
    public static function apiConfig($id, $password)
    {
        Query::setDefaultAuth($id, $password);
    }

    /**
     * Organisme par défaut pour la recherche des ressources
     * @param string $id
     * @param string $slug
     */
    public static function setDefaultOrganism($id, $slug = NULL)
    {
        Query::setDefaultOrganismId($id);
        Query::setDefaultOrganismSlug($slug);
    }

    /**
     * Campagne par défaut pour la recherche des ressources
     * @param string $id
     */
    public static function setDefaultCampaign($id)
    {
        Query::setDefaultCampaingId($id);
    }

    /**
     * Mode test : autorise de réécrire une propriété protégée
     * pour lui forcer une valeur
     * @param string $test_mode
     */
    public static function setTestMode($test_mode)
    {
        Resource::setTestMode($test_mode);
        Callback::setTestMode($test_mode);
    }
}


