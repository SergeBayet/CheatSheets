# Modéle OSI

Norme qui préconise comment les ordinateurs devraient communiquer entre eux.  
Le modèle OSI comporte 7 couches :

![7 couches OSI](https://user.oc-static.com/files/257001_258000/257865.png)

Le modèle TCP/IP n'utilise pas les couches 5 et 6.  
Les couches 1 à 4 sont les couches réseaux.

Règles d'or :

- Chaque couche est indépendante
- Chaque couche ne peut communiquer qu'avec une couche adjacente

Lors de l'envoi des données, on parcourt le modèle OSI de haut en bas

## Couche 1 : physique

Fournir le support de transmission de la communication.

### Matériel

Le câble le plus utilisé aujourd'hui est la paire torsadée (moins sujet aux perturbations électromagnétiques), munie de prise RJ45. La fibre potique est utilisé chez les opérateurs Internet.

![Câble à paires torsadées](https://user.oc-static.com/files/258001_259000/258409.jpg)

On utilise 2 paires pour le moment. On l'appelle ausii le 10BT, 100BT ou 1000BT (10 Mbps, 100 Mbps, 1000 Mbps) le T étant pour _twisted_.

Prises mâle et femelle RJ45

| Mâle                                                                          | Femelle                                                                          |
| ----------------------------------------------------------------------------- | -------------------------------------------------------------------------------- |
| ![Prise mâle RJ45](https://user.oc-static.com/files/258001_259000/258433.png) | ![Prise femelle RJ45](https://user.oc-static.com/files/258001_259000/258438.png) |

Pour relier deux machines directement entre elles, il faut un câble **croisé**

![RJ45 croisé](https://user.oc-static.com/files/258001_259000/258536.png)

Aujourd'hui les prises femelles sont capbles de s'adapter et d'inverser les connexions de transmission et réception si besoin.

![RJ45 droit avec switch](https://user.oc-static.com/files/258001_259000/258537.png)

Aujourd'hui, on peut utiliser indiféremment des câbles droits ou croisés.

Avec du vieux matériel, une simple règle : On doit utiliser un câble croisé pour connecter deux matériels de même type.

Il y a deux catégories de matériel :

- les matériels de connexion (switch, hub...)
- les matériels connectés (les ordinateurs, imprimantes, routeurs...)

![Hub](https://user.oc-static.com/files/258001_259000/258538.png)
![Switch](https://user.oc-static.com/files/258001_259000/258439.png)

> **Hub** : concentrateur. Il reçoit l'information et l'envoie partout  
> **Switch** : commutateur. Il reçoit une information et l'envoie uniquement à son destinataire (quand il le connait), sinon il l'envoie à tout le monde.

### Topologie réseau

Manière selon laquelle on branche les machines entre elles.

- **Topologie** en bus : machines branchées sur le même câble
- **Topologie** en anneau : sur le même câble mais bouclé sur lui-même en cercle
- **Topologie** en étoile : machines branchées à une machine centrale qui elle même sait envoyer les informations à une machine en particulier

| En bus                                                               | En anneau                                                               | En étoile                                                               |
| -------------------------------------------------------------------- | ----------------------------------------------------------------------- | ----------------------------------------------------------------------- |
| ![En bus](https://user.oc-static.com/files/259001_260000/259026.png) | ![En anneau](https://user.oc-static.com/files/259001_260000/259027.png) | ![En étoile](https://user.oc-static.com/files/259001_260000/259029.png) |

La topologie en étoile est la seule qui permet d'étendre son réseau aussi bien en taille qu'en nombre de machines. Les réseaux en anneau et en bus sont en voie de disparition.

### CSMA/CD

_Carrier Sense Multiple Access/Collision Detection_

Quand deux machines parlent en même temps sur le réseau, on parle de collision. On ne peut pas éviter les collisions mais on peut les limiter. Le CSMA/CD limite le nombre de collisions en organisant le droit à la parole.

Règles du CSMA/CD

1. On écoute en permanence sur le bus pour savoir si quelqu'un parle ou s'il y a une collision
2. On ne peut parler que quand le bus est libre
3. Si jamais on parle, mais qu'une collision survient, on doit se taire
4. Le premier transmetteur qui découvre la collision envoi un signal spécial **Jam Signal** à toutes les autres machines.
5. On attend un temps aléatoire
6. On reparle
7. Si jamais il y a une collision, on revient à l'étape 4, sinon, c'et correct.

![CSMA/CD avec hub. Réseau en étoile](https://azizozbek.ch/wp-content/uploads/2018/01/CSMA-CD.gif)

## Couche 2 : Liaison de données

Connecter des machines sur un réseau local et détecter des erreurs de transmission.

### Adresse MAC

_Media Access Control_

Pour pouvoir parler à une machine, il faut être capable de l'identifier : **l'adresse MAC**.

L'adresse MAC est codé sur 6 octets (2<sup>48</sup> bits) soit plus de 280 mille milliards d'adresses MAC possibles. Chaque adresse MAC est unique au monde. Chaque constructeur de carte réseau achète les trois premiers octets, et toutes les cartes réseaux qu'il produira auront pour adresse MAC ces 3 premiers octets : xx:xx:xx:00:00:01 ; xx:xx:xx:00:00:02 etc. Il pourra donc écouler 1 6777 216 cartes réseau.

**L'adresse MAC de broadcast** (ff:ff:ff:ff:ff:ff) identifie n'importe quelle carte réseau. Elle permet d'envoyer un message à toutes les cartes réseaux des machines présentes sur le réseau en une seule fois.

### Protocole Ethernet

Protocole (langage) pour communiquer entre les machines. Définit le format des messages. Ces messages sont appelés **trames** (_frames_ en anglais)

Selon la deuxième règle d'or (Chaque couche ne peut communiquer qu'avec une couche adjacente), la couche deux peut connaître le protocole de la couche 3 et doit l'insérer dans la trame.

Pour détecter l'intégrité du message, un **CRC** est calculé par la source à partir des données et est recalculé par le destinataire. Si les réponses sont différentes il y a une erreur de transmission et on redemande le message en diminuant la taille des données. Si par contre les réponses sont identiques, c'est bon et on peut tenter d'augmenter la taille des données...

| **Adresse MAC destinatiare** | **Adresse MAC source** | **Protocole de couche 3** | Données          | **CRC**  |
| ---------------------------- | ---------------------- | ------------------------- | ---------------- | -------- |
| 6 octets                     | 6 octets               | 2 octets                  | 46 à 1500 octets | 4 octets |

> CRC : Contrôle de redondance cyclique (cyclic redundancy check)

Les informations en gras dans le tableau ci-dessus représente l'**entête Ethernet** (18 octets)

Exemple de communication entre une machine A et B

- Une application sur la machine A veut envoyer des données à une autre application sur une machine B.
- Le message parcourt les couches du modèle OSI de haut en bas.
- La couche 3 indique à la couche 2 quel protocole a été utilisé.
- La couche 2 peut alors former la trame et l'envoyer sur le réseau.
- La machine B reçoit la trame et regarde l'adresse MAC de destination.
- C'est elle ! elle lit donc la suite de la trame.
- Grâce à l'information sur le protocole de couche 3 utilisé, elle peut envoyer les données correctement à la couche 3.
- Le message remonte les couches du modèle OSI et arrive à l'application sur la machine B.

### Le commutateur (switch)

Relie plusieurs machines entre elles.

> **Pont ou bridge** : Switch avec seulement deux ports.

![Exemple de réseau en étoile avec switchs](https://user.oc-static.com/files/264001_265000/264887.jpg)
