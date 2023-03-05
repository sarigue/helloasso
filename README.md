# HelloAsso API and Notification callback

Bibliothèque pour utiliser les outils fournis par HelloAsso.com
* V3 : Manipulation de l'API v3
* V5 : Manipulation de l'API v5

## Prerequis

- PHP 5.6
- Webserver (Apache, ...)
- Un compte HelloAsso.com
- Un accès API (clé + password) HelloAsso

## Installation

Copier simplement le dossier contenant le code HelloAsso sur votre disque
(ou cloner le dépôt)

---
## Manipulation de l'API v5

Version conseillée.

### Initialisation

Initialiser la bibliothèque avec son client_id et son client_secret à l'aide
de la classe HelloAsso
puis appeler `authenticate()` pour lancer la requête d'autentification

```php 
require_once 'HelloAsso.php'; // Facultatif si autoload.php est chargé

\HelloAsso\V5\HelloAsso::initialize()
    ->setClient('my_client_id', 'my_secreat_id')
    ->setOrganization('organization-slug')
    ->authenticate()
;
```

### Les requêtes

Les `ResourceQuery` permettent d'effectuer une requête sur une ressource.

#### Liste de ressources : les Query

Exemple de récupération d'une liste de paiements :
```php
use \HelloAsso\V5\Resource\Query\Payment as PaymentQuery;
use \HelloAsso\V5\Api\Pagination;

$pagination = null; /* @var Pagination $pagination */
$payment_list = PaymentQuery::create()
    ->setFromDate(date('Y').'-01-01') // Depuis le premier janvier
    ->setToDate(date('Y-m-d')) // Jusqu'à aujourd'hui
    ->search() // Appel de la requête "search"
    ->getCollection($pagination) // Récupère la collection de Payment
    ;
    
echo 'Current page : ' . $pagination->page . PHP_EOL;
echo 'Max page : ' . $pagination->max_page . PHP_EOL;
echo 'Page size : ' . $pagination->result_per_page . PHP_EOL;

// --------------------
// Ou bien, en récupérant d'abord la réponse
// --------------------

$response = PaymentQuery::create()
    ->setFromDate(date('Y').'-01-01') // Depuis le premier janvier
    ->setToDate(date('Y-m-d')) // Jusqu'à aujourd'hui
    ->search() // Appel de la requête "search"
    ;

$payment_list = $response->getCollection();
    
echo 'Current page : ' . $response->getPagination()->page . PHP_EOL;
echo 'Max page : ' . $response->getPagination()->max_page . PHP_EOL;
echo 'Page size : ' . $response->getPagination()->result_per_page . PHP_EOL;
```

#### Un seul enregistrement: get()

Pour récupérer un seul enregistrement, on utilise la méthode `get()`

```php
use \HelloAsso\V5\Resource\Query\Payment as PaymentQuery;
use \HelloAsso\V5\Resource\Payment;

$payment_id = 3;
$payment = PaymentQuery::create()->get($payment_id)->getResource();

// -------------------------
// Ou bien sous forme statique :
// -------------------------

$payment_id = 3;
$payment = Payment::get($payment_id);
```

#### Tous les enregistrements: getAll()
```php
use \HelloAsso\V5\Resource\Payment;
use \HelloAsso\V5\Api\Pagination;

$pagination = null; /* @var Pagination $pagination */
$payment_list = Payment::getAll($pagination);

echo 'Current page : ' . $pagination->page . PHP_EOL;
echo 'Max page : ' . $pagination->max_page . PHP_EOL;
echo 'Page size : ' . $pagination->result_per_page . PHP_EOL;
```

#### Refresh d'une ressource

Lorsqu'une ressource peut-être incomplète, par exemple lorsqu'elle vient
d'une liste, d'une autre ressource, ou d'un _callback_,
il peut être nécessaire d'effectuer une requête  de type `get()`
pour rafraichir ses données

```php

use \HelloAsso\V5\Resource\Payment;

// Récupérer toutes les infos d'un ORDER depuis un PAYMENT

$payment_order = Payment::get(3)->order->refresh();

// Depuis un Callback

$callback = new \HelloAsso\V5\Callback();
if ($callback->isPayment())
{
    $payment = $callback->getPayment()->refresh();
}
```
Ne pas faire de refresh() dans ces cas, a pour conséquence
de n'avoir que des données partielles

