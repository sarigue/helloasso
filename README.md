# HelloAsso API and Notification callback

Outils pour utiliser les outils fournis par HelloAsso.com

* HelloAsso.php est le point d'entrée et de configuration de l'API HelloAsso
* Callback.php et ses classes dérivées (dossier callback/) permet de manipuler les notifications
* Resource.php et ses classes dérivées (dossier resource/) permet de manipuler les ressources (objets)

## Prerequis

- PHP 5.6
- Webserver (Apache, ...)
- Un compte HelloAsso.com
- Un accès API (clé + password) HelloAsso

## Installation

Copier simplement le dossier contenant le code HelloAsso sur votre disque (ou cloner le dépôt)

## Utilisation

Inclure HelloAsso.php puis configurer l'API avec la clé.


```php
require_once <path>.'/HelloAsso.php';

\HelloAsso::apiConfig("id-api-helloasso", "password-api-helloasso");
```

### Utilisation partielle

Il est possible de n'utiliser que la partie API / Ressources HelloAsso et utiliser moins de fichier

Inclure alors seulement Resource.php et supprimer le dossier callback/ et le fichier Callback.php et HelloAsso.php

La configuration se fait alors en définissant les variables statiques `\HelloAsso\Api\Query::setDefaultAuth($api_id, $api_pass)`;

```php
require_once <path>.'/Resource.php';

use HelloAsso\Api\Query;

Query::setDefaultAuth("id-api-helloasso", "password-api-helloasso");
```

## Liste des objets

### Callback

Objets modélisant les données transmises lors de la réception d'une notification HelloAsso

- \HelloAsso\Callback\Campaign : Notification de création/édition d'une campagne
- \HelloAsso\Callback\Payment : Notification de paiement

Les méthodes :

- \HelloAsso\Callback\Campaign::getCampaign() : \HelloAsso\Resource\Campaign
- \HelloAsso\Callback\Payment::getPayment() : \HelloAsso\Resource\Payment
- \HelloAsso\Callback\Payment::getAction() : \HelloAsso\Resource\Action

### Ressources

Objets modélisant les données renvoyées par l'API

- \HelloAsso\Resource\BasicCampaign : Campagne basique (information publique)
- \HelloAsso\Resource\Action
- \HelloAsso\Resource\Campaign
- \HelloAsso\Resource\Payment
- \HelloAsso\Resource\Organism

## Les méthodes

### Methodes getter API

Chaque ressource (sauf BasicCampaign) possède les méthodes statiques suivantes: 
- `get(string ID) : Resource`
- `getAll(& Pagination) : Resource[]`

BasicCampaign dispose des méthodes de recherche statiques

`searchForOrganismSlug(string $slug, string $type = NULL, int $page = NULL, int $results_per_page = NULL, Pagination & $pagination = NULL) : BasicCampaign[]`

pour le recherche des campagne d'un organisme donné par son slug et

`searchForOrganismId(string $id, string $type = NULL, int $page = NULL, int $results_per_page = NULL, Pagination & $pagination = NULL) : BasicCampaign[]`

pour le recherche des campagne d'un organisme donné par son id


### Autres méthodes

Une action est liée à un paiement, une campagne et un organisme.
Les méthodes getters sont donc disponibles :

- \HelloAsso\Resource\Action::getCampaign() : Campaign
- \HelloAsso\Resource\Action::getOrganism() : Organism
- \HelloAsso\Resource\Action::getPayment() : Payment

## Requête et réponse

### Requête personnalisée

Pour créer et exécuter une requête d'API personnalisée, utiliser la classe \HelloAsso\Api\Query

Les paramètres du constructeur sont :

- string ressource : La ressource à chercher, ou bien le nom (full-qualified. utiliser ::class) de la classe ressource
- string id (facultatif) : l'ID de la ressource à récupérer

Les méthodes :

- Methode statique `Query::create(string ressource[, string id])` pour récupérer une nouvelle requete
- `setId(string)` pour l'ID de la ressource à récupérer
- `setPage(int)` le numéro de page à récupérer
- `setResultsPerPage(int)` le nombre de résultat par pages
- `setCampaignId(string)` id de la campagne sur laquelle limiter la recherche
- `setOrganismId(string)` id de l'organisme sur lequel limiter la recherche
- `setOrganismSlug(string)` slug de l'organisme pour limiter la recherche à cet organisme - **uniquement pour l'API publique**
- `setPublic()` pour utiliser l'API publique (prend éventuellement le booléen en paramètre)
- `setPrivate()` pour utiliser l'API privé (prend éventuellement le booléen en paramètre)
- `addParam(string, string)` pour ajouter des paramètres de recherche
- `execute()` pour exécuter la requête **retourne une Api\Response**
- `build()` pour retourner l'URL de requête depuis les éléments donnés

