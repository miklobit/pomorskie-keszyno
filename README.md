# Pomorskie keszyno
Skrypt to zbieraczka wyników do projektu pomorskie-keszyno realizowanego w ramach gry opencaching.pl

# Powiązane projekty
OKAPI - https://github.com/opencaching/okapi

Opencaching.pl - https://github.com/mzylowski/opencaching-pl

jQuery tablesorter

#Licencja
Możesz dowolnie modyfikować ten kod na swoje potrzeby pod warunkiem pozostawienia zdania "Generated by Czarodziej, with OKAPI." pod tabelką z wynikami.

#Instalacja
- Konfigurujemy serwer tak by głównym katalogiem strony był www-data ('../' powinien być niedostępny)
- Poprawiamy informacje w pliku params.php - klucz do używania OKAPI oraz wyrażenie regularne opisujące skrzynki geocache które chcesz listować.
- Uzupełniamy plik keszyno_members.dat :

W pliku każda linia to jeden uczestnik projektu, gdzie:

id;nick;1/0(debiutant);punkty


id - Link do mojego profilu to http://opencaching.pl/viewprofile.php?userid=46365 a ID które powinno być pisane w pliku tekstowym to 46365

nick - wiadomo

debiutant - w pomorskim keszynie wyróżniamy debiutantów tj keszerów którzy muszą uzyskać akceptację COG przed publikacją skrzynki. 0 = normalny 1 = debiutant

punkty - punkty które mają być doliczone użytkownikowi. W tym miejscu ustawiamy punkty ujemne (tak robimy to ręcznie po każdym keszynie).


#Używanie
Spis keszy w projekcie można uzyskać otwierają w przeglądarce plik keszyno_lista_keszy.php

Ranking wszystkich uczestników: keszyno_ranking.php

Ranking debiutantów: keszyno_ranking_debiuty.php

Polecam osadzać powyższe tabele na swoich stronach za pomocą html iframe

#Działanie
Powyższe skrypty pobierają dane z plików tworzonych przez skrypt data_saver.php

Tak więc aby zaktualizować dane należy uruchomić ten skrypt. Zastosowano takie rozwiązanie by ograniczyć liczbę zapytań do bazy danych opencaching.

Proponuję uruchamiać ten skrypt np co 15 minut poprzez moduł cron:
*/15 * * * * /var/keszyno_data_saver.sh

Gdzie skrypt keszyno data_saver.sh ma postać:
wget -q -O /dev/null "http://cluster017.ovh.net/~mzylowsknf/data_saver.php" > /dev/null 2>&1


