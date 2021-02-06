# Admin Control Panel Public

Głównym założeniem projektu było stworzenie panelu który mógłby działać w tle sieci i nadzorować wszelkie procesy w projektach sieci. Mam tutaj na myśli wszelką maść problemów z nadzorowaniem Techników przez Właścicieli, braku informacji o pracach nad danym zakresem problemów zadań. Znikających tematach błędów zgłaszanych przez Opiekunów. Dodatkowy aspektem była myśl stworzenia konfiguratora serwerów, czyli pełny nadzór nad roundsoundem, reklamami, rangami, listami baz danych. Z całego tego zamieszania i pomysłów powstał panel, który zostaje udostępniony dla całej społeczności CS:GO.
Zdaję sobie sprawę z problemów, niedopracowań jak i również „jednostronnego” pisania kodu pod jedną siec, lecz zawsze poniży panel może służyć jako baza do czego lepszego, większego a może i nawet spowoduje to rozdział forum na rzecz takich wybranych i dostosowanych stron.

Projekt bazuje na: PHP 5.6, systemie szablonów bootstrap (Szablon AdminLTE 2)

Możliwości:
1.	Wpisy:
Moduł do wymiany wiadomości publicznych między userami systemu, możliwość oznaczania ogłoszeń, które są prezentowane na stronie głównej. Daje pewnego rodzaju szansę na wymianę informacji na czymś poza forum.
2.	Zadania:
System umożliwiający prezentowanie progresu, postępu działań na sieci. Kilka etapów postępu pracy nad zadaniem, system komentarzy oraz pełne logi. Dodatkowo możliwość stworzenia publicznego linku i przekazania danych z zadania na np. forum. Przydatną funkcją jest możliwość stworzenia pozycji todo, które mogą prezentować postęp prac nad danym zadaniem. W ramach tej możliwości została stworzona dodatkowo zakładka z mini statystykami. Statystyki te przedstawiają rozkład ilości zadań w danym statusie oraz procentowe wykonanie zadań przyjętych przez Techników.
3.	Usługi:
Bardzo prosty system mini/mikro sklepu który ma nadać usługę na określony czas. Jest to moim zdaniem dobre miejsce na nadawanie usług wszelkim legendom, wspierającym czy nagród za topx. W ustawieniach tej zakładki możemy dodawać usługi, definiować serwery na których dana usługa działa oraz jakie flagi ma otrzymać. Usługi są oparte na edycji pliku admins simple który możemy zlokalizować w katalogu configs naszego sourcemoda.
4.	Konkurencja;
Wykorzystanie irytujących powiadomień push do nadzoru innych sieci. Dzięki tej funkcji można w zupełności zapomnieć hasła do multikont na innych sieciach, ponieważ skrypt, pobierze nam ostatnie aktualności z innych for.
5.	Galeria Map:
Znany temat na sieciach wielu for csgo, gdzie znajdziemy listę map z dopasowanym do nich obrazkiem. Jako że temat zwykle jest mało wykorzystywany a obrazki zwykle muszą być robione często powstał pomysł aby wykorzystać api imgura i za jego pomocą stworzyć jedną dużą bazę obrazków. Mamy swego rodzaju pewność, że obrazki będą w sieci długo, a wgranie jest banalne. Dodatkiem miłym jest tak zwany znak wodny, którego tekst możemy zdefiniować w ustawieniach systemu. Warto też wspomnieć że wgrywane obrazki są zmieniane pod względem rozdzielczości w celu ujednolicenia wielkości.
6.	Pluginy:
W zależności od pomysłu wykorzystania, moim celem było danie administracji pewnego rodzaju lokalizacji biblioteki podstawowych pluginów działających praktycznie na każdym serwerze.  Miejsca w którym możemy sprawdzać, weryfikować poprawki jak i zlecać wgranie plików na serwer.
System dodatkowo ma możliwość oznaczania plików jako kod źródłowy czy starszą wersję. Dzięki takiemu zabiegowi, pliki np. sp nie trafią do plików serwera.
7.	Roundsound
Bazując na listach przebojów, powstał pomysł stworzenia katalogu piosenek. Piosenki są proponowane przez stronę publiczna które możemy linkować na forum. Społeczność może oddać również głos zadowolenia na utwory i również sprawdzić aktualną listę jak i tworzoną na np. kolejny miesiąc. Publiczna lista posiada możliwość otworzenia fragmentu utworu, przejścia na serwis youtube czy też pobiera obrazek jako okładki albumu.
8.	Kalkulator Slotów
Nowatorska funkcja umożliwiająca przeliczanie slotów i analizowanie ile dodać ile skasować. Celem jest aby serwer był jak najbardziej pełny i zyski z sprzedaży vipów były duże. Brak miejsca w godzinach szczytu = darmowy slot dla vipa
9.	Serwery Ustawienia
10.	Serwery Konfiguracja
1.	Reklamy: Cieżko opisać tą zakładę, znany plugin od reklam w say, csay czy msay. Skrypt acp dodatkowo posiada możliwość dodania czasowej reklamy, czy reklamy która będzie na serwerach jakimś zakresie dni wskazanego miesiąca.
2.	Rangi: System hextags, aktualizacja pliku.
3.	Bazy danych: Uproszczony edytor pliku database.cfg
4.	Mapy: lista map UMC lub mapchoster
5.	Help Menu: Zakładka współdziałająca z pluginem napisanym przez @Pynio, która ma za zadanie wygenerować listę serwerów z detalami, listę adminów z statusem steam oraz menu pomocy z listą potrzebnych komend.
6.	Tagi: Edytor svtags, polega na systematycznym zmienianiu zawartości tagów.
11.	Changelog: logi serwerów
12.	Wgrywarka: dane z prac, zleconych wgrywek plików
13.	Raporty: Lista raportów Opiekuna
14.	Kokpit Serwerów: Informacje o połączenie ftp oraz rcon, system informuje nas o problemach. (zakładka moim zdaniem nie skończona)
15.	Detale Serwerów:
1.	Detale serwera
1.	obrazek mapy pobierany z galerii map
2.	podstawowe informacje o serwerze
2.	Regulamin
Umożliwia dodanie linku lub regulaminu jako tekstu, chciałem to wykorzystać ale raczej zapomniane. Celowałem aby regulamin był wykorzystywany głównie z systemu acp, nie na ociężałych forach.
3.	Ustawienia Podstawowe
4.	Lista Adminów
1.	status steam, nick steam, awatar admina
2.	dodawanie, usuwanie, edycja admina z sourcebans
3.	zgodność nicku sb z steam
4.	Ilość adminów z danym statusem steam
5.	Chat Say, wyświetlanie czatu say z bazdy hlstats
6.	Prace zdalne: infromacja o aktualizacji danych plików danego serwera, oraz o błędach które system napotkał.
7.	Source update: aktualizator sourcemod i metamod, wskazana zakładka sprawdza za nas czy pojawiła się nowa wersja. Gdy tak jest mamy o tym powiadomienie.
8.	Consola RCON
9.	Statystyki serwera:
1.	Ilość graczy w zestawieniach godzinnowy, dziennych czy miesięcznych z podziałem na ilość wyników.
2.	Gosetti: Wykresy ilości punktów oraz pozycji serwera.
16.	 ACP systemowe:
1.	Użytkownicy: dodawanie, usuwanie, generowanie nowego hasła, edycja
2.	Grupy: dodawanie, edytowanie, kasowanie
3.	Moduły
4.	Ustawienia
5.	Logi
17.	Strony publiczne
1.	Lista serwerów, detale serwerów
2.	Lista adminów
3.	Roundsound
4.	Detale Zadania
5.	Głosowanie gosetti: jedna strona wiele serwerów
6.	Changelog
7.	Galeria Map

Poziomy dostępu:
1.	Użytkownik ROOT, nadawany z bazy, brak możliwości nadania z poziomu acp
2.	Grupy użytkowników: każda grupa może mieć dostęp do wskazanego modułu a w nim do określonych funkcji, jeśli takowe są
3.	Gość: publiczne strony


Instalacja:
1.	Wgranie do katalogu strony www
2.	Wgranie bazy danych
3.	Edycja pliku var/confing php uzupełnienie danych dostępu do wgranej bazy
4.	Dodanie zadań cronjobs, przykład:
*	*/1	*	*	*	curl –silent https://acp.sloneczny-dust.pl/?x=cronjobs_optym > /dev/null
*/2	*	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs_serwer > /dev/null
0	*/1	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs_stats > /dev/null
*/2	*	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs > /dev/null
	
5.	Pierwsze logowanie:
Login: admin
Hasło: 3wart1jX

GIT: github

Prosiłbym o nie ścieranie mnie z ziemi za może stary kod, małe zabezpieczenia czy też bugi i błędy. Nie jest to system skończony i wydany dla firmy za 10k, nie jest to również system na licencji.
Chciałbym tylko w społeczność CSGO tchnąć pewną bryzę nowego i może spowodować jakieś dobre zmiany.

