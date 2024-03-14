$(document).ready(function () {
   function fetch() {
      $.ajax({
         url: 'http://szuflandia.pjwstk.edu.pl/~ppisarski/zad8/dane.php',
         dataType: 'json',
         success: function (data) {
            updtStc(data.stock);
            updtNws(data.news);
         },
         error: function (xhr, status, error) {
            console.error('Bład, ', error);
         }
      });
   }
   function updtStc(stocks) {
      var table = $('#table');
      table.empty();
      var tableHeaders = '<tr><th>Nazwa drogiej firmy</th><th>Jakieś przydatne liczby</th></tr>';
      table.append(tableHeaders);
      $.each(stocks, function (name, price) {
         var row = '<tr><td>' + name + '</td><td>' + price + '</td></tr>';
         table.append(row);
      });
   }
   var lastThreeNews = [];

   function updtNws(news) {
      var rotatorElements = $('.rotator');
      if (news && (typeof news === 'string' || Array.isArray(news))) {
         rotatorElements.empty();
         if (typeof news === 'string') {
            lastThreeNews.push(news);
         } else if (Array.isArray(news)) {
            lastThreeNews = lastThreeNews.concat(news);
         }
         if (lastThreeNews.length > 3) {
            lastThreeNews = lastThreeNews.slice(lastThreeNews.length - 3);
         }

         for (var i = 0; i < lastThreeNews.length; i++) {
            $(rotatorElements[i]).text(lastThreeNews[i]);
         }
      }
   }
   setInterval(fetch, 10000);
   fetch();
});