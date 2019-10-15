# Bash (Bourne Again Shell)

## Table des matières

### Fichiers et répertoires

[ls](#`ls`), [touch](#`touch`), [mkdir](#`mkdir`), [rmdir](#`rmdir`), [cp](#`cp`), [mv](#`mv`), [rm](#`rm`), [ln](#`ln`), [find](#`find`), [whereis](#`whereis`), [which](#`which`)

### Redirections

[>](#`>`), [>>](#`>>`), [<](#`<`), [<<](#`<<`), [Canaux standards](#Canaux-standards), [exec](#`exec`)

### Les filtres

## Saisie

| Touche   | Action                          |
| -------- | ------------------------------- |
| `Ctrl-a` | Début de la ligne               |
| `Ctrl-e` | Fin de la ligne                 |
| `Ctrl-l` | Clear screen                    |
| `Ctrl-u` | Effacer la ligne jusqu'au début |
| `Ctrl-k` | Effacer la ligne jusqu'à la fin |

## Syntaxe générale

`Commande [paramètres] [arguments]`

## Fichiers et répertoires

### `ls`

Liste les fichiers

| Paramètres | Signification                                                                                                         |
| ---------- | --------------------------------------------------------------------------------------------------------------------- |
| `-l`       | détaillé                                                                                                              |
| `-a`       | afficher les fichiers cachés (dotted files)                                                                           |
| `-F`       | caractère à la fin du nom pour spécifier le type. / pour un dossier, \* pour un exécutable, @ pour un lien symbolique |
| `-R`       | Récursif                                                                                                              |
| `-t`       | tri par date de moficiation du plus récent au plus ancien                                                             |
| `-c`       | avec `-t` par date de changement d'état de fichier                                                                    |
| `-u`       | avec `-t` par date d'accès au fichier                                                                                 |
| `-r`       | ordre de sortie inversée                                                                                              |
| `-i`       | afficher l'_inode_ du fichier                                                                                         |
| `-1`       | afficher sur une seule colonne                                                                                        |

### `touch`

Créé un fichier vide. Si le fichier existe, met à jour la date de modification du fichier!

### `mkdir`

Créé un ou plusieurs répertoires, ou une arborescence complète (`-p` _parent_).

```bash
$ mkdir -p Archives/vieilleries
$ ls -R
./Arhives:
vieilleries

./Archives/vielleries
```

### `rmdir`

Supprime un ou plusieurs répertoires. Le répertoires doit être vide sinon retour d'erreur.

### `cp`

Copie un ou plusieurs fichiers vers un autre fichier ou un autre répertoire.

```bash
cp file1 [file2 ... filen] destination
```

| Paramètre | Signification                            |
| --------- | ---------------------------------------- |
| -i        | demande confirmation pour chaque fichier |
| -r        | récursif                                 |
| -p        | permissions et dates préservées          |
| -f        | forcer la copie                          |
| -a        | copie d'archive                          |

### `mv`

Déplace, renomme un fichier ou les deux.

```bash
$ touch text1
$ mv text1 text1.old
```

### `rm`

Supprime un ou plusieurs fichiers, et éventuellement une arborescence. **La suppression est définitive**

| Paramètre | Signification        |
| --------- | -------------------- |
| -i        | confirmation         |
| -r        | récursif             |
| -f        | force la suppression |

**Attention à la commande :** `sudo rm -rf \` Connue sous le nom **Commande de la mort** : Suppression de toute l'arborescence de votre disque dur.

### `ln`

Créé un lien symbolique (fichier contenant un chemin vers un autre fichier)

```bash
ln -s fichier lien
```

Si le fichier pointé par le lien est supprimé, le lien lui, n'est pas supprimé. (par exemple une clé USB, un disque amovible)

### `find`

`find chemin critères options`

La commande `find` est récursive.
Opérande par défaut `AND`

| Critères                   | Signification                                                                                                                                                         |
| -------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `-name`                    | par nom de fichier                                                                                                                                                    |
| `-type`                    | par type de fichier (d = directory, f = file, l = symbolic link ...)                                                                                                  |
| `-size`                    | par taille de fichier (c = byte, w = word, k = Ko, M = Mo, G = Go) précédée d'un + ou d'un -                                                                          |
| `-atime` `-mtime` `-ctime` | dernier accès ; dernière modification ; date de changement. (0 = jour même, 1 = hier, ...) précédée d'un + ou un - (+ = il y a plus de x jours, - = moins de x jours) |
| `perm`                     | par permissions (octal) précédée de / ou - (/ = au moins un des droits spécifiés, - = au moins tous les droits spécifiés)                                             |
| `-regex` `-iregex`         | par expressions régulière. Sensible ; insensible à la casse                                                                                                           |
| `-a`                       | AND (par défaut)                                                                                                                                                      |
| `-o`                       | OR                                                                                                                                                                    |
| `!`                        | Négation                                                                                                                                                              |

| Commandes | Signification                                                                                                                                                    |
| --------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `-ls`     | informations détaillés                                                                                                                                           |
| `-exec`   | exécute la commande juste après. Doit être la dernière option. la commande doit se terminer par `\;` La substitution de fichier trouvé doit être passée par `{}` |
| `-ok`     | comme `exec` mais avec demande de confirmation                                                                                                                   |

```
$ find -name a*
./.git/objects/5a/ae74c109549de3703f09d034e7bae23d1d4713
./.git/objects/ab
./.git/objects/c8/a781af8857c7a3d4e9fdd62a443fa838779a8b
./.git/objects/d8/a99f4dfdf8937d1b1df0bd3057225cdbdefd23

$ find -name remotes* -type d
./.git/logs/refs/remotes
./.git/refs/remotes

$ find -size -20c
./.git/COMMIT_EDITMSG
./serge.test

root@LUDWIG-LINUX:/# find -type d -perm -111         // chercher les dossiers où tout le monde peut accéder
./var/lib/snapd/void
./snap/core18/1144/var/lib/snapd/void
./snap/core18/1192/var/lib/snapd/void
./snap/core/7917/var/lib/snapd/void
./snap/core/7713/var/lib/snapd/void

sudo find . -regex '.*oaders.*'
./www/react.js/memory/node_modules/cosmiconfig/dist/loaders.js
./snap/spotify/common/.cache/gdk-pixbuf-loaders.cache
./snap/gnome-system-monitor/common/.cache/gdk-pixbuf-loaders.cache

find . -type f -name "*.mp3" -exec rm -f {} \;      // Efface tous les fichiers terminant par .mp3

find . ! -name "*fic*"                              // Trouve les fichiers ne contenant pas 'fic'
```

### `whereis`

Recherche dans les chemins de fichiers binaires, du manuel et des sources

```bash
serge@LUDWIG-LINUX:~$ whereis ls
ls: /bin/ls /usr/share/man/man1/ls.1.gz
```

### `which`

Recherce une commande dans le PATH et fournit la première qu'elle trouve

```bash
serge@LUDWIG-LINUX:~$ which ls
/bin/ls
```

## Redirections

Attention que le shell recherche les signes de redirections de droite à gauche.

### `>`

Redirige vers un fichier

```bash
$ ls -l > serge.test
$ cat serge.test
total 16
drwxr-xr-x 2 serge serge 4096 oct.  14 10:43 Linux
-rw-r--r-- 1 serge serge  463 oct.  13 12:28 PostgreSQL.md
-rw-r--r-- 1 serge serge   52 oct.  13 11:59 README.md
-rw-r--r-- 1 serge serge    0 oct.  14 10:53 serge.test
-rw-r--r-- 1 serge serge  163 oct.  14 08:20 Vocabulary.md
```

### `>>`

Ajoute des données en sortie

```bash
$ cat serge.test
total 16
drwxr-xr-x 2 serge serge 4096 oct.  14 10:43 Linux
-rw-r--r-- 1 serge serge  463 oct.  13 12:28 PostgreSQL.md
-rw-r--r-- 1 serge serge   52 oct.  13 11:59 README.md
-rw-r--r-- 1 serge serge    0 oct.  14 10:53 serge.test
-rw-r--r-- 1 serge serge  163 oct.  14 08:20 Vocabulary.md
lundi 14 octobre 2019, 10:55:01 (UTC+0200)
```

### `<`

Les commandes qui attendent des données ou des paramètres depuis le clavier peuvent aussi en recevoir depuis un fichier.

```bash
$ wc < serge.test
  7  53 332
```

### `<<`

_Herescript_ ou _Here Document_. Documents en ligne.
Après le `<<` indiquez une chaîne définissant la fin de la saisie (ici _end_).

```bash
$ tr "[a-z]" "[A-Z]" << end
> je suis
> en minuscule
> mais plus maintenant
> end
JE SUIS
EN MINUSCULE
MAIS PLUS MAINTENANT
```

### Canaux standards

- **stdin** canal d'entrée : 0
- **stdout** canal de sortie : 1
- **stderr** canal d'erreur

```bash
$ rmdir dossier-inexistant 2>error.log
$ cat error.log
rmdir: failed to remove 'dossier-inexistant': No such file or directory
```

Rediriger deux canaux de sortie dans un seul fichier.

```bash
$ ls -l > serge.test 2>&1
```

La sortie 2 est redirigée vers la sortie 1, donc les messages d'erreurs passeront par la sortie standard.

### `exec`

La commande **exec** permet d'ouvrir sept autres canaux numérotés de 3 à 9.

```bash
$ exec 5>dump.log
$ ls -m >&5
$ cat dump.log
dump.log, error.log, Linux, PostgreSQL.md, README.md, serge.test, Vocabulary.md
```

Fermer le canal

```bash
$ exec 5>&-
```

## Les filtres

### `basename`

Extrait le nom d'un fichier

```bash
$ basename Linux/bash.md
bash.md
```

### `dirname`

Extrait le chemin d'un fichier

```bash
$ dirname Linux/bash.md
Linux
```

### `grep`

Extraire des lignes selon divers critères

`grep [options] modèle [Fichier1...]`

| options | signification                                        |
| ------- | ---------------------------------------------------- |
| -v      | recherche inverse                                    |
| -c      | retourne le nombre de lignes trouvées                |
| -i      | insensible à la casse                                |
| -n      | indique le numéro de ligne pour chaque ligne trouvée |
| -l      | indique dans quel fichier apparaît la ligne trouvée  |

```bash
$ cat serge.test
chien
chat
furet
rat
cochon d inde
hamster
chinchilla
dègue du chili
gerbille
souris
lapin domestique
lapin nain
alpaga
cheval
âne
chèvre naine

cochon vietnamien$ grep "^[cs]" serge.test
chien
chat
cochon d inde
chinchilla
souris
cheval
chèvre naine
cochon vietnamien

$ grep -c "^[cs]" serge.test
8

$ grep -v "^[cs]" serge.test
furet
rat
hamster
dègue du chili
gerbille
lapin domestique
lapin nain
alpaga
âne
```

### `egrep`

grep étendu et peut accepter un fichier de critères en entrée. ERE (extented regular expression)

`egrep -f fichier_critère fichier_recherche`

(pour plus d'informations ; [regex](../Regex.md))

### `fgrep`

_fast grep_ simplifié et rapide. Pas de caractères spéciaux.

### `sed`

_Stream editor_ permettant de filtrer et de transformer du texte.

`sed -e '<cmd> ' fic`

Syntaxe basique de substitution :

`s/<ancien>/<nouveau>/[g]`

le `g` final permet de faire un remplacement sur toute la ligne s'il y a plusieurs occurences.

```bash
$ echo "Je m appelle __NOM__. Tu t appelles __NOM__" | sed -e "s/__NOM__/Serge/g"
Je m appelle Serge. Tu t appelles Serge
```

### `cut`

Sélectionne des colonnes et des champs dans un fichier.

`cut -cColonnes [fic1...]`

```bash
$ cat serge.test
Produit  prix   quantités
souris   15     20
clavier  35     20
écran    150    4
disque   70     12
$ cut -c1-8 serge.test
Produit
souris
clavier
écran
disque
$ cut -c10-13 serge.test
prix
15
35
150
70
```

Pour les champs le délimiteur est attribué à l'aide du paramètre : `-d` (par défaut la tabulation)

`cut -dc -fChamps [fic1...]`

```bash
$ cat serge.test
Produit - prix - quantités
souris - 15 - 20
clavier - 35 - 20
écran - 150 - 4
disque - 70 - 12
$ cut -d- -f1 serge.test
Produit
souris
clavier
écran
disque
```

Exemple concret :

```bash
$ cut -d: -f1,3 /etc/group | sort
adm:4
audio:29
avahi:122
avahi-autoipd:112
backup:34
bin:2
bluetooth:113
cdrom:24
colord:123
crontab:105
daemon:1
dialout:20
dip:30
disk:6
fax:21
floppy:25
...
```

### `wc`

_Word count_ compte les lignes, les mots et les caractères.

`wc [-l] [-c] [-w] [-m] fic1`

| Paramètres | Signification        |
| ---------- | -------------------- |
| `-l`       | nombre de lignes     |
| `-c`       | nombre d'octets      |
| `-w`       | nombre de mots       |
| `-m`       | nombre de caractères |

```bash
serge@LUDWIG-LINUX:~/www/CheatSheets$ cut -d: -f1,3 /etc/group > serge.test
serge@LUDWIG-LINUX:~/www/CheatSheets$ wc serge.test
 68  68 697 serge.test
```

### `sort`

Trie des lignes.

`sort [options] [-k pos1[,pos2]] [fic1...]`

| Options | Signification                        |
| ------- | ------------------------------------ |
| `-d`    | tri dictionnaire                     |
| `-n`    | tri numérique                        |
| `-b`    | Ignore les espaces en début de champ |
| `-f`    | Ignore la casse                      |
| `-r`    | Tri inverse                          |
| `-tc`   | nouveau délimiteur de champ c        |

Exemple : trier le fichier sur la deuxième colonne (-k 2) par ordre décroissant (-r), le délimiteur étant `:` (-t:) et de manière numérique (-n)

```bash
$ sort -r -n -t: -k 2 serge.test
nogroup:65534
serge:1000
postgres:128
mysql:127
sambashare:126
gdm:125
geoclue:124
colord:123
avahi:122
pulse-access:121
pulse:120
saned:119
scanner:118
whoopsie:117
lpadmin:116
...
```

### `uniq`

Supprime des doublons.

Exemple : Liste des GID (group id) réellement utilisés.

```bash
$ sudo cut -d: -f4 /etc/passwd | sort -n | uniq
0
1
2
5
7
8
12
13
14
25
49
51
62
...
```

### `join`

Joint deux fichiers en fonction d'un champ commun.

`join [-tc] [-1 n] [-2 m] fic1 fic2`

| Parmaètres | Signification             |
| ---------- | ------------------------- |
| `-1`       | Champ du premier fichier  |
| `-2`       | Champ du deuxième fichier |
| `-tc`      | délimiteur de champ c     |
