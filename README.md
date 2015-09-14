# Taux d'Occupation de Salle de réunion
mesure en temps réel le taux d'occupation d'une salle grace à un capteur infrarouge PIR et un Raspberry Pi
Matériel:
+ [HC-SR501](http://letmeknow.fr/shop/capteurs/83-capteur-de-mouvement-infra-rouge.html?search_query=PIR&results=1) : capteur de mouvement infrarouge
+ Raspberry Pi

![alt tag](https://github.com/famibelle/OccupationSalle/blob/master/Boitier%20Complet.jpg)
   :height: 100px
   :width: 200 px

### Installation des packages
```bash
$ sudo apt-get install apache
$ sudo apt-get install apache2
$ sudo apt-get install php5
$ sudo apt-get install libapache2-mod-php5
$ sudo apt-get install sqlite php5-sqlite
```

### Création de la base de données
```bash
$ sqlite3 PIRlog.db`
```

dans le shell de la base de données on crée la table qui va enregistrer les mouvements
```sql
BEGIN;
CREATE TABLE TxOccupationBox (timestamp DATETIME, motion INTEGER);`
COMMIT;
.quit
```

la table à deux champs, le timestamp qui enregistre la date et le champs motion qui prend deux valeurs 0 ou 1 (0 = pas de mouvements, 1 = mouvement)

On copie la base de données dans le répertoire /var/www/ et on change son ownership pour www-data
```bash
$ sudo cp PIRlog.db /var/www/
$ sudo chown www-data:www-data /var/www/PIRlog.db
```

Installation du script qui va rajouter tout les 5 mn des 0 dans la base de données
```bash
$ sudo chmod +x dummylogPIR.py
$ sudo chown www-data:www-data dummylogPIR.py
$ sudo cp dummylogPIR.py /usr/lib/cgi-bin/
$ sudo crontab -e
```

Il faut rajouter la ligne suivante puis faire CTRL^X pour installer le CRON job
`*/5 * * * * /usr/lib/cgi-bin/dummylogPIR.py`

### Installation du détecteur de mouvements en daemon(pour qu'il tourne en tache de fond)
l'inspiration vient d http://blog.scphillips.com/posts/2013/07/getting-a-python-script-to-run-in-the-background-as-a-service-on-boot/
```bash
$ sudo cp modmypiPIR.py /usr/lib/cgi-bin/
$ chmod +x modmypiPIR
$ sudo cp modmypiPIR /etc/init.d/
```

On verifie que le daemon marche
```bash
$ sudo /etc/init.d/modmypiPIR start
$ sudo /etc/init.d/modmypiPIR status
```

On installe le daemon
`$ sudo update-rc.d modmypiPIR defaults`

### fichier & emplacements
```
/var/www/json.php
/var/www/mouvements.php
/usr/lib/cgi-bin/dummylogPIR.py
/usr/lib/cgi-bin/modmypiPIR.py
/init.d/modmypiPIR
```

### Remarques : 
Mon Raspberry Pi a connu un SD card corrupt qui l'a rendu totalement inutilisable. Les commandes ci-dessous visent à prevenir ce problème (source [ideaheap](http://www.ideaheap.com/2013/07/stopping-sd-card-corruption-on-a-raspberry-pi/)

`sudo nano /etc/fstab`

on rajoute les lignes suivantes dans `/etc/fstab`
```
none	/var/run	tmpfs	size=1M, noatime	0	0
none	/var/log	tmpfs	size=1M, noatime	0	0
```

on désactive le swapping
```bash
$ sudo dphys-swapfile swapoff
$ sudo dphys-swapfile uninstall
$ sudo update-rc.d dphys-swapfile remove
```
Attention une fois les logs désactivés Apache2 ne marche plus.
