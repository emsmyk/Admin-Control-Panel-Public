# Admin Control Panel Public

Głównym założeniem projektu było stworzenie panelu który mógłby działać w tle sieci i nadzorować wszelkie procesy w projektach sieci. Mam tutaj na myśli wszelką maść problemów z nadzorowaniem Techników przez Właścicieli, braku informacji o pracach nad danym zakresem problemów zadań. Znikających tematach błędów zgłaszanych przez Opiekunów. Dodatkowy aspektem była myśl stworzenia konfiguratora serwerów, czyli pełny nadzór nad roundsoundem, reklamami, rangami, listami baz danych. Z całego tego zamieszania i pomysłów powstał panel, który zostaje udostępniony dla całej społeczności CS:GO.
Zdaję sobie sprawę z problemów, niedopracowań jak i również „jednostronnego” pisania kodu pod jedną siec, lecz zawsze poniży panel może służyć jako baza do czego lepszego, większego a może i nawet spowoduje to rozdział forum na rzecz takich wybranych i dostosowanych stron.

Projekt bazuje na: PHP 5.6, systemie szablonów bootstrap (Szablon AdminLTE 2)

Poziomy dostępu:
1. Użytkownik ROOT, nadawany z bazy, brak możliwości nadania z poziomu acp
2. Grupy użytkowników: każda grupa może mieć dostęp do wskazanego modułu a w nim do określonych funkcji, jeśli takowe są
3. Gość: publiczne strony


Instalacja:
1. Wgranie do katalogu strony www
2. Wgranie bazy danych
3. Edycja pliku var/confing php uzupełnienie danych dostępu do wgranej bazy
4. Dodanie zadań cronjobs, przykład:
	*	*/1	*	*	*	curl –silent https://acp.sloneczny-dust.pl/?x=cronjobs_optym > /dev/null
	*/2	*	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs_serwer > /dev/null
	0	*/1	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs_stats > /dev/null
	*/2	*	*	*	*	curl --silent https://acp.sloneczny-dust.pl/?x=cronjobs > /dev/null
	
5. Pierwsze logowanie:
	Login: admin
	Hasło: 3wart1jX