### Callback

Lorsque HelloAsso envoie une notification sur l'URL de callback spécifiée,
les données concernant le payment / form / order sont transmises en POST
dans le corps de la requête.

La classe `Callback` permet de traiter ces données pour récupérer la ressource

```php
use HelloAsso\V5\Callback;

$callback = new Callback(); // Suffisant pour initialiser depuis le POST body

if ($callback->isPayment())
{
    $payment = $callback->getPayment(); // Données transmises (partielles)
    $payment->refresh(); // Requête cURL pour récupéer toute la ressource
}

if ($callback->isForm())
{
    $form = $callback->getForm(); // Données transmises (partielles)
    $payment->refresh(); // Requête cURL pour récupéer toute la ressource
}

if ($callback->isOrder())
{
    $order = $callback->getOrder(); // Données transmises (partielles)
    $order->refresh(); // Requête cURL pour récupéer toute la ressource
}

```
#### Ressources

Objets modélisant les données renvoyées par l'API

- \HelloAsso\V5\Resource\Payment - Un paiement
- \HelloAsso\V5\Resource\Organization - Infos sur la structure
- \HelloAsso\V5\Resource\Item - Item de vente
- \HelloAsso\V5\Resource\Form - Campagne (vente, adhésion, ...)
- \HelloAsso\V5\Resource\Order - Commande

##### Données structurées des ressources

- \HelloAsso\V5\Data\Payer - Informations payeur
- \HelloAsso\V5\Data\CustomField - Champ personnalisé
- \HelloAsso\V5\Data\CustomData - abstact - Liste de champs personnalisés
- \HelloAsso\V5\Data\User - Informations utilisateurs
- \HelloAsso\V5\Data\Meta - Informations de date création / modification
- \HelloAsso\V5\Data\Banner - Bannière de la campagne
- \HelloAsso\V5\Data\Amount - Infos montant : total, TVA, remise

##### Requêtes de ressources

- \HelloAsso\V5\Query\Form
- \HelloAsso\V5\Query\Item
- \HelloAsso\V5\Query\Order
- \HelloAsso\V5\Query\Payment
- \HelloAsso\V5\Query\PaymentRefund - Extension de Payment permettant d'appeller
la méthode refund()


#### Réponse API

Il s'agit de l'objet Api\Response

Les méthodes

- `setResourceClass(string) : Api\Response` : 
Permet de définir la classe qui sera utilisée par défaut par `getResource()` et
`getCollection()`. Utilisée pour l'usage générique de `ResourceQuery`.
- `getHttpCode() : int` : Retourne le code HTTP
- `isCollection() : boolean` : Si la réponse est une collection de resources
- `isError() : boolean` : Si la réponse est une erreur
- `throwException() : Api\Response` : Lancer l'exception éventuelle.
- `getException() : Api\ResponseError` : Retourne l'exception éventuelle
- `getData() : stdClass` : Données JSON décodées
- `getPagination() : Api\Pagination` : Pagination renvoyée par l'API
- `getResource(string) : Resource` : Retourne la ressource
- `getCollection(&Pagination) : Resource[]` : Retourne la liste de ressources

Voir les exemples pour l'utilisation de Query et Response

---

## Manipulation de l'API v3

Cette API est dépréciée

* HelloAsso.php est le point d'entrée et de configuration de l'API HelloAsso
* Callback.php et ses classes dérivées (dossier callback/) permet de manipuler les notifications
* Resource.php et ses classes dérivées (dossier resource/) permet de manipuler les ressources (objets)

### Utilisation

Inclure simplement HelloAsso.php puis configurer l'API avec la clé.

```php
require_once 'HelloAsso.php';

HelloAsso\HelloAsso::apiConfig("id-api-helloasso", "password-api-helloasso");
```

#### Utilisation partielle

Il est possible de n'utiliser que la partie API / Ressources HelloAsso et
utiliser moins de fichier

Inclure alors seulement Resource.php et supprimer le dossier callback/ et
le fichier Callback.php et HelloAsso.php

La configuration se fait alors en définissant les variables statiques
`\HelloAsso\Api\Query::setDefaultAuth($api_id, $api_pass)`;