Les méthodes sont chaînables (sauf build() qui retourne une chaine de caractère). Par exemple : `Query::create('payments')->setOrganimId('id')->execute()`

### Réponse API

Il s'agit de l'objet Api\Response

Les méthodes 

- `setResourceClass(string) : Api\Response` : Permet de définir la classe qui sera utilisée par défaut par `getResource()`. Cette fonction est utilisée par la classe `Query` lorsque c'est une classe qui est passée au constructeur. Permet d'éviter la répétion et les erreurs en permettant de chaîner `Query::create(RessouceClass::class)->[...]->execute()->throwException()->getResource()`
- `getHttpCode() : int` : Retourne le code HTTP
- `getData() : stdClass` : Retourne les données JSON décodées
- `getPagination() : Api\Pagination` : Retourne la pagination renvoyée par l'API
- `getResource(string) : Resource` : Parse les données récupérée comme la ressource de classe donnée.
- `isCollection() : boolean` : Pour savoir si la réponse est une collection de donnée
- `isError() : boolean` : Pour savoir si la réponse est une erreur
- `getException() : Api\Exception` : Pour récupérer l'exception éventuelle
- `throwException() : Api\Response` : Pour lancer l'exception éventuelle. Si pas d'exception, retourne Api\Response. Cette méthode permet de chainer avec Query::execute()

Voir les exemples pour l'utilisation de Query et Response

## Mode test

Ce mode permet de modifier les propriétés non modifiables des objets telles que Callback\Payment::$action ou Callback\Payment::$payment
ou encore Api\Action::$organism, Api\Action::$payment, Api\Action::$campaign

Cela permet de les redéfinir à la volée pour pousser des données de test.

Le mode test peut se définir pour l'ensemble de HelloAsso 

```php
\HelloAsso::setTestMode(boolean)
```

Il peut aussi se définir indépendamment pour le callback et les resources

```php
\HelloAsso\Callback::setTestMode(boolean);
\HelloAsso\Resource::setTestMode(boolean);
```

## Exemple

### Callback de paiement

Exemple de réaction à la notification d'un paiement

```php

require_once 'helloasso/HelloAsso.php';

\HelloAsso::config("id-api", "password-api");
\HelloAsso::setTestMode(false);

$notification = \HelloAsso\Callback::getPayment();
$organism  = $notification->getAction()->getOrganism()->name;
$campaign  = $notification->getAction()->getCampaign()->name;
$amount    = $notification->getPayment()->amount;
$firstname = $notification->getPayment()->payer_first_name;
$lastname  = $notification->getPayment()->payer_last_name;

echo "$firstname $lastname a payé la somme de $amount euros à $organism à l'occasion de la campagne : $campaign";

```

### Requête personnalisée et réponse

Récupérer les paiement de l'année


```php

use HelloAsso\Api\Query;
use HelloAsso\Resource\Payment;

// Paiements de mon organisme depuis le début de l'année

$response = 
Query::create(Payment::class)
->setPage(1)                           // Premiere page 
->setResultsPerPage(50)                // 50 résultats par page
->addParam('from', date('Y').'-01-01') // Depuis le 1er janvier
->addParam('to', date('Y-m-d'))        // Jusqu'à aujourd'hui
->execute()                            // Exécute la requête
->throwException();                    // Lance l'exception si elle existe

$code       = $response->getHttpCode();
$pagination = $response->getPagination();
$paiements  = $response->getResource(Payment::class);

/*
 * Note : Puisqu'on a initialisé Query::create() avec le nom de la classe ("Payment::class"),
 * on peut aussi utiliser ici directement $response->getResource() sans paramètre 
 */
 
echo "Code HTTP resultat : $code" . PHP_EOL;
echo "Pagination : Page {$pagination->page} / {$pagination->$max_page} ({$pagination->result_per_page} résultats par page)" . PHP_EOL;

echo count($paiements) . ' paiements trouvés' . PHP_EOL;

var_export($paiements);

echo PHP_EOL;

```


## Developpement

- Francois Raoult

## Licence 

Licence MIT - Voir [LICENSE](LICENSE)

