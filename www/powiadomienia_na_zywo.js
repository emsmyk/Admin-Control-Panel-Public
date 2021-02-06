$(document).ready(function(){
  function getPowiadomieniaIlosc(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=ilosc_powiadomien',
          success: function(data){
              $('a#ilosc_powiadomien').html(data);
          }
      });
  }
  function getZadaniaIlosc(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=ilosc_zadan',
          success: function(data){
              $('a#ilosc_zadan').html(data);
          }
      });
  }
	function getWiadomoscIlosc(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=ilosc_wiadomosc',
          success: function(data){
              $('a#ilosc_wiadomosc').html(data);
          }
      });
  }
	getPowiadomieniaIlosc();
	getZadaniaIlosc();
	getWiadomoscIlosc();
	setInterval(function () {
      getPowiadomieniaIlosc();
      getZadaniaIlosc();
      getWiadomoscIlosc();
  }, 7200);


	function getPowiadomienia(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=powiadomienia',
          success: function(data){
              $('ul#powiadomienia').html(data);
          }
      });
  }
	function getZadania(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=zadania',
          success: function(data){
              $('ul#zadania').html(data);
          }
      });
  }
	function getWiadomosci(){
      $.ajax({
          type: 'POST',
          url: '?x=ajax&xx=header&xxx=wiadomosci',
          success: function(data){
              $('ul#wiadomosci').html(data);
          }
      });
  }
	$(document).on('click', 'a#ilosc_powiadomien', function(){
		getPowiadomienia();
	});
	$(document).on('click', 'a#ilosc_zadan', function(){
		getZadania();
	});
	$(document).on('click', 'a#ilosc_wiadomosc', function(){
		getWiadomosci();
	});

});
