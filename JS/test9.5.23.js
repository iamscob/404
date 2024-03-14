// 11
class Auto {

   constructor(rok, przebieg, cena_wyjsciowa, cena_koncowa) {

      this.rok = rok;

      this.przebieg = przebieg;

      this.cena_wyjsciowa = cena_wyjsciowa;

      this.cena_koncowa = cena_koncowa;

   }

   zwiekszCene = function () { this.cena_wyjsciowa += 1000 }

   obnizCeneWgWieku = function () {
      let wiek = new Date().getFullYear() - this.rok;
      let obnizka = Math.floor(wiek * 1000);
      this.cena_koncowa -= obnizka;

   }
   obnizCeneWgPrzebiegu = function () {

      let obnizka = Math.floor(this.przebieg / 100000) * 10000;

      this.cena_koncowa -= obnizka;
   }
   dodajPrzebiegIRok = function (przebieg, rok) {
      this.przebieg = przebieg;
      this.rok = rok;
      this.obnizCeneWgWieku();
      this.obnizCeneWgPrzebiegu();
   }

}

let samochody = [];

function dodajAuto(rok, przebieg, cena_wyjsciowa, cena_koncowa) {

   let noweAuto = new Auto(rok, przebieg, cena_wyjsciowa, cena_koncowa);

   if (noweAuto.cena_wyjsciowa > 10000) {

      samochody.push(noweAuto);
   }

}
function zwiekszRok() {
   samochody.forEach(function (auto) {

      auto.rok++;

   });
}

let PolskiFIat = new Auto(1007, 20000, 15000, 40000);







// zad12
class Ocena {
   constructor(przedmiot, wartosc) {
      this.przedmiot = przedmiot;
      this.wartosc = wartosc;
   }
}
class Student {
   constructor(imie, nazwisko) {
      this.imie = imie;
      this.nazwisko = nazwisko;
      this._oceny = [];
      this.srednia = 0;
   }
   hello() {
      return `Witaj ${this.imie} ${this.nazwisko}, twoja srednia to: ${this.srednia}.`;
   }
   get oceny() {
      let tekst = "";
      for (const ocena of this._oceny) {

         tekst += `Przedmiot: ${ocena.przedmiot} - ocena ${ocena.wartosc}. `;
      }
      return tekst;
   }

   set oceny(x) {
      if (x instanceof Ocena) {
         this._oceny.push(x);
         this.srednia = this._oceny.reduce((a, b) => a + b.wartosc, 0) / this._oceny.length;
      } else {
         console.log("habibi, sprawdź co wpisałeś");
      }
   }
}
let s = new Student("Jan", "Kowalski");
s.oceny = new Ocena("HKJ", 2);
s.oceny = new Ocena("TIN", 10);
s.oceny = new Ocena("PRI", 2);


