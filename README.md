# Taux d'Occupation de Salle de réunion
Le boitier permet de mesurer en temps réel le taux d'occupation d'une salle grace à un capteur infrarouge PIR (ref: HC-SR501) et un Raspberry Pi. La portée du capteur est entre 3m et 7m (ajustable) ce qui le rend parfait pour des salles de réunion de taille moyenne.

**Matériel:**
+ [HC-SR501](http://letmeknow.fr/shop/capteurs/83-capteur-de-mouvement-infra-rouge.html?search_query=PIR&results=1) : capteur de mouvement infrarouge
+ [Raspberry Pi](http://letmeknow.fr/shop/board/275-raspberry-pi-2-modele-b.html)
+ [Boitier](http://letmeknow.fr/shop/accessoires/68-boitier-pour-raspberry-pi.html) pour Raspberry Pi (en option)
+ [Connecteur BERG](http://www.stquentin-radio.com/index.php?page=info_produit&info=2305&color=9&id=0&act=0) FEMELLE (3 points) à connecter sur le HC-SR501
+ [Cosses BERG] (http://www.stquentin-radio.com/index.php?page=info_produit&info=2283&color=9&id=0&act=0)
+ Nappe 26cts ou plus, selon le modèle du Raspberry

**Boitier**

![alt tag](https://github.com/famibelle/OccupationSalle/blob/master/images/Boitier%20Complet.jpg)

**Schéma de connection**

![alt tag](https://github.com/famibelle/OccupationSalle/blob/master/images/Fritzing.png)

**Boitier en situation dans le box**

![alt tag](https://github.com/famibelle/OccupationSalle/blob/master/images/FreqBox2.jpg)

### Installation des packages
```bash
$ sudo apt-get install apache
$ sudo apt-get install apache2
$ sudo apt-get install php5
$ sudo apt-get install libapache2-mod-php5
$ sudo apt-get install sqlite php5-sqlite
```
### Récupération des packages 
```bash
$ git clone https://github.com/famibelle/OccupationSalle.git
```
### Installation des fichiers du server Apache dans le répertoire `/var/www`
```bash
$ sudo chown www-data:www-data hourly.php
$ sudo cp hourly.php /var/www/
$ sudo chown www-data:www-data mouvements.php
$ sudo cp mouvements.php /var/www/
$ sudo chown www-data:www-data index.html
$ sudo cp index.html /var/www/
$ sudo chown www-data:www-data js/colorbrewer.min.js
$ sudo cp js/colorbrewer.min.js /var/www/js/
```

### Création de la base de données
```bash
$ sqlite3 PIRlog.db
```

dans le shell de la base de données on crée la table qui va enregistrer les mouvements
```sql
BEGIN;
CREATE TABLE TxOccupationBox (timestamp DATETIME, motion INTEGER);
COMMIT;
.quit
```

La table a deux champs, le timestamp qui enregistre la date et le champs motion qui prend deux valeurs 0 ou 1 (0 = pas de mouvements, 1 = mouvement)

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
```bash
*/5 * * * * /usr/lib/cgi-bin/dummylogPIR.py
```
### Installation du détecteur de mouvements en daemon(pour qu'il tourne en tache de fond)
l'inspiration vient de [blog.scphillips.com](http://blog.scphillips.com/posts/2013/07/getting-a-python-script-to-run-in-the-background-as-a-service-on-boot/)
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


### Utilisation
Pour suivre le taux d'utilisation d'une salle de réunion il suffit de poser le boitier dans la salle de réunion et ensuite faire pointer son navigateur vers 
+ `http://@IP_Raspberry/index.html` pour avoir la vision cumulée ou vers
+ `http://@IP_Raspberry/mouvements.php`pour la vision chronologique


### fichier & emplacements
```
/var/www/hourly.php
/var/www/mouvements.php
/var/www/index.html
/usr/lib/cgi-bin/dummylogPIR.py
/usr/lib/cgi-bin/modmypiPIR.py
/init.d/modmypiPIR
```

### Remarques : 
Mon Raspberry Pi a connu un SD card corrupt qui l'a rendu totalement inutilisable. Les commandes ci-dessous visent à prevenir ce problème (source [ideaheap](http://www.ideaheap.com/2013/07/stopping-sd-card-corruption-on-a-raspberry-pi/))

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
### note d'usage
Je suis passé sous Raspberry PI B+. Le board offre plus de port GPIO mais le boitier ne correspond plus. J'ai testé un niveau type de nappes droites, mais le problème est que le port OUT du [HC-SR501](http://letmeknow.fr/shop/capteurs/83-capteur-de-mouvement-infra-rouge.html?search_query=PIR&results=1) est entre le VCC et le GND alors que sur le Raspberry Pi B+ le IN est à coté du VCC et du GND. J'ai bien essayé de forcer une sortie du GPIO à l'état haut pour simuler un VCC mais le capteur se met à détecter des dizaine de mouvememts fantomes par seconde. Bref ça marche pas.
```python
GPIO.setmode(GPIO.BCM)
PIR_PIN = 24
PIR_VCC = 23
GPIO.setup(PIR_PIN, GPIO.IN)
GPIO.setup(PIR_VCC, GPIO.OUT)           # met GPIO PIR_VCC comme VCC (emulation VCC)
GPIO.output(PIR_VCC, 1)
```