```php
require_once 'Resource.php';

use HelloAsso\Api\Query;

Query::setDefaultAuth("id-api-helloasso", "password-api-helloasso");
```

### Liste des objets

#### Callback

Objets modélisant les données transmises lors de la réception d'une notification
HelloAsso

- \HelloAsso\V3\Callback\Campaign : Notification de création/édition d'une campagne
- \HelloAsso\V3\Callback\Payment : Notification de paiement

Les méthodes :

- \HelloAsso\V3\Callback\Campaign::getCampaign() : \HelloAsso\V3\Resource\Campaign
- \HelloAsso\V3\Callback\Payment::getPayment() : \HelloAsso\V3\Resource\Payment
- \HelloAsso\V3\Callback\Payment::getAction() : \HelloAsso\V3\Resource\Action

#### Ressources

Objets modélisant les données renvoyées par l'API

- \HelloAsso\V3\Resource\BasicCampaign : Campagne basique (information publique)
- \HelloAsso\V3\Resource\Action
- \HelloAsso\V3\Resource\Campaign
- \HelloAsso\V3\Resource\Payment
- \HelloAsso\V3\Resource\Organism

### Les méthodes

#### Methodes getter API

Chaque ressource (sauf BasicCampaign) possède les méthodes statiques suivantes: 
- `get(string ID) : Resource`
- `getAll(& Pagination) : Resource[]`

BasicCampaign dispose des méthodes de recherche statiques

`searchForOrganismSlug(string $slug, string $type = NULL, int $page = NULL, int $results_per_page = NULL, Pagination & $pagination = NULL) : BasicCampaign[]`

pour le recherche des campagne d'un organisme donné par son slug et

`searchForOrganismId(string $id, string $type = NULL, int $page = NULL, int $results_per_page = NULL, Pagination & $pagination = NULL) : BasicCampaign[]`

pour le recherche des campagne d'un organisme donné par son id


#### Autres méthodes

Une action est liée à un paiement, une campagne et un organisme.
Les méthodes getters sont donc disponibles :

- \HelloAsso\V3\Resource\Action::getCampaign() : Campaign
- \HelloAsso\V3\Resource\Action::getOrganism() : Organism
- \HelloAsso\V3\Resource\Action::getPayment() : Payment

### Requête et réponse

#### Requête personnalisée

Pour créer et exécuter une requête d'API personnalisée, utiliser la classe
\HelloAsso\Api\Query

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

#### Réponse API

Il s'agit de l'objet Api\V3\Response

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

### Mode test

Ce mode permet de modifier les propriétés non modifiables des objets telles que
Callback\Payment::$action ou Callback\Payment::$payment
ou encore Api\Action::$organism, Api\Action::$payment, Api\Action::$campaign

Cela permet de les redéfinir à la volée pour pousser des données de test.

Le mode test peut se définir pour l'ensemble de HelloAsso

```php
HelloAsso\V3\HelloAsso::setTestMode(boolean)
```

Il peut aussi se définir indépendamment pour le callback et les resources

```php
HelloAsso\V3\Callback::setTestMode(boolean);
\HelloAsso\V3\Resource::setTestMode(boolean);
```

### Exemple

#### Callback de paiement

Exemple de réaction à la notification d'un paiement

```php

require_once 'helloasso/HelloAsso.php';

HelloAsso\HelloAsso::config("id-api", "password-api");
HelloAsso\HelloAsso::setTestMode(false);

$notification = HelloAsso\Callback::getPayment();
$organism  = $notification->getAction()->getOrganism()->name;
$campaign  = $notification->getAction()->getCampaign()->name;
$amount    = $notification->getPayment()->amount;
$firstname = $notification->getPayment()->payer_first_name;
$lastname  = $notification->getPayment()->payer_last_name;

echo "$firstname $lastname a payé la somme de $amount euros à $organism à l'occasion de la campagne : $campaign";

```

#### Requête personnalisée et réponse

Récupérer les paiements de l'année


```php

use HelloAsso\V3\Api\Query;
use HelloAsso\V3\Resource\Payment;

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


### Developpement

- Francois Raoult

### Licence 

Licence MIT - Voir [LICENSE](LICENSE)

