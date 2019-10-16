# Processus

[&](#&)  
[Ctrl-Z](#Ctrl-Z)  
[exec](#exec)  
[fg / bg](#fg-/-bg)  
[jobs](#jobs)  
[kill](#kill)  
[nice et renice](#nice-et-renice)  
[nohup](#nohup)  
[pkill](#pkill)  
[ps](#ps)  
[time](#time)

## Définition et environnement

Processus : programme en cours d'exécution + son environnement d'exécution

| Données d'identification d'un processus | signification                                                                                                   |
| --------------------------------------- | --------------------------------------------------------------------------------------------------------------- |
| PID (Process ID)                        | Identifiant de processus. _init_ a comme PID : 1                                                                |
| PPID (Parent Process ID)                | Identifiant du processus parent qui a lancé celui en cours. PID 0 = pseudo-processus qui lance _init_           |
| UID et GID                              | Identifiant de l'utilisateur et du groupe qui a lancé le processus. Les processus enfants héritent de ces infos |
| Durée de traitement                     | Temps d'exécution depuis le dernier réveil du processus                                                         |
| Priorité                                | Dans un environnement multi-tâche, tous les processus ne possèdent pas la même priorité.                        |
| Répertoire de travail actif             | Répertoire courant dans lequel le processus a été lancé                                                         |
| Fichiers ouverts                        | Au lancement : 3 (canaux standards). À la fermeture du processus, les descripteurs sont fermés (en principe)    |

## État d'un processus

| Process state                                                |
| ------------------------------------------------------------ |
| **User mode** mode utilisateur                               |
| **Kernel mode** mode noyau                                   |
| **Waiting** en attente I/O                                   |
| **Stopped** endormi                                          |
| **Runnable** prêt à l'exécution                              |
| **Swap** endormi dans le swap                                |
| **Zombie** terminé mais toujours dans la table des processus |

## Lancement en tâche de fond

### &

Lance en tâche de fond : ajouter `&` après la commande.

`ls -R / > ls.txt 2>/dev/null &`

### Ctrl-Z

Reprend la main sous le shell et suspend le processus.

```bash
$ sleep 100
^Z
[1]+  Stopped                 sleep 100
```

### jobs

Liste des jobs (lancés à partir du terminal courant)

```bash
sleep 45 &
[1] 6722
$ sleep 75
^Z
[2]+  Stopped                 sleep 75
$ sleep 200 &
[3] 6724
$ jobs
[1]   Running                 sleep 45 &
[2]+  Stopped                 sleep 75
[3]-  Running                 sleep 200 &
```

> _+_ : Job courrant (celui appelé par défaut avec la commande `fg` ou `bg`)  
> _-_ : Job précédant

### fg / bg

Remet un processus au premier plan : `fg [n]`  
Met un processus à l'arrière-plan : `bg [n]`

## Liste des processus

### ps

Lancée seule, elle n'affiche que les processus en cours lancés par l'utilisateur et depuis la console actuelle.

```bash
$ ps
  PID TTY          TIME CMD
 5711 pts/0    00:00:00 bash
 6723 pts/0    00:00:00 sleep
 6969 pts/0    00:00:00 ps
[3]-  Done                    sleep 200
```

> TTY : _Terminal Type Writer_ Terminal natif (pas de mode graphique)
> PTS : _Pseudo Terminal Slave_ Émulé par un autre programme (connexion ssh, xterm, ou encore le terminal de Gnome)

Plus d'informations avec le paramètre `-f`

```bash
$ ps -f
UID        PID  PPID  C STIME TTY          TIME CMD
serge     9548  9538  0 09:28 pts/0    00:00:00 bash
serge    11778  9548  0 09:34 pts/0    00:00:00 ps -f
```

> La colonne C indique en nombre entier le pourcentage d'utilisation du CPU pour ce process

Le paramètre `-e` donne des informations sur tous les processus en cours.

```bash
 ps -ef
UID        PID  PPID  C STIME TTY          TIME CMD
root         1     0  0 06:14 ?        00:00:08 /sbin/init splash
...
serge     1256     1  0 06:14 ?        00:00:00 /lib/systemd/systemd --user
serge     1257  1256  0 06:14 ?        00:00:00 (sd-pam)
serge     1308  1256  0 06:14 ?        00:00:00 /usr/bin/dbus-daemon --session -
serge     1414  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfsd
serge     1419  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfsd-fuse /run/us
serge     1429  1256  0 06:14 ?        00:00:00 /usr/lib/at-spi2-core/at-spi-bus
serge     1434  1429  0 06:14 ?        00:00:00 /usr/bin/dbus-daemon --config-fi
root      1459     1  0 06:14 ?        00:00:00 /usr/lib/upower/upowerd
rtkit     1478     1  0 06:14 ?        00:00:00 /usr/lib/rtkit/rtkit-daemon
root      1498   882  0 06:14 ?        00:00:00 /sbin/dhclient -d -q -sf /usr/li
whoopsie  1562     1  0 06:14 ?        00:00:00 /usr/bin/whoopsie -f
kernoops  1572     1  0 06:14 ?        00:00:00 /usr/sbin/kerneloops --test
kernoops  1574     1  0 06:14 ?        00:00:00 /usr/sbin/kerneloops
serge     1676  1256  0 06:14 ?        00:00:00 /usr/libexec/xdg-permission-stor
serge     1685  1256  0 06:14 ?        00:00:00 /usr/lib/gnome-shell/gnome-shell
serge     1689  1256  0 06:14 ?        00:00:00 /usr/lib/evolution/evolution-sou
serge     1697  1256  0 06:14 ?        00:00:00 /usr/lib/gnome-online-accounts/g
root      1719     1  0 06:14 ?        00:00:00 /usr/lib/x86_64-linux-gnu/boltd
serge     1723  1256  0 06:14 ?        00:00:00 /usr/lib/dconf/dconf-service
serge     1737  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfs-udisks2-volum
serge     1785  1256  0 06:14 ?        00:00:00 /usr/lib/gnome-online-accounts/g
serge     1786  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfs-gphoto2-volum
serge     1800  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfs-goa-volume-mo
serge     1807  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfs-afc-volume-mo
serge     1814  1256  0 06:14 ?        00:00:00 /usr/lib/gvfs/gvfs-mtp-volume-mo
root      1868     1  0 06:14 ?        00:00:12 /usr/lib/packagekit/packagekitd
...
serge    11814  9548  0 09:37 pts/0    00:00:00 ps -ef
...
```

> Le `?` dans la colonne TTY (Terminal Typewriter) signifie qu'aucun terminal n'est attaché à cette commande.

Le paramètre `-u` filtre par utilisateurs.
Le paramètre `-g` filtre par groupes.
Le paramètre `-t` filtre par terminaux.
Le paramètre `-p` filtre par PID.

```bash
$ ps -u root
  PID TTY          TIME CMD
    1 ?        00:00:09 systemd
    2 ?        00:00:00 kthreadd
    3 ?        00:00:00 rcu_gp
    4 ?        00:00:00 rcu_par_gp
    6 ?        00:00:00 kworker/0:0H-kb
    8 ?        00:00:00 mm_percpu_wq
    9 ?        00:00:00 ksoftirqd/0
   10 ?        00:00:05 rcu_sched
   11 ?        00:00:00 migration/0
   12 ?        00:00:00 idle_inject/0
   13 ?        00:00:03 kworker/0:1-cgr
   14 ?        00:00:00 cpuhp/0
   ...
```

Le paramètre `-l` propose encore plus d'informations techniques

```bash
$ ps -l
F S   UID   PID  PPID  C PRI  NI ADDR SZ WCHAN  TTY          TIME CMD

...
0 R  1000  9538  1256  0  80   0 - 201575 -     ?        00:00:04 gnome-terminal-
0 S  1000  9548  9538  0  80   0 -  7494 wait   pts/0    00:00:00 bash
0 S  1000  9561  8817  0  80   0 - 162847 poll_s tty4    00:00:00 update-notifier
0 S  1000  9564  8817  0  80   0 - 337248 poll_s tty4    00:00:03 gnome-software
0 S  1000  9628  1256  1  80   0 - 303590 poll_s ?       00:00:17 code
0 S  1000  9630  9628  0  80   0 - 94003 poll_s ?        00:00:00 code
0 S  1000  9663  9628  1  80   0 - 147579 poll_s ?       00:00:23 code
1 S  1000  9682  9630  4  80   0 - 288558 futex_ ?       00:00:56 code
0 S  1000  9712  9682  0  80   0 - 183892 ep_pol ?       00:00:05 code
0 S  1000  9734  9682  0  80   0 - 149769 ep_pol ?       00:00:00 code
0 S  1000  9760  9682  0  80   0 - 149889 ep_pol ?       00:00:00 code
1 S  1000 10026  9630  0  80   0 - 195873 futex_ ?       00:00:00 code
0 S  1000 10758  8817  0  80   0 - 197445 poll_s tty4    00:00:00 deja-dup-monito
4 R  1000 11975  9548  0  80   0 - 11119 -      pts/0    00:00:00 ps
```

> _PRI_ : Priorité  
> _S_ : Process status (S = Stopped, R = Running, Z = Zombie)  
> _F_ : Flags (`man ps` pour plus d'infos)  
> _ADDR_ : Adresse mémoire du process  
> _SZ_ : Mémoire virtuelle utilisée  
> _WCHAN_ : Adresse mémoire de l'évènement que le process attend  
> _NI_ : Valeur de `nice` (ajoutée ou retirée à PRI)

## Arrêt d'un processus / signaux

Pas de `Ctrl-C` pour une tâche de fond...

### kill

Envoie un signal à un processus

`kill [-l] -num_signal PID [PID2...]`

| Signal       | Rôle                                                                  |
| ------------ | --------------------------------------------------------------------- |
| 1 (SIGHUP)   | Envoyé à tous ses enfants lorsqu'il se termine                        |
| 2 (SIGINT)   | Interruption du processus demandé (Ctr-C)                             |
| 3 (SIGQUIT)  | Idem que SIGINT mais génération d'un fichier de débuggage (core dump) |
| 9 (SIGKILL)  | Force le processus à se terminer immédiatement                        |
| 15 (SIGTERM) | Demande au processus de se terminer normalement. Peut être ignoré     |

```bash
$ sleep 100 &
[1] 12811
$ sleep 100 &
[2] 12812
$ sleep 100 &
[3] 12813
$ sleep 100 &
[4] 12814
$ jobs
[1]   Running                 sleep 100 &
[2]   Running                 sleep 100 &
[3]-  Running                 sleep 100 &
[4]+  Running                 sleep 100 &
$ kill -9 12814
$ jobs
[1]   Running                 sleep 100 &
[2]   Running                 sleep 100 &
[3]-  Running                 sleep 100 &
[4]+  Killed                  sleep 100
```

### pkill

Envoie un signal à une commande

`pkill commande`

```bash
$ sleep 100&
[1] 12724
$ sleep 100&
[2] 12725
$ jobs
[1]-  Running                 sleep 100 &
[2]+  Running                 sleep 100 &
$ pkill sleep
[1]-  Terminated              sleep 100
[2]+  Terminated              sleep 100
```

### nohup

_No Hang up_. Lorsqu'on quitte un shell, le signal 1 (SIGHUP) est envoyé à tous les processus enfants du shell. Pour éviter ça on utilisera nohup.

Dans un terminal :

```bash
nohup sleep 1000 &
[1] 12881
serge@LUDWIG-LINUX:~/www/CheatSheets$ nohup: ignoring input and appending output to 'nohup.out'

serge@LUDWIG-LINUX:~/www/CheatSheets$ jobs
[1]+  Running                 nohup sleep 1000 &
```

On ferme le terminal et on en ouvre un autre

```bash
ps -ef | grep sleep
serge    12881  1256  0 10:31 ?        00:00:00 sleep 1000
```

Le `?` indique bien que la commande a été détachée de pts/0

### nice et renice

`nice` lance une commande avec une priorité plus faible ou plus haute (entre -20 et +20).

`nice [valeur] commande [arguments]`

```bash
$ ps -l
F S   UID   PID  PPID  C PRI  NI ADDR SZ WCHAN  TTY          TIME CMD
0 S  1000 12896  9538  0  80   0 -  7461 wait   pts/1    00:00:00 bash
4 D  1000 12963 12896 90  90  10 -  9423 read_c pts/1    00:00:05 ls
4 R  1000 12966 12896  0  80   0 -  9005 -      pts/1    00:00:00 ps
```

`renice` permet de modifier la priorité en fonction d'un utilisateur, d'un groupe ou d'un PID. La commande visée doit déjà tourner.

`renice [-n prio] [-p] [-g] [-u] ID`

### time

Mesure les durées d'exécution d'une commande.

`time commande [arguments]`

| Valeur | Signification                                                 |
| ------ | ------------------------------------------------------------- |
| real   | durée totale d'exécution de la commande                       |
| user   | temps CPU nécessaire pour exécuter le programme               |
| system | temps CPU nécessaire pour exécuter les commandes liées à l'OS |

```bash
$ time ls -lR /home
real	0m0,552s
user	0m0,147s
sys   0m0,239s
```

> Le résultat sort sur le canal _stderr_ (2)  
> Indication de la charge : real / (user + system). Ici : 0.552 / (0.147 + 0.239) = 1.430051813  
>  Si le résultat est inférieur à 10, la machine dispose de bonne performances. Au-delà de 20 la charge est trop lourde.

### exec

Remplace le processus courant par un autre.

`exec commande [arguments]`

```bash
$ ps | grep bash
12896 pts/1    00:00:00 bash
$ exec ksh
$ ps | grep ksh
12896 pts/1    00:00:00 ksh
```

On a remplacé le shell courant par ksh : même PID. Si on quitte ksh, on quitte le shell.

> On peut connaître le PID du shell en affichant le contenu de la variable \\\\\$\$

```bash
$ echo $$
12896
```
